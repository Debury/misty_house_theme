<?php
/**
 * Single Product template override
 * @package Misty_House
 */

defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>

<div class="custom-single-product">
  <?php while ( have_posts() ) : the_post(); global $product; ?>
    <div class="product-main">

      <!-- LEFT: Images -->
      <div class="product-image-section">

        <!-- MAIN IMAGE -->
        <div class="main-product-image">
          <?php
            // Grab gallery IDs or fallback
            $gallery = $product->get_gallery_image_ids();
            if ( empty( $gallery ) ) {
              $gallery[] = $product->get_image_id() ?: 0;
            }

            // Pop off main
            $main_id = array_shift( $gallery );
            $main_large_url     = wp_get_attachment_image_url( $main_id, 'large' );
            $main_thumb_url     = wp_get_attachment_image_url( $main_id, 'thumbnail' );
            echo '<img '
              . 'id="mh-main-img" '
              . 'src="' . esc_url( $main_large_url ) . '" '
              . 'data-full="'  . esc_url( $main_large_url ) . '" '
              . 'data-thumb="' . esc_url( $main_thumb_url ) . '" '
              . 'data-role="main" '
              . 'alt="'       . esc_attr( get_post_meta( $main_id, '_wp_attachment_image_alt', true ) ) . '" '
              . '/>';
          ?>
        </div>

        <!-- THUMBNAILS -->
        <div class="product-gallery">
          <?php
            $roles = [ 'back', 'bonus_1', 'bonus_2' ];
            foreach ( $gallery as $i => $id ) {
              if ( $i >= 3 ) break;
              $large = wp_get_attachment_image_url( $id, 'large' );
              $thumb = wp_get_attachment_image_url( $id, 'thumbnail' );
              printf(
                '<img class="mh-thumb" src="%1$s" data-full="%2$s" data-thumb="%1$s" data-role="%3$s" alt="%4$s" />',
                esc_url( $thumb ),
                esc_url( $large ),
                esc_attr( $roles[ $i ] ),
                esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) )
              );
            }
          ?>
        </div>

      </div>

      <!-- RIGHT: Details / Purchase -->
      <div class="product-details">
        <div class="product-info-top">
          <h1 class="product-series-title"><?php echo esc_html( $product->get_name() ); ?></h1>
          <p class="product-handle">
            @<?php
              $cats = get_the_terms( $product->get_id(), 'product_cat' );
              echo ( $cats && ! is_wp_error( $cats ) )
                ? esc_html( $cats[0]->name )
                : 'Horebass';
            ?>
          </p>

          <div class="product-description-text">
            <?php
              $d = $product->get_description() ?: $product->get_short_description();
              echo wpautop( wp_kses_post( $d ?: 'No description.' ) );
            ?>
          </div>

          <button class="desc-toggle" id="mh-desc-toggle" hidden>Zobraziť viac</button>

          <div class="price-section">
            <div class="main-price"><?php echo $product->get_price_html(); ?></div>
            <div class="stock-status" id="mh-stock-status">
              <?php echo $product->is_in_stock() ? 'IN STOCK' : '<span class="out-of-stock">OUT OF STOCK</span>'; ?>
            </div>
          </div>
        </div>

        <div class="product-actions">
          <form class="variations_form cart" method="post">
            <div class="size-selector">
              <?php
                $sizes = [ 'S','M','L','XL','XXL' ];
                $avail = [];
                if ( $product->is_type( 'variable' ) ) {
                  foreach ( $product->get_children() as $v_id ) {
                    $v = wc_get_product( $v_id );
                    foreach ( $v->get_attributes() as $k => $val ) {
                      if ( false !== stripos( $k, 'size' ) ) {
                        $sz = strtoupper( $val );
                        $avail[ $sz ] = [
                          'id'    => $v_id,
                          'stock' => max( 0, $v->get_stock_quantity() ),
                          'ok'    => $v->is_in_stock(),
                        ];
                        break;
                      }
                    }
                  }
                }
                foreach ( $sizes as $s ) {
                  if ( $product->is_type( 'variable' ) ) {
                    $ok   = ! empty( $avail[ $s ] ) && $avail[ $s ]['ok'];
                    $cls  = $ok ? 'size-btn' : 'size-btn disabled';
                    $attr = $ok ? '' : 'disabled';
                    $v_id = $avail[ $s ]['id'] ?? 0;
                    $stk  = $avail[ $s ]['stock'] ?? 0;
                    printf(
                      '<button type="button" class="%1$s" data-variation-id="%2$d" data-stock="%3$d" %4$s>%5$s</button>',
                      esc_attr( $cls ), absint( $v_id ), absint( $stk ), $attr, esc_html( $s )
                    );
                  } else {
                    $stock = max( 0, $product->get_stock_quantity() );
                    $ok    = $product->is_in_stock();
                    $cls   = $ok ? 'size-btn selected' : 'size-btn disabled';
                    $attr  = $ok ? '' : 'disabled';
                    printf(
                      '<button type="button" class="%1$s" data-stock="%2$d" %3$s>%4$s</button>',
                      esc_attr( $cls ), absint( $stock ), $attr, esc_html( $s )
                    );
                    break;
                  }
                }
              ?>
              <input type="hidden" name="selected_size" id="selected_size" required>
              <input type="hidden" name="variation_id"  id="variation_id">
            </div>

            <div class="quantity-selector">
              <label for="mh-quantity">Quantity:</label>
              <input type="number" name="quantity" id="mh-quantity" value="1" min="1" disabled>
            </div>

            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">

            <button type="submit" class="buy-button" id="mh-buy-button" disabled>
              BUY THIS SHIT!!!
            </button>
          </form>
        </div>
      </div>

    </div>
  <?php endwhile; ?>

  <div class="featured-section"><?php get_template_part( 'template-parts/featured-products' ); ?></div>
  <div class="social-mosaic-section"><?php get_template_part( 'template-parts/social' ); ?></div>
</div>



<?php get_footer( 'shop' ); ?>

<script>
jQuery(function($){

  // --- toast helper
  function toast(msg){
    var $t = $('<div class="mh-toast"></div>').text(msg).css({
      position:'fixed', right:'20px', bottom:'20px',
      background:'#000', color:'#fff', border:'2px solid gold',
      padding:'10px 14px', borderRadius:'10px',
      opacity:0, transition:'opacity .25s', zIndex:9999,
      boxShadow:'0 0 12px gold'
    });
    $('body').append($t);
    requestAnimationFrame(()=> $t.css('opacity',1));
    setTimeout(()=>{ $t.css('opacity',0); setTimeout(()=> $t.remove(),250); },3000);
  }

  // Intercept add-to-cart
  $(document).on('submit', 'form.cart, form.variations_form.cart', function(e){
    e.preventDefault();
    e.stopImmediatePropagation(); // 🔑 prevents other handlers firing

    var $form = $(this);
    var $btn  = $('#mh-buy-button');

    // require variation if present
    if ($('#variation_id').length && !$('#variation_id').val()){
      toast('Please select a size first.');
      return false;
    }

    $btn.addClass('loading').prop('disabled', true);

    // Build AJAX URL safely
    if (typeof wc_add_to_cart_params === 'undefined' || !wc_add_to_cart_params.wc_ajax_url){
      toast('WooCommerce AJAX not available');
      $btn.removeClass('loading').prop('disabled', false);
      return false;
    }
    var ajaxUrl = wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%','add_to_cart');

    // Serialize including qty (make sure it isn’t disabled)
    var $qty = $('#mh-quantity');
    var wasDisabled = $qty.prop('disabled');
    if (wasDisabled) $qty.prop('disabled', false);
    var payload = $form.serialize();
    if (wasDisabled) $qty.prop('disabled', true);

    $.ajax({
      url: ajaxUrl,
      type: 'POST',
      dataType: 'json',
      data: payload
    }).done(function(resp){
      if (resp && !resp.error){
        $(document.body).trigger('added_to_cart',[resp.fragments, resp.cart_hash, $btn]);
        toast('✅ Added to cart!');
      } else {
        toast('⚠️ Could not add to cart');
        console.warn('Woo response error:', resp);
      }
    }).fail(function(xhr){
      toast('⚠️ AJAX request failed');
      console.error('AJAX add_to_cart failed:', xhr);
    }).always(function(){
      $btn.removeClass('loading').prop('disabled', false);
    });

    return false;
  });

});
</script>
