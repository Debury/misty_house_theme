<?php
/**
 * Template part for displaying albums section (exactly 4 items, each can link out)
 *
 * @package Misty_House
 */

$top_title    = get_theme_mod( 'misty_house_albums_top_title', __( 'FROM MistyHouse', 'misty-house' ) );
$bottom_title = get_theme_mod( 'misty_house_albums_bottom_title', __( 'Najlepšie a najnovšie', 'misty-house' ) );

// Force exactly 4
$albums_count = 4;

// Default placeholders (1..4)
$default_images = array( 'Ellipse 13.png', 'Ellipse 14.png', 'Ellipse 9.png', 'Ellipse 10.png' );

// Build data array (1..4)
$albums_data = array();
for ( $i = 1; $i <= $albums_count; $i++ ) {
    $title = get_theme_mod( "misty_house_album_{$i}_title", sprintf( __( 'Album Title %d', 'misty-house' ), $i ) );
    $image = get_theme_mod( "misty_house_album_{$i}_image", '' );
    $link  = trim( get_theme_mod( "misty_house_album_{$i}_link", '' ) );

    // Fallback to theme asset if no custom image set
    if ( ! $image && isset( $default_images[ $i - 1 ] ) ) {
        $image = trailingslashit( get_template_directory_uri() ) . 'assets/images/' . $default_images[ $i - 1 ];
    }

    $albums_data[] = array(
        'title' => $title,
        'image' => $image ? esc_url( $image ) : '',
        'link'  => $link ? esc_url( $link ) : '',
    );
}
?>

<section class="albums-section">
  <?php if ( $top_title ) : ?>
    <h2 class="albums-section-top-title"><?php echo esc_html( $top_title ); ?></h2>
  <?php endif; ?>

  <div class="albums-grid">
    <?php foreach ( $albums_data as $album ) : ?>
      <?php
        $link  = $album['link'];
        $title = $album['title'];
        $img   = $album['image'];
        $aria  = $title ? sprintf( __( 'Open album: %s', 'misty-house' ), $title ) : __( 'Open album', 'misty-house' );
      ?>

      <?php if ( $link ) : ?>
        <a class="album-item" href="<?php echo $link; ?>" aria-label="<?php echo esc_attr( $aria ); ?>">
      <?php else : ?>
        <div class="album-item">
      <?php endif; ?>

          <div class="album-image-wrapper">
            <?php if ( $img ) : ?>
              <img
                src="<?php echo $img; ?>"
                alt="<?php echo esc_attr( $title ); ?>"
                loading="lazy"
                decoding="async"
              >
            <?php else : ?>
              <div class="album-image-placeholder" aria-hidden="true"></div>
            <?php endif; ?>
          </div>

          <?php if ( $title ) : ?>
            <p class="album-title"><?php echo esc_html( $title ); ?></p>
          <?php endif; ?>

      <?php if ( $link ) : ?>
        </a>
      <?php else : ?>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>


</section>
