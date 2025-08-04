<?php
/**
 * Template part for displaying banner carousel section
 *
 * @package Misty_House
 */

// Gather banners data
$banners_data = [];
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
        $banners_data[] = [
            'image' => esc_url( $image_url ),
            'alt'   => esc_attr( $alt_text ),
        ];
    }
}

// Arrow image URLs
$arrow_left  = get_template_directory_uri() . '/assets/images/arrow_left.png';
$arrow_right = get_template_directory_uri() . '/assets/images/arrow_right.png';
?>

<section class="banner-section">
  <div class="banner-carousel-wrapper">

    <button type="button" class="banner-btn prev" aria-label="<?php esc_attr_e( 'Previous slide', 'misty-house' ); ?>">
      <img
        src="<?php echo esc_url( $arrow_left ); ?>"
        alt="<?php esc_attr_e( 'Previous', 'misty-house' ); ?>"
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
              data-src="<?php echo $banner['image']; ?>"
              alt="<?php echo $banner['alt']; ?>"
              loading="lazy"
            >
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <button type="button" class="banner-btn next" aria-label="<?php esc_attr_e( 'Next slide', 'misty-house' ); ?>">
      <img
        src="<?php echo esc_url( $arrow_right ); ?>"
        alt="<?php esc_attr_e( 'Next', 'misty-house' ); ?>"
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
            data-slide-to="<?php echo esc_attr( $index ); ?>"
            aria-label="<?php printf( esc_attr__( 'Go to slide %d', 'misty-house' ), $index + 1 ); ?>">
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
