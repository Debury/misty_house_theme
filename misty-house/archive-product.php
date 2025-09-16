<?php
/**
 * Template Name: Custom Shop Page
 * Description: A 4-column, fixed-height image grid with excerpts under titles and JS filter + pagination hooks.
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/* ─────────────────────────────────────────────
   Helpers to pick only the allowed card image
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

/* ─────────────────────────────────────────────
   Read filter/pagination from URL (server-side)
────────────────────────────────────────────── */
$cat_slug     = isset($_GET['cat']) ? sanitize_title( wp_unslash($_GET['cat']) ) : '';
$current_page = max( 1, get_query_var('paged') ? (int) get_query_var('paged') : (int) ( $_GET['paged'] ?? 1 ) );

/* Per-page – nech sa zhoduje so shopom */
$per_page = (int) apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page', 12 ) );
if ( $per_page <= 0 ) $per_page = 12;

/* Základné argumenty ako na shope */
$ordering_args = wc()->query->get_catalog_ordering_args(); // rešpektuje triedenie
$meta_query    = WC()->query->get_meta_query();             // visibility / price / stock meta
$tax_query     = WC()->query->get_tax_query();              // visibility taxonomy (exclude-from-catalog, outofstock)

/* Pridaj našu kategóriu do tax_query (AND) */
if ( $cat_slug && $cat_slug !== 'all' && $cat_slug !== 'uncategorized' ) {
  $tax_query[] = [
    'taxonomy'         => 'product_cat',
    'field'            => 'slug',
    'terms'            => [ $cat_slug ],
    'include_children' => true,
  ];
}

/* Postav query — toto sa správa ako shop (žiadne „dierky“) */
$args = [
  'post_type'           => 'product',
  'post_status'         => 'publish',
  'ignore_sticky_posts' => 1,
  'paged'               => $current_page,
  'posts_per_page'      => $per_page,
  'orderby'             => $ordering_args['orderby'],
  'order'               => $ordering_args['order'],
  'meta_key'            => isset( $ordering_args['meta_key'] ) ? $ordering_args['meta_key'] : '',
  'meta_query'          => $meta_query,
  'tax_query'           => $tax_query,
];

/* Spusti oddelenú query (presné stránkovanie pre zvolenú kategóriu) */
$q = new WP_Query( $args );

/* Redirect ak je požadovaná strana mimo rozsah */
if ( $q->max_num_pages > 0 && $current_page > $q->max_num_pages ) {
  $params = [];
  if ( $cat_slug ) $params['cat'] = $cat_slug;
  $params['paged'] = $q->max_num_pages;
  $target = get_permalink() . '?' . http_build_query( $params );
  wp_safe_redirect( $target, 302 );
  exit;
}

/* Pre UI */
$initial_cat = $cat_slug ?: 'all';
?>

<div class="shop-page-wrapper">

    <!-- Shop Header -->
  <?php
    // z Customizeru; fallback: Woo title + obsah stránky
    $shop_title    = get_theme_mod( 'misty_house_shop_title', woocommerce_page_title( false ) );
    $shop_subtitle = get_theme_mod( 'misty_house_shop_subtitle', '' );
  ?>
  <div class="shop-header">
    <h1 class="shop-title"><?php echo esc_html( $shop_title ); ?></h1>
    <div class="shop-description">
      <?php
      if ( $shop_subtitle ) {
        echo wpautop( wp_kses_post( $shop_subtitle ) );
      } elseif ( $desc = get_the_content() ) {
        echo wpautop( $desc );
      }
      ?>
    </div>
  </div>


  <!-- Filter Buttons -->
  <div class="shop-filters" data-initial-filter="<?php echo esc_attr($initial_cat); ?>">
    <button class="filter-btn <?php echo ($initial_cat==='all' ? 'active' : ''); ?>" data-filter="all">
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
        if ( 'uncategorized' === $cat->slug ) continue;
        printf(
          '<button class="filter-btn %3$s" data-filter="%1$s">%2$s</button>',
          esc_attr( $cat->slug ),
          esc_html( $cat->name ),
          $initial_cat === $cat->slug ? 'active' : ''
        );
      }
    }
    ?>
  </div>

  <!-- Products Grid -->
  <div class="products-container">
    <?php if ( $q->have_posts() ) : ?>
      <div class="products-grid">
        <?php
        while ( $q->have_posts() ) :
          $q->the_post();
          $product = wc_get_product( get_the_ID() );

          $terms = get_the_terms( get_the_ID(), 'product_cat' ) ?: [];
          $slugs = wp_list_pluck( $terms, 'slug' );
          $slugs = array_filter( $slugs, function( $s ) { return 'uncategorized' !== $s; } );
          $data_cat = implode( ' ', array_merge( ['all'], $slugs ) );

          // Badge
          $make_badge = static function( $name ) {
            $raw  = ltrim( trim( $name ), '@' );
            $caps = preg_replace( '/[^A-Z]/', '', $raw );
            if ( $caps === '' ) {
              $parts = preg_split( '/[\s\-_\.]+/', $raw, -1, PREG_SPLIT_NO_EMPTY );
              $caps  = '';
              foreach ( $parts as $p ) {
                $caps .= ( function_exists('mb_substr') ? mb_substr( $p, 0, 1 ) : substr( $p, 0, 1 ) );
              }
              $caps = strtoupper( $caps );
            }
            return substr( $caps, 0, 4 );
          };
          $badge_text = 'MH';
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
            if ( $preferred ) $badge_text = $make_badge( $preferred->name );
          }

          // Obrázok
          $main_id = 0;
          $gallery = (array) ( $product ? $product->get_gallery_image_ids() : [] );
          foreach ( $gallery as $gid ) {
            if ( mh_detect_image_role( $gid ) === 'main' ) { $main_id = $gid; break; }
          }
          if ( ! $main_id && $product ) {
            $featured = $product->get_image_id();
            if ( $featured && ! mh_is_sizing_media( $featured ) ) $main_id = $featured;
          }
          $img_html = $main_id
            ? wp_get_attachment_image( $main_id, 'medium', false, [
                'alt' => get_post_meta( $main_id, '_wp_attachment_image_alt', true ) ?: get_the_title()
              ] )
            : '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( get_the_title() ) . '">';

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
              <h3 class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

              <?php
              $description = '';
              if ( $product && $product->get_short_description() ) {
                $description = $product->get_short_description();
              } elseif ( get_the_excerpt() ) {
                $description = get_the_excerpt();
              }
              if ( $description ) {
                $description = wp_strip_all_tags( $description );
                if ( strlen( $description ) > 80 ) $description = substr( $description, 0, 80 ) . '...';
                echo '<p class="product-description">' . esc_html( $description ) . '</p>';
              }
              ?>

              <div class="product-meta">
                <span class="product-price"><?php echo $product ? $product->get_price_html() : ''; ?></span>
              </div>
            </div>

          </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    <?php else : ?>
      <p class="woocommerce-info"><?php esc_html_e( 'No products found', 'misty-house' ); ?></p>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <div class="shop-pagination">
    <?php
    $total_pages  = (int) $q->max_num_pages;
    $current_page = max( 1, $current_page );
    if ( $total_pages > 1 ) :
    ?>
      <button class="pagination-arrow prev" <?php echo $current_page <= 1 ? 'disabled' : ''; ?>
              data-page="<?php echo max( 1, $current_page - 1 ); ?>">←</button>

      <div class="pagination-dots">
        <?php for ( $i = 1; $i <= $total_pages; $i++ ) : ?>
          <button class="dot <?php echo $i === $current_page ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
            <?php echo $i; ?>
          </button>
        <?php endfor; ?>
      </div>

      <button class="pagination-arrow next" <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>
              data-page="<?php echo min( $total_pages, $current_page + 1 ); ?>">→</button>
    <?php endif; ?>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filtersWrap = document.querySelector('.shop-filters');
  if (!filtersWrap) return;

  const btns   = filtersWrap.querySelectorAll('.filter-btn');
  const url    = new URL(window.location.href);
  const initial = filtersWrap.getAttribute('data-initial-filter') || url.searchParams.get('cat') || 'all';

  function setActive(slug) {
    btns.forEach(b => b.classList.toggle('active', b.dataset.filter === slug));
  }
  setActive(initial);

  // Filter click -> go to ?cat=...&paged=1 (žiadne schovávanie na fronte)
  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      const slug = btn.dataset.filter || 'all';
      const u = new URL(window.location.href);
      if (slug && slug !== 'all') u.searchParams.set('cat', slug); else u.searchParams.delete('cat');
      u.searchParams.set('paged', '1');
      window.location.href = u.toString();
    });
  });

  // Pagination – keep cat
  document.querySelectorAll('.shop-pagination [data-page]').forEach(el => {
    el.addEventListener('click', () => {
      const page = el.getAttribute('data-page');
      const u = new URL(window.location.href);
      u.searchParams.set('paged', page);
      const currentCat = (new URL(window.location.href)).searchParams.get('cat');
      if (currentCat && currentCat !== 'all') u.searchParams.set('cat', currentCat); else u.searchParams.delete('cat');
      window.location.href = u.toString();
    });
  });
});
</script>

<?php get_footer( 'shop' ); ?>
