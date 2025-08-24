jQuery(function($){
  function toast(msg){
    var $t=$('<div class="mh-toast"></div>').text(msg).css({position:'fixed',right:'20px',bottom:'20px',background:'#000',color:'#fff',border:'2px solid gold',padding:'10px 14px',borderRadius:'10px',opacity:0,transition:'opacity .25s',zIndex:9999,boxShadow:'0 0 12px gold'});
    $('body').append($t);requestAnimationFrame(()=>{$t.css('opacity',1)});setTimeout(()=>{$t.css('opacity',0);setTimeout(()=>{$t.remove()},250)},3000);
  }
  $(document).on('click','.mh-thumb',function(){
    var $thumb=$(this),$main=$('#mh-main-img');var thumbSrc=$thumb.attr('src'),thumbFull=$thumb.data('full'),thumbRole=$thumb.data('role');var mainSrc=$main.attr('src'),mainFull=$main.data('full'),mainThumb=$main.data('thumb'),mainRole=$main.data('role');$main.attr('src',thumbFull).data('full',thumbFull).data('thumb',thumbSrc).data('role',thumbRole).removeAttr('srcset');$thumb.attr('src',mainThumb).data('full',mainFull).data('thumb',mainThumb).data('role',mainRole);
  });
  function toggleBuy(){var ok=$('.size-btn.selected').length>0&&!$('.size-btn.selected').hasClass('disabled');$('#mh-buy-button').prop('disabled',!ok);if(ok)$('#mh-quantity').prop('disabled',false)}
  $(document).on('click','.size-selector .size-btn:not(.disabled)',function(){
    $('.size-btn').removeClass('selected');$(this).addClass('selected');$('#selected_size').val($(this).text().trim());$('#variation_id').val($(this).data('variation-id')||'');toggleBuy();
  });
  toggleBuy();
  (function(){var box=document.querySelector('.product-description-text');var btn=document.getElementById('mh-desc-toggle');if(!box||!btn)return;var needsToggle=box.scrollHeight>box.clientHeight+16;if(needsToggle)btn.hidden=false;btn.addEventListener('click',function(){var expanded=box.classList.toggle('is-expanded');btn.textContent=expanded?'Menej':'Zobraziť viac'})})();
  $(document).on('submit','form.cart, form.variations_form.cart',function(e){
    e.preventDefault();e.stopImmediatePropagation();
    var $form=$(this);var $btn=$('#mh-buy-button');
    if($('#variation_id').length&&!$('#variation_id').val()){toast('Please select a size first.');return false}
    $btn.addClass('loading').prop('disabled',true);
    if(typeof wc_add_to_cart_params==='undefined'||!wc_add_to_cart_params.wc_ajax_url){toast('WooCommerce AJAX not available');$btn.removeClass('loading').prop('disabled',false);return false}
    var ajaxUrl=wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%','add_to_cart');
    var $qty=$('#mh-quantity');var wasDisabled=$qty.prop('disabled');if(wasDisabled)$qty.prop('disabled',false);var payload=$form.serialize();if(wasDisabled)$qty.prop('disabled',true);
    $.ajax({url:ajaxUrl,type:'POST',dataType:'json',data:payload})
      .done(function(resp){if(resp&&!resp.error){$(document.body).trigger('added_to_cart',[resp.fragments,resp.cart_hash,$btn]);toast('✅ Added to cart!')}else{toast('⚠️ Could not add to cart')}})
      .fail(function(){toast('⚠️ AJAX request failed')})
      .always(function(){$btn.removeClass('loading').prop('disabled',false)});
    return false;
  });
});
