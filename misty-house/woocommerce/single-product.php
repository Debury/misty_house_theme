<?php
/**
 * Single Product template override (Misty House)
 *
 * - Big image = gallery image named "main" (fallback featured → other non-sizing).
 * - Thumbs = back, bonus_1, bonus_2 (filled from other non-sizing if missing).
 * - Sizing image is never in the gallery (overlay only).
 * - Row "SIZE | SIZE CHART" appears if sizing image exists OR sizes exist.
 *   "SIZE" label stays visible even for UNIVERSAL.
 * - PREORDER (attribute name contains "preorder") → stock ignored, qty max 99, label "PREORDER".
 */
defined('ABSPATH') || exit;
get_header('shop');

/* =================== HELPERS =================== */

function mh_is_sizing_media( $id ) {
  if ( ! $id ) return false;
  $post  = get_post( $id );
  $title = $post ? $post->post_title : '';
  $alt   = get_post_meta( $id, '_wp_attachment_image_alt', true );
  $file  = basename( get_attached_file( $id ) );
  $hay   = strtolower( $title . ' ' . $alt . ' ' . $file );
  foreach ( ['sizing','size-chart','size chart','tabulka-velkosti','velkost','veľkosť','velkosti'] as $n ) {
    if ( strpos( $hay, $n ) !== false ) return true;
  }
  return false;
}

function mh_detect_image_role( $id ) {
  if ( ! $id ) return 'other';
  if ( mh_is_sizing_media( $id ) ) return 'sizing';
  $post  = get_post( $id );
  $title = $post ? $post->post_title : '';
  $alt   = get_post_meta( $id, '_wp_attachment_image_alt', true );
  $file  = basename( get_attached_file( $id ) );
  $hay   = strtolower( $title . ' ' . $alt . ' ' . $file );
  $hay_n = str_replace(['-',' '],'_', $hay);
  if ( preg_match('/(^|[^a-z])main([^a-z]|$)/',  $hay) ) return 'main';
  if ( preg_match('/(^|[^a-z])back([^a-z]|$)/',  $hay) ) return 'back';
  if ( preg_match('/bonus[_]?1\b/',              $hay_n) ) return 'bonus_1';
  if ( preg_match('/bonus[_]?2\b/',              $hay_n) ) return 'bonus_2';
  return 'other';
}

/** Find sizing image id (title/alt/filename keywords) */
function mh_find_sizing_image_id( $product ) {
  foreach ( (array) $product->get_gallery_image_ids() as $id ) {
    if ( mh_is_sizing_media( $id ) ) return $id;
  }
  return 0;
}

/**
 * Detect sizes & preorder from attributes.
 * Returns:
 *  [
 *    'has_size'      => bool,
 *    'size_terms'    => array e.g. ['S','M',...] or ['UNIVERSAL']
 *    'is_universal'  => bool,
 *    'is_preorder'   => bool,
 *    'size_attr_key' => string
 *  ]
 */
function mh_detect_sizing( $product ) {
  $out = [
    'has_size'      => false,
    'size_terms'    => [],
    'is_universal'  => false,
    'is_preorder'   => false,
    'size_attr_key' => '',
  ];
  $is_boolean = static function($v){
    $v = strtolower(trim($v));
    return in_array($v, ['true','false','yes','no','1','0'], true);
  };
  $looks_like_size_value = static function($v){
    return (bool) preg_match('/^(xxxs|xxs|xs|s|m|l|xl|xxl|xxxl|osfa|one\s*size|onesize|universal)$/i', trim($v));
  };

  foreach ( $product->get_attributes() as $key => $attr ) {
    $label = strtolower( wc_attribute_label( $key ) );
    $k     = strtolower( $key );

    // PREORDER if attribute name/label contains "preorder"
    if ( strpos($label,'preorder') !== false || strpos($k,'preorder') !== false ) {
      $out['is_preorder'] = true;
    }

    // Is this a size attribute?
    $maybe_size =
      strpos($label,'size')     !== false ||
      strpos($label,'velkost')  !== false ||
      strpos($label,'veľkosť')  !== false ||
      strpos($k,'pa_size')      !== false ||
      $k === 'size';

    if ( ! $maybe_size ) continue;

    // Read values
    if ( $attr->is_taxonomy() ) {
      $terms = wc_get_product_terms( $product->get_id(), $key, ['fields'=>'names'] );
    } else {
      $terms = $attr->get_options();
    }
    $terms = array_values(array_filter(array_map('trim', array_map('wp_strip_all_tags', (array)$terms))));

    // Ignore pure boolean flags
    if ( count($terms) === 1 && $is_boolean($terms[0]) ) continue;

    // UNIVERSAL
    if ( count($terms) === 1 && preg_match('/^universal$/i', $terms[0]) ) {
      $out['has_size']      = true;
      $out['is_universal']  = true;
      $out['size_terms']    = ['UNIVERSAL'];
      $out['size_attr_key'] = $key;
      continue;
    }

    // Classic sizes
    $valids = array_filter($terms, $looks_like_size_value);
    if ( count($terms) >= 2 || count($valids) === count($terms) ) {
      $out['has_size']      = true;
      $out['size_attr_key'] = $key;
      $out['size_terms']    = array_map('strtoupper', $terms);
      sort($out['size_terms']);
    }
  }
  return $out;
}
?>

<div class="custom-single-product">
  <?php while ( have_posts() ) : the_post(); global $product; ?>
    <?php
      $sizing     = mh_detect_sizing( $product );
      $sizing_img = mh_find_sizing_image_id( $product );
      $is_var     = $product->is_type('variable');
      $is_pre     = $sizing['is_preorder'];

      // --- Build gallery roles (exclude sizing) ---
      $gallery_ids = (array) $product->get_gallery_image_ids();
      $role_map = ['main'=>0,'back'=>0,'bonus_1'=>0,'bonus_2'=>0];
      $others   = [];
      foreach ( $gallery_ids as $gid ) {
        $role = mh_detect_image_role( $gid );
        if ( $role === 'sizing' ) continue;
        if ( isset($role_map[$role]) && $role_map[$role] === 0 ) $role_map[$role] = $gid;
        else $others[] = $gid;
      }

      // Pick main: prefer role 'main', then featured (if non-sizing), else any other non-sizing
      $featured_id = $product->get_image_id();
      $main_id = 0;
      if ( $role_map['main'] ) {
        $main_id = $role_map['main'];
      } elseif ( $featured_id && ! mh_is_sizing_media($featured_id) ) {
        $main_id = $featured_id;
      } else {
        if ( $role_map['back'] )        { $main_id = $role_map['back'];        $role_map['back'] = 0; }
        elseif ( $role_map['bonus_1'] ) { $main_id = $role_map['bonus_1'];     $role_map['bonus_1'] = 0; }
        elseif ( $role_map['bonus_2'] ) { $main_id = $role_map['bonus_2'];     $role_map['bonus_2'] = 0; }
        elseif ( !empty($others) )      { $main_id = array_shift($others); }
      }

      // Fill thumbs (back, bonus_1, bonus_2), supplementing from $others
      $thumb_ids = [];
      foreach ( ['back','bonus_1','bonus_2'] as $slot ) {
        if ( $role_map[$slot] && $role_map[$slot] !== $main_id ) {
          $thumb_ids[] = $role_map[$slot];
        } else {
          while ( !empty($others) && $others[0] === $main_id ) array_shift($others);
          if ( !empty($others) ) $thumb_ids[] = array_shift($others);
        }
      }

      $main_large_url = $main_id ? wp_get_attachment_image_url($main_id,'large')     : wc_placeholder_img_src();
      $main_thumb_url = $main_id ? wp_get_attachment_image_url($main_id,'thumbnail') : wc_placeholder_img_src();
      $main_alt       = $main_id ? (get_post_meta($main_id,'_wp_attachment_image_alt',true) ?: get_the_title()) : get_the_title();

      // Do we require selecting a size before buying?
      $requires_selection = ( $sizing['has_size'] && ! $sizing['is_universal'] );

      // For UNIVERSAL on variable: pre-resolve its variation id
      $universal_var_id = 0;
      $universal_stock  = 0;
      if ( $sizing['is_universal'] && $is_var ) {
        foreach ( $product->get_children() as $child_id ) {
          $v = wc_get_product($child_id); if ( ! $v ) continue;
          foreach ( $v->get_attributes() as $k => $val ) {
            $k_l = strtolower($k);
            if ( strpos($k_l,'size')!==false || strpos($k_l,'velkost')!==false || strpos($k_l,'veľkosť')!==false ) {
              if ( strtoupper($val) === 'UNIVERSAL' ) {
                $universal_var_id = $child_id;
                $universal_stock  = max(0,(int)$v->get_stock_quantity());
                break 2;
              }
            }
          }
        }
      }

      // Quantity limits
      $qty_min = 1;
      if ( $is_pre ) {
        $qty_max = 99;
      } else {
        if ( $sizing['is_universal'] ) {
          // If universal variation exists, use its stock; otherwise use product stock
          $qty_max = $is_var && $universal_var_id ? max(1, (int)$universal_stock) : max(1, (int)$product->get_stock_quantity());
        } else {
          // If a size is required, we'll let JS set the max after a click; start with 1
          $qty_max = $requires_selection ? 1 : max(1, (int)$product->get_stock_quantity());
        }
      }
    ?>

    <div class="product-main">
      <!-- LEFT: images -->
      <div class="product-image-section">
        <div class="main-product-image">
          <img id="mh-main-img"
               src="<?php echo esc_url($main_large_url); ?>"
               data-full="<?php echo esc_url($main_large_url); ?>"
               data-thumb="<?php echo esc_url($main_thumb_url); ?>"
               data-role="main"
               alt="<?php echo esc_attr($main_alt); ?>" />
        </div>
        <div class="product-gallery">
          <?php
            $thumb_roles = ['back','bonus_1','bonus_2'];
            foreach ( $thumb_ids as $i => $tid ) {
              if ( ! $tid ) continue;
              printf(
                '<img class="mh-thumb" src="%1$s" data-full="%2$s" data-thumb="%1$s" data-role="%3$s" alt="%4$s" />',
                esc_url( wp_get_attachment_image_url($tid,'thumbnail') ),
                esc_url( wp_get_attachment_image_url($tid,'large') ),
                esc_attr( $thumb_roles[$i] ),
                esc_attr( get_post_meta($tid,'_wp_attachment_image_alt',true) )
              );
            }
          ?>
        </div>
      </div>

      <!-- RIGHT: details -->
      <div class="product-details">
        <div class="product-info-top">
          <h1 class="product-series-title"><?php echo esc_html($product->get_name()); ?></h1>
          <p class="product-handle">
            @<?php
              $cats = get_the_terms($product->get_id(),'product_cat');
              echo ($cats && !is_wp_error($cats)) ? esc_html($cats[0]->name) : 'Horebass';
            ?>
          </p>


          <?php
          $d_raw = $product->get_description() ?: $product->get_short_description();
          $has_desc = trim( wp_strip_all_tags( (string) $d_raw ) ) !== '';
          if ( $has_desc ) : ?>
            <div class="product-description-text" id="mh-desc-box">
              <?php echo wpautop( wp_kses_post( $d_raw ) ); ?>
            </div>
            <button class="desc-toggle" id="mh-desc-toggle" hidden>Zobraziť viac</button>
          <?php endif; ?>
          <button class="desc-toggle" id="mh-desc-toggle" hidden>Zobraziť viac</button>

          <div class="price-section">
            <div class="main-price"><?php echo $product->get_price_html(); ?></div>
            <div class="stock-status" id="mh-stock-status">
              <?php
                if ( $is_pre ) {
                  echo '<span class="preorder">PREORDER</span>'; // styled white in your CSS
                } else {
                  echo $product->is_in_stock() ? 'IN STOCK' : '<span class="out-of-stock">OUT OF STOCK</span>';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="product-actions">
          <form class="variations_form cart" method="post" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>">

            <?php if ( $sizing_img || $sizing['has_size'] ) : ?>
              <div class="size-row">
                <?php if ( $sizing['has_size'] ) : ?>
                  <div class="size-label">SIZE</div>
                <?php endif; ?>
                <?php if ( $sizing_img ) : ?>
                  <button type="button" class="size-chart-trigger" id="mh-size-chart-btn" aria-haspopup="dialog" aria-controls="mh-size-chart">SIZE CHART</button>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <div class="size-selector" id="mh-size-selector">
              <?php
                if ( $sizing['has_size'] ) {
                  if ( $sizing['is_universal'] ) {
                    // Render UNIVERSAL as selected + prefill hidden fields if we have a variation id
                    $stock_display = $is_pre ? 99 : ( $is_var && $universal_var_id ? $universal_stock : $product->get_stock_quantity() );
                    printf(
                      '<button type="button" class="size-btn selected" data-variation-id="%1$d" data-stock="%2$d">UNIVERSAL</button>',
                      absint($universal_var_id), absint(max(0,(int)$stock_display))
                    );
                  } else {
                    // Classic sizes
                    $avail = [];
                    if ( $is_var ) {
                      foreach ( $product->get_children() as $child_id ) {
                        $v = wc_get_product($child_id); if ( ! $v ) continue;
                        foreach ( $v->get_attributes() as $k => $val ) {
                          $k_l = strtolower($k);
                          if ( strpos($k_l,'size')!==false || strpos($k_l,'velkost')!==false || strpos($k_l,'veľkosť')!==false ) {
                            $sz = strtoupper($val);
                            $avail[$sz] = [
                              'id'    => $child_id,
                              'stock' => max(0,(int)$v->get_stock_quantity()),
                              'ok'    => $v->is_in_stock(),
                            ];
                            break;
                          }
                        }
                      }
                    }
                    foreach ( $sizing['size_terms'] as $s ) {
                      if ( $is_var ) {
                        $ok   = !empty($avail[$s]) && $avail[$s]['ok'];
                        $cls  = $ok ? 'size-btn' : 'size-btn disabled';
                        $attr = $ok ? '' : 'disabled';
                        $v_id = $avail[$s]['id'] ?? 0;
                        $stk  = $is_pre ? 99 : ($avail[$s]['stock'] ?? 0);
                        printf(
                          '<button type="button" class="%1$s" data-variation-id="%2$d" data-stock="%3$d" %4$s>%5$s</button>',
                          esc_attr($cls), absint($v_id), absint($stk), $attr, esc_html($s)
                        );
                      } else {
                        // Simple product with size terms (rare), treat like one stock
                        $stock = $is_pre ? 99 : max(0,(int)$product->get_stock_quantity());
                        $ok    = $is_pre ? true : $product->is_in_stock();
                        $cls   = $ok ? 'size-btn' : 'size-btn disabled';
                        $attr  = $ok ? '' : 'disabled';
                        printf(
                          '<button type="button" class="%1$s" data-stock="%2$d" %3$s>%4$s</button>',
                          esc_attr($cls), absint($stock), $attr, esc_html($s)
                        );
                      }
                    }
                  }
                }
              ?>
              <input type="hidden" name="selected_size" id="selected_size" value="<?php echo $sizing['is_universal'] ? 'UNIVERSAL' : ''; ?>">
              <input type="hidden" name="variation_id" id="variation_id" value="<?php echo $sizing['is_universal'] ? absint($universal_var_id) : ''; ?>">
            </div>

            <div class="quantity-selector">
              <label for="mh-quantity">Quantity:</label>
              <input type="number"
                     name="quantity"
                     id="mh-quantity"
                     value="<?php echo esc_attr($qty_min); ?>"
                     min="<?php echo esc_attr($qty_min); ?>"
                     max="<?php echo esc_attr($qty_max); ?>"
                     <?php echo $requires_selection ? 'disabled' : ''; ?>>
            </div>

            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">

            <button type="submit"
                    class="buy-button"
                    id="mh-buy-button"
                    data-requires-selection="<?php echo $requires_selection ? '1' : '0'; ?>"
                    data-preorder="<?php echo $is_pre ? '1' : '0'; ?>"
                    <?php echo $requires_selection ? 'disabled' : ''; ?>>
              <?php echo $is_pre ? 'PREORDER NOW!!!' : 'BUY THIS SHIT!!!'; ?>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- SIZE CHART OVERLAY -->
    <div id="mh-size-chart" class="mh-sizechart-overlay" aria-hidden="true" role="dialog" aria-label="Size chart" style="display:none">
      <div class="mh-sizechart-dialog" role="document">
        <button type="button" class="mh-sizechart-close" aria-label="Close">&times;</button>
        <div class="mh-sizechart-content">
          <?php
            if ( $sizing_img ) {
              echo wp_get_attachment_image( $sizing_img, 'large', false, [ 'alt' => 'Size chart' ] );
            } else {
              echo '<p>No size chart image found.</p>';
            }
          ?>
        </div>
      </div>
    </div>

  <?php endwhile; ?>

  <div class="featured-section"><?php get_template_part('template-parts/featured-products'); ?></div>
  <div class="social-mosaic-section"><?php get_template_part('template-parts/social'); ?></div>
</div>

<?php get_footer('shop'); ?>

