<?php
/**
 * Template Name: Custom Shop Page
 * Description: A 4-column, fixed-height image grid with excerpts under titles and JS filter + pagination hooks.
 */
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );

/* ─────────────────────────────────────────────
   Helpers to pick only the allowed card image
   - Prefer gallery image named "main"
   - Otherwise use featured image
   - Never use sizing images
────────────────────────────────────────────── */
if ( ! function_exists('mh_is_sizing_media') ) {
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
}

if ( ! function_exists('mh_detect_image_role') ) {
  function mh_detect_image_role( $id ) {
    if ( ! $id ) return 'other';
    if ( mh_is_sizing_media( $id ) ) return 'sizing';
    $post  = get_post( $id );
    $title = $post ? $post->post_title : '';
    $alt   = get_post_meta( $id, '_wp_attachment_image_alt', true );
    $file  = basename( get_attached_file( $id ) );
    $hay   = strtolower( $title . ' ' . $alt . ' ' . $file );
    if ( preg_match('/(^|[^a-z])main([^a-z]|$)/', $hay) ) return 'main';
    return 'other';
  }
}
?>

<div class="shop-page-wrapper">

  <!-- Shop Header -->
  <div class="shop-header">
    <h1 class="shop-title"><?php woocommerce_page_title(); ?></h1>
    <div class="shop-description">
      <?php if ( $desc = get_the_content() ) echo wpautop( $desc ); ?>
    </div>
  </div>

  <!-- Filter Buttons -->
  <div class="shop-filters">
    <button class="filter-btn active" data-filter="all">
      <?php esc_html_e( 'All', 'misty-house' ); ?>
    </button>
    <?php
    $cats = get_terms( [
      'taxonomy'   => 'product_cat',
      'hide_empty' => true,
      'parent'     => 0,
    ] );

    if ( ! is_wp_error( $cats ) && $cats ) {
      foreach ( $cats as $cat ) {
        if ( 'uncategorized' === $cat->slug ) continue; // keep "uncategorized" out of filters
        printf(
          '<button class="filter-btn" data-filter="%1$s">%2$s</button>',
          esc_attr( $cat->slug ),
          esc_html( $cat->name )
        );
      }
    }
    ?>
  </div>

  <!-- Products Grid -->
  <div class="products-container">
    <?php if ( woocommerce_product_loop() ) : ?>
      <div class="products-grid">
        <?php
        while ( have_posts() ) :
          the_post();
          global $product;

          // terms/slugs for filtering
          $terms = get_the_terms( get_the_ID(), 'product_cat' ) ?: [];
          $slugs = wp_list_pluck( $terms, 'slug' );
          $slugs = array_filter( $slugs, function( $s ) { return 'uncategorized' !== $s; } );
          $slugs = array_merge( [ 'all' ], $slugs );
          $data_cat = implode( ' ', $slugs );

          // ---------- Badge generation ----------
          $make_badge = static function( $name ) {
            $raw  = ltrim( trim( $name ), '@' );
            $caps = preg_replace( '/[^A-Z]/', '', $raw );
            if ( $caps === '' ) {
              $parts = preg_split( '/[\s\-_\.]+/', $raw, -1, PREG_SPLIT_NO_EMPTY );
              $caps  = '';
              foreach ( $parts as $p ) {
                $caps .= function_exists('mb_substr') ? mb_substr( $p, 0, 1 ) : substr( $p, 0, 1 );
              }
              $caps = strtoupper( $caps );
            }
            return substr( $caps, 0, 4 );
          };

          $badge_text = '';
          if ( ! empty( $terms ) ) {
            $preferred = null;
            foreach ( $terms as $t ) {
              if ( isset($t->name[0]) && $t->name[0] === '@' ) { $preferred = $t; break; }
            }
            if ( ! $preferred ) {
              foreach ( $terms as $t ) {
                if ( 'uncategorized' !== $t->slug ) { $preferred = $t; break; }
              }
            }
            $badge_text = $preferred ? $make_badge( $preferred->name ) : 'MH';
          } else {
            $badge_text = 'MH';
          }
          // --------------------------------------

          // ---------- Strict image choice ----------
          // 1) Look for gallery image whose title/alt/filename contains "main" (and is not sizing)
          $main_id = 0;
          $gallery = (array) $product->get_gallery_image_ids();
          foreach ( $gallery as $gid ) {
            $role = mh_detect_image_role( $gid );
            if ( $role === 'main' ) { $main_id = $gid; break; }
          }
          // 2) If not found, fall back to featured image
          if ( ! $main_id ) {
            $featured = $product->get_image_id();
            if ( $featured && ! mh_is_sizing_media( $featured ) ) {
              $main_id = $featured;
            }
          }
          // 3) Absolute fallback: placeholder
          $img_html = '';
          if ( $main_id ) {
            // use a single, consistent size for the grid (e.g. 'medium')
            $img_html = wp_get_attachment_image( $main_id, 'medium', false, [
              'alt' => get_post_meta( $main_id, '_wp_attachment_image_alt', true ) ?: get_the_title()
            ] );
          } else {
            $img_html = '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( get_the_title() ) . '">';
          }
          // ----------------------------------------
        ?>
          <div class="product-card" data-category="<?php echo esc_attr( $data_cat ); ?>">

            <div class="product-image">
              <?php if ( $badge_text ) : ?>
                <span class="mh-pin mh-pin--gold" aria-label="category badge"><?php echo esc_html( $badge_text ); ?></span>
              <?php endif; ?>

              <a href="<?php the_permalink(); ?>">
                <?php echo $img_html; ?>
              </a>
            </div>

            <div class="product-info">
              <h3 class="product-name">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>

              <?php
              $description = '';
              if ( $product->get_short_description() ) {
                $description = $product->get_short_description();
              } elseif ( get_the_excerpt() ) {
                $description = get_the_excerpt();
              }

              if ( $description ) {
                $description = wp_strip_all_tags( $description );
                if ( strlen( $description ) > 80 ) {
                  $description = substr( $description, 0, 80 ) . '...';
                }
                echo '<p class="product-description">' . esc_html( $description ) . '</p>';
              }
              ?>

              <div class="product-meta">
                <span class="product-price"><?php echo $product->get_price_html(); ?></span>
              </div>
            </div>

          </div>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p class="woocommerce-info"><?php esc_html_e( 'No products found', 'misty-house' ); ?></p>
    <?php endif; ?>
  </div>

  <!-- WordPress Native Pagination -->
  <div class="shop-pagination">
    <?php
    global $wp_query;
    $total_pages  = $wp_query->max_num_pages;
    $current_page = max( 1, get_query_var( 'paged' ) );

    if ( $total_pages > 1 ) :
    ?>
      <button class="pagination-arrow prev" <?php echo $current_page <= 1 ? 'disabled' : ''; ?>
              data-page="<?php echo max( 1, $current_page - 1 ); ?>">
        ←
      </button>

      <div class="pagination-dots">
        <?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
          <button class="dot <?php echo $i === $current_page ? 'active' : ''; ?>"
                  data-page="<?php echo $i; ?>">
            <?php echo $i; ?>
          </button>
        <?php endfor; ?>
      </div>

      <button class="pagination-arrow next" <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>
              data-page="<?php echo min( $total_pages, $current_page + 1 ); ?>">
        →
      </button>
    <?php endif; ?>
  </div>

</div>

<?php get_footer( 'shop' ); ?>
