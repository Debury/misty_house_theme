<?php
/**
 * Template part for displaying banner carousel section
 *
 * @package Misty_House
 */

// 1) Load all banners from Customizer repeater (JSON)
$banners_json = get_theme_mod( 'misty_house_banners', '[]' );
$banners_all  = json_decode( $banners_json, true );
if ( ! is_array( $banners_all ) ) {
    $banners_all = [];
} else {
    // sanitize in case something weird sneaks in
    $banners_all = array_values( array_filter( array_map( function( $it ) {
        $img = isset( $it['image'] ) ? esc_url_raw( $it['image'] ) : '';
        $alt = isset( $it['alt'] )   ? sanitize_text_field( $it['alt'] ) : '';
        if ( ! $img ) return null;
        return [ 'image' => $img, 'alt' => $alt ];
    }, $banners_all ) ) );
}

// 2) Fallback to old 1..3 settings if repeater is empty
if ( empty( $banners_all ) ) {
    for ( $i = 1; $i <= 3; $i++ ) {
        $image_url = get_theme_mod(
            "misty_house_banner_image_$i",
            get_template_directory_uri() . '/assets/images/Rectangle 5.png'
        );
        $alt_text = get_theme_mod(
            "misty_house_banner_alt_$i",
            sprintf( __( 'Banner Image %d', 'misty-house' ), $i )
        );

        if ( $image_url ) {
            $banners_all[] = [
                'image' => esc_url_raw( $image_url ),
                'alt'   => sanitize_text_field( $alt_text ),
            ];
        }
    }
}

// 3) Shuffle and take up to 3
if ( ! empty( $banners_all ) ) {
    shuffle( $banners_all );
}
$banners_data = array_slice( $banners_all, 0, 3 );

// If nothing to show, bail out early
if ( empty( $banners_data ) ) {
    return;
}

// Arrow image URLs
$arrow_left  = get_template_directory_uri() . '/assets/images/arrow_left.png';
$arrow_right = get_template_directory_uri() . '/assets/images/arrow_right.png';
?>

<section class="banner-section">
  <div class="banner-carousel-wrapper">

    <button type="button" class="banner-btn prev" aria-label="<?php echo esc_attr__( 'Previous slide', 'misty-house' ); ?>">
      <img
        src="<?php echo esc_url( $arrow_left ); ?>"
        alt="<?php echo esc_attr__( 'Previous', 'misty-house' ); ?>"
        width="176" height="211"
        loading="eager"
      >
    </button>

    <div class="banner-carousel">
      <div class="banner-container" id="banner-container">
        <?php foreach ( $banners_data as $banner ) : ?>
          <div class="banner-item">
            <img
              class="lazy-banner"
              src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
              data-src="<?php echo esc_url( $banner['image'] ); ?>"
              alt="<?php echo esc_attr( $banner['alt'] ); ?>"
              loading="lazy"
            >
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <button type="button" class="banner-btn next" aria-label="<?php echo esc_attr__( 'Next slide', 'misty-house' ); ?>">
      <img
        src="<?php echo esc_url( $arrow_right ); ?>"
        alt="<?php echo esc_attr__( 'Next', 'misty-house' ); ?>"
        width="176" height="211"
        loading="eager"
      >
    </button>

    <?php if ( count( $banners_data ) > 1 ) : ?>
      <div class="banner-indicators">
        <?php foreach ( $banners_data as $index => $banner ) : ?>
          <button
            type="button"
            class="indicator<?php echo $index === 0 ? ' active' : ''; ?>"
            data-slide-to="<?php echo esc_attr( (string) $index ); ?>"
            aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'misty-house' ), $index + 1 ) ); ?>">
          </button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
// Lazy‐load banner images via IntersectionObserver
document.addEventListener('DOMContentLoaded', function() {
  var banners = document.querySelectorAll('img.lazy-banner');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries, obs) {
      entries.forEach(function(entry) {
        if (!entry.isIntersecting) return;
        var img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
        img.classList.remove('lazy-banner');
        obs.unobserve(img);
      });
    }, { rootMargin: '200px' });
    banners.forEach(function(img) { io.observe(img); });
  } else {
    // Fallback: load all immediately
    banners.forEach(function(img) {
      img.src = img.dataset.src;
      img.removeAttribute('data-src');
      img.classList.remove('lazy-banner');
    });
  }
});
</script>
