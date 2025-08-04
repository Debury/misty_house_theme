<?php
/**
 * Template part for displaying albums section
 *
 * @package Misty_House
 */

$top_title    = get_theme_mod( 'misty_house_albums_top_title', __( 'Naši fellas z misty house', 'misty-house' ) );
$bottom_title = get_theme_mod( 'misty_house_albums_bottom_title', __( 'Najlepšie a najnovšie', 'misty-house' ) );
$albums_count = absint( get_theme_mod( 'misty_house_albums_count', 5 ) );

// Default placeholders
$default_images = array( 'Ellipse 13.png', 'Ellipse 14.png', 'Ellipse 9.png', 'Ellipse 15.png', 'Ellipse 16.png' );

// Build data array
$albums_data = array();
for ( $i = 1; $i <= $albums_count; $i++ ) {
    $title = get_theme_mod( "misty_house_album_{$i}_title", sprintf( __( 'Album Title %d', 'misty-house' ), $i ) );
    $image = get_theme_mod( "misty_house_album_{$i}_image", '' );

    if ( ! $image && isset( $default_images[ $i - 1 ] ) ) {
        $image = get_template_directory_uri() . '/assets/images/' . $default_images[ $i - 1 ];
    }

    if ( $image ) {
        $albums_data[] = array(
            'title' => $title,
            'image' => esc_url( $image ),
        );
    }
}
?>

<section class="albums-section">
  <?php if ( $top_title ) : ?>
    <h2 class="albums-section-top-title"><?php echo esc_html( $top_title ); ?></h2>
  <?php endif; ?>

  <?php if ( $albums_data ) : ?>
    <div class="albums-grid">
      <?php foreach ( $albums_data as $album ) : ?>
        <div class="album-item">
          <div class="album-image-wrapper">
            <img src="<?php echo $album['image']; ?>" alt="<?php echo esc_attr( $album['title'] ); ?>">
          </div>
          <?php if ( $album['title'] ) : ?>
            <p class="album-title"><?php echo esc_html( $album['title'] ); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <p><?php esc_html_e( 'No albums to display. Please configure them in the Customizer.', 'misty-house' ); ?></p>
  <?php endif; ?>

  <?php if ( $bottom_title ) : ?>
    <h2 class="albums-section-bottom-title"><?php echo esc_html( $bottom_title ); ?></h2>
  <?php endif; ?>
</section>
