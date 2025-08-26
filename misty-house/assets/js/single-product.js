jQuery(function($){

  /* ─────────────────────────────
     Tiny toast helper
  ───────────────────────────── */
  function toast(msg){
    var $t=$('<div class="mh-toast"></div>')
      .text(msg)
      .css({
        position:'fixed',right:'20px',bottom:'20px',
        background:'#000',color:'#fff',border:'2px solid gold',
        padding:'10px 14px',borderRadius:'10px',
        opacity:0,transition:'opacity .25s',zIndex:9999,
        boxShadow:'0 0 12px gold'
      });
    $('body').append($t);
    requestAnimationFrame(()=>{$t.css('opacity',1)});
    setTimeout(()=>{$t.css('opacity',0);setTimeout(()=>{$t.remove()},250)},3000);
  }

  /* ─────────────────────────────
     Main ↔ Thumb swap
  ───────────────────────────── */
  $(document).on('click','.mh-thumb',function(){
    var $thumb=$(this), $main=$('#mh-main-img');
    var ts=$thumb.attr('src'), tf=$thumb.data('full'), tr=$thumb.data('role');
    var ms=$main.attr('src'), mf=$main.data('full'), mt=$main.data('thumb'), mr=$main.data('role');

    $main.attr('src', tf)
         .data('full', tf)
         .data('thumb', ts)
         .data('role', tr)
         .removeAttr('srcset');

    $thumb.attr('src', mt)
          .data('full', mf)
          .data('thumb', mt)
          .data('role', mr);
  });

  /* ─────────────────────────────
     Enable/disable Buy logic
  ───────────────────────────── */
  function toggleBuy(){
    var $btn  = $('#mh-buy-button');
    var $qty  = $('#mh-quantity');
    var $sizes = $('.size-selector .size-btn');

    var hasSizes = $sizes.length > 0;
    var universalVisible = $sizes.filter(function(){
      return $(this).text().trim().toUpperCase() === 'UNIVERSAL';
    }).length > 0;

    var $selected = $sizes.filter('.selected:not(.disabled)');
    var requiresSelection = hasSizes && !universalVisible;
    var ok = !requiresSelection || $selected.length > 0;

    $btn.prop('disabled', !ok);
    $qty.prop('disabled', !ok && requiresSelection);

    if ($selected.length){
      var label = $selected.text().trim();
      $('#selected_size').val(label);
      if ($selected.data('variation-id') !== undefined){
        $('#variation_id').val($selected.data('variation-id') || '');
      }
      var st = parseInt($selected.data('stock'), 10);
      if (!isNaN(st) && st > 0) { $qty.attr('max', st); }
      else { $qty.removeAttr('max'); }
    }
  }

  $(document).on('click','.size-selector .size-btn:not(.disabled)',function(){
    $('.size-btn').removeClass('selected');
    $(this).addClass('selected');
    toggleBuy();
  });
  toggleBuy(); // initial pass

  /* ─────────────────────────────
     Description "read more" toggle
  ───────────────────────────── */
  (function(){
    var box=document.querySelector('.product-description-text');
    var btn=document.getElementById('mh-desc-toggle');
    if(!box||!btn) return;
    var needsToggle=box.scrollHeight>box.clientHeight+16;
    if(needsToggle) btn.hidden=false;
    btn.addEventListener('click',function(){
      var expanded=box.classList.toggle('is-expanded');
      btn.textContent=expanded?'Menej':'Zobraziť viac';
    });
  })();

  /* ─────────────────────────────
     Size Chart overlay open/close
  ───────────────────────────── */
  (function(){
    var $overlay = $('#mh-size-chart');
    function open(){ $overlay.addClass('is-open').attr('aria-hidden','false').css('display','flex'); }
    function close(){ $overlay.removeClass('is-open').attr('aria-hidden','true').css('display','none'); }
    close();

    $(document).on('click', '#mh-size-chart-btn', function(e){ e.preventDefault(); e.stopPropagation(); open(); });
    $overlay.on('click', function(e){ if (e.target === this) close(); });
    $(document).on('click', '.mh-sizechart-close', function(e){ e.preventDefault(); close(); });
    $(document).on('keydown', function(e){ if (e.key === 'Escape' && $overlay.hasClass('is-open')) close(); });
  })();

  /* ─────────────────────────────
     Add to Cart via Woo AJAX
     Robust against non-JSON responses
  ───────────────────────────── */
  $(document).on('submit','form.cart, form.variations_form.cart',function(e){
    e.preventDefault();
    e.stopImmediatePropagation();

    var $form = $(this);
    var $btn  = $('#mh-buy-button');

    // require variation ONLY if at least one size button has a data-variation-id
    var needVariation = $('.size-selector .size-btn').filter(function(){
      return $(this).data('variation-id') !== undefined;
    }).length > 0;

    if (needVariation) {
      var vid = parseInt($('#variation_id').val(), 10);
      if (!vid) {
        toast('Please select a size first.');
        return false;
      }
    }

    $btn.addClass('loading').prop('disabled', true);

    if (typeof wc_add_to_cart_params === 'undefined' || !wc_add_to_cart_params.wc_ajax_url) {
      toast('WooCommerce AJAX not available');
      $btn.removeClass('loading').prop('disabled', false);
      return false;
    }

    var ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%','add_to_cart');

    // include disabled qty in payload
    var $qty = $('#mh-quantity');
    var wasDisabled = $qty.prop('disabled');
    if (wasDisabled) $qty.prop('disabled', false);
    var payload = $form.serialize();
    if (wasDisabled) $qty.prop('disabled', true);

    var toasted = false;
    $(document.body).one('added_to_cart', function(){
      if (!toasted) { toast('✅ Added to cart!'); toasted = true; }
    });

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'text',                 // <-- ask for text, we’ll parse manually
      data: payload
    })
    .done(function(text, status, xhr){
      console.log('AJAX DONE (text):', text, 'status:', status, 'xhr:', xhr);

      // Try to parse JSON if any text present
      var resp = null;
      if (text && typeof text === 'string') {
        try { resp = JSON.parse(text); } catch(e) { /* not JSON, that’s fine */ }
      }

      // If Woo explicitly says error:true → fail
      var explicitError = resp && typeof resp === 'object' && resp.error === true;

      if (!explicitError) {
        // Fire Woo event so fragments refresh regardless of payload shape
        $(document.body).trigger('added_to_cart', [resp && resp.fragments, resp && resp.cart_hash, $btn]);
        if (!toasted) { toast('✅ Added to cart!'); toasted = true; }
      } else {
        toast('⚠️ Could not add to cart');
      }
    })
    .fail(function(xhr, status, error){
      console.error('AJAX FAIL:', status, error, xhr && xhr.responseText);
      // Even on fail, sometimes the cart changed (e.g., 200 with bad headers parsed as error).
      // If server returned 200, treat as soft success:
      if (xhr && xhr.status === 200) {
        $(document.body).trigger('added_to_cart', [null, null, $btn]);
        toast('✅ Added to cart!');
      } else {
        toast('⚠️ AJAX request failed');
      }
    })
    .always(function(){
      $btn.removeClass('loading').prop('disabled', false);
    });

    return false;
  });

});
