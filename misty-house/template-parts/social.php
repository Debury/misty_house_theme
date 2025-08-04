<?php
/**
 * Template part for displaying social mozaic section
 *
 * @package Misty_House
 */

$placeholders = [
    'Rectangle 28.png',
    'Rectangle 29.png',
    'Rectangle 30.png',
    'Rectangle 28-1.png',
    'Rectangle 29-1.png',
];
?>

<section class="social-section" id="social">
  <div class="social-grid-desktop">
    <?php for ( $i = 1; $i <= 11; $i++ ) :
      $def     = $placeholders[ ( $i - 1 ) % count( $placeholders ) ];
      $def_url = get_template_directory_uri() . "/assets/images/$def";
      $alt     = get_theme_mod( "misty_house_mozaic_alt_$i", sprintf( __( 'Mozaic Image %d', 'misty-house' ), $i ) );
      $img     = get_theme_mod( "misty_house_mozaic_image_$i", $def_url );
      $link    = get_theme_mod( "misty_house_mozaic_link_$i", '#' );
    ?>
      <a href="<?php echo esc_url( $link ); ?>"
         class="social-item"
         target="_blank"
         rel="noopener noreferrer">
        <img
          src="<?php echo esc_url( $img ); ?>"
          alt="<?php echo esc_attr( $alt ); ?>"
          class="social-icon"
          loading="lazy"
        >
      </a>
    <?php endfor; ?>
  </div>

  <div class="social-mobile-grid">
    <?php for ( $i = 1; $i <= 5; $i++ ) :
      $def     = $placeholders[ ( $i - 1 ) % count( $placeholders ) ];
      $def_url = get_template_directory_uri() . "/assets/images/$def";
      $alt     = get_theme_mod( "misty_house_mozaic_alt_$i", sprintf( __( 'Mozaic Image %d', 'misty-house' ), $i ) );
      $img     = get_theme_mod( "misty_house_mozaic_image_$i", $def_url );
      $link    = get_theme_mod( "misty_house_mozaic_link_$i", '#' );
    ?>
      <a href="<?php echo esc_url( $link ); ?>"
         class="social-mobile-item"
         target="_blank"
         rel="noopener noreferrer">
        <img
          src="<?php echo esc_url( $img ); ?>"
          alt="<?php echo esc_attr( $alt ); ?>"
          loading="lazy"
        >
      </a>
    <?php endfor; ?>
  </div>
</section>
