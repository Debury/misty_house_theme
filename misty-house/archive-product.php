<?php
/**
 * Template Name: Custom Shop Page
 * Description: A 4-column, fixed-height image grid with excerpts under titles and JS filter + pagination hooks.
 */
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
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
        // skip "uncategorized"
        if ( 'uncategorized' === $cat->slug ) {
          continue;
        }
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
        while ( have_posts() ) {
          the_post();
          global $product;

          // collect category slugs (minus uncategorized)
          $terms = get_the_terms( get_the_ID(), 'product_cat' ) ?: [];
          $slugs = wp_list_pluck( $terms, 'slug' );
          $slugs = array_filter( $slugs, function( $s ) { return 'uncategorized' !== $s; } );
          $slugs = array_merge( [ 'all' ], $slugs );
          $data_cat = implode( ' ', $slugs );
        ?>
          <div class="product-card" data-category="<?php echo esc_attr( $data_cat ); ?>">

            <div class="product-image">
              <a href="<?php the_permalink(); ?>">
                <?php
                $gallery = $product->get_gallery_image_ids();
                if ( ! empty( $gallery ) ) {
                  echo wp_get_attachment_image( array_shift( $gallery ), 'medium' );
                } elseif ( has_post_thumbnail() ) {
                  the_post_thumbnail( 'medium' );
                } else {
                  echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                }
                ?>
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
                // Strip HTML and limit to 80 characters
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
        <?php
        }
        ?>
      </div>
    <?php else : ?>
      <p class="woocommerce-info"><?php esc_html_e( 'No products found', 'misty-house' ); ?></p>
    <?php endif; ?>
  </div>

  <!-- WordPress Native Pagination -->
  <div class="shop-pagination">
    <?php
    global $wp_query;
    $total_pages = $wp_query->max_num_pages;
    $current_page = max(1, get_query_var('paged'));

    if ( $total_pages > 1 ) :
    ?>
      <button class="pagination-arrow prev" <?php echo $current_page <= 1 ? 'disabled' : ''; ?>
              data-page="<?php echo max(1, $current_page - 1); ?>">
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
              data-page="<?php echo min($total_pages, $current_page + 1); ?>">
        →
      </button>
    <?php endif; ?>
  </div>

</div>

<?php get_footer( 'shop' ); ?>
