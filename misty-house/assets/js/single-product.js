jQuery(function($){
  let selectedVar  = null;
  let availableMax = 0;

  // helper to toggle controls
  function refreshControls(){
    const $qty = $('#mh-quantity');
    const $btn = $('#mh-buy-button');

    if (!selectedVar) {
      $qty.prop('disabled', true);
      $btn.prop('disabled', true);
      return;
    }

    // compute how many are already in cart
    const inCart   = MH_CART.qty[selectedVar] || 0;
    const stock    = availableMax;
    const maxAllow = Math.max(0, stock - inCart);

    // update stock label
    $('#mh-stock-status').text( maxAllow > 0 ? 'IN STOCK' : 'OUT OF STOCK' );

    // update quantity picker
    $qty.attr('max', maxAllow)
        .prop('disabled', maxAllow <= 0);
    if ( parseInt($qty.val(),10) > maxAllow ) {
      $qty.val( maxAllow || 1 );
    }

    // enable buy button only if qty ≥ 1 and ≤ maxAllow
    const qv = parseInt($qty.val(),10) || 0;
    $btn.prop('disabled', !(qv >= 1 && qv <= maxAllow));
  }

  // size button click
  $('.size-selector').on('click','button.size-btn:not(.disabled)', function(){
    const $b = $(this);
    $('.size-btn').removeClass('selected');
    $b.addClass('selected');

    selectedVar  = $b.data('variation-id') ? String($b.data('variation-id')) : String($b.data('stock') ? $('#mh-quantity').closest('form').find('input[name="add-to-cart"]').val() : '');
    availableMax = parseInt( $b.data('stock'), 10 ) || 0;

    // store into hidden fields
    $('#selected_size').val( $b.text().trim() );
    $('#variation_id').val( $b.data('variation-id') || '' );

    refreshControls();
  });

  // quantity change
  $('#mh-quantity').on('input change', function(){
    const $this = $(this);
    let v = parseInt($this.val(),10) || 1;
    const mx = parseInt($this.attr('max'),10) || 1;
    if ( v < 1 ) v = 1;
    if ( v > mx )  v = mx;
    $this.val(v);
    refreshControls();
  });

  // AJAX add to cart
  $('form.cart, form.variations_form.cart').on('submit', function(e){
    e.preventDefault();
    const $f  = $(this);
    const qty = parseInt($('#mh-quantity').val(),10) || 1;

    // build data
    let data = $f.serialize();
    data += '&quantity=' + qty +
            '&' + $.param({ 'add-to-cart': $f.find('input[name="add-to-cart"]').val() });

    $('#mh-buy-button').addClass('loading');

    $.post(
      wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%','add_to_cart'),
      data,
      function(resp){
        $(document.body).trigger('added_to_cart',[ resp.fragments, resp.cart_hash, $f ]);
        $('#mh-buy-button').removeClass('loading');
      }
    );
  });

  // toast
  $(document.body).on('added_to_cart', function(){
    const $t = $('<div class="mh-toast">✅ Added to cart!</div>');
    $('body').append($t);
    setTimeout(()=> $t.css('opacity',1),10);
    setTimeout(()=>{
      $t.css('opacity',0);
      setTimeout(()=> $t.remove(),300);
    }, 3000);
  });



  // init
  refreshControls();
});
