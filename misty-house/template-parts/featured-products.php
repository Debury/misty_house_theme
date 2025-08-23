<?php
/**
 * Template part for displaying featured products as t-shirts carousel
 *
 * @package Misty_House
 */

$arrow_left   = get_template_directory_uri() . '/assets/images/arrow_left.png';
$arrow_right  = get_template_directory_uri() . '/assets/images/arrow_right.png';
$placeholder  = get_template_directory_uri() . '/assets/images/t-shirt.png';

// Query featured (or fallback to latest 5)
$args = [
    'post_type'      => 'product',
    'posts_per_page' => 5,
    'meta_query'     => [
        ['key'=>'_featured','value'=>'yes'],
    ],
];
$products = new WP_Query( $args );
if ( ! $products->have_posts() ) {
    $products = new WP_Query([
        'post_type'=>'product',
        'posts_per_page'=>5,
        'orderby'=>'date',
        'order'=>'DESC',
    ]);
}

$items_html = [];
if ( $products->have_posts() ) {
    while ( $products->have_posts() ) {
        $products->the_post();
        $img   = get_the_post_thumbnail_url(get_the_ID(),'woocommerce_thumbnail') ?: $placeholder;
        $link  = get_the_permalink();
        $title = esc_attr(get_the_title());
        ob_start();
        ?>
        <div class="tshirt-item">
          <a href="<?php echo $link; ?>">
            <div class="tshirt-image-wrapper">
              <img
                src="<?php echo esc_url( $img ); ?>"
                alt="<?php echo $title; ?>"
                loading="lazy"
              >
            </div>
          </a>
        </div>
        <?php
        $items_html[] = ob_get_clean();
    }
    wp_reset_postdata();
} else {
    for ( $i = 0; $i < 10; $i++ ) {
        ob_start();
        ?>
        <div class="tshirt-item placeholder">
          <div class="tshirt-image-wrapper">
            <img
              src="<?php echo esc_url( $placeholder ); ?>"
              alt="Placeholder T-shirt"
              loading="lazy"
            >
          </div>
        </div>
        <?php
        $items_html[] = ob_get_clean();
    }
}
?>

<section class="tshirts-section">
  <div class="tshirts-carousel-wrapper">
    <button class="tshirt-btn prev">
      <img src="<?php echo esc_url( $arrow_left ); ?>" alt="Prev">
    </button>

    <div class="tshirts-container" id="tshirts-container">
      <?php
      foreach ( $items_html as $html ) {
          echo $html;
      }
      if ( count( $items_html ) > 1 ) {
          foreach ( $items_html as $html ) {
              echo $html;
          }
      }
      ?>
    </div>

    <button class="tshirt-btn next">
      <img src="<?php echo esc_url( $arrow_right ); ?>" alt="Next">
    </button>
  </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {
  var scroller = document.getElementById('tshirts-container');
  if (!scroller) return;

  // Ensure we start on the first item (no left gutter)
  var first = scroller.querySelector('.tshirt-item');
  if (first) {
    scroller.scrollTo({ left: first.offsetLeft, behavior: 'auto' });
  }
});
</script>
