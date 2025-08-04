<?php
/**
 * Template part for displaying hero section
 * @package Misty_House
 */

$bg_url    = get_theme_mod(
    'misty_house_hero_bg_image',
    get_template_directory_uri() . '/assets/images/image 5.png'
);
$title_img = get_theme_mod(
  'misty_house_hero_title_image',
  get_template_directory_uri() . '/assets/images/Vrstva_1.svg'
);
$title_txt = get_theme_mod( 'misty_house_hero_title_text', get_bloginfo( 'name' ) );
$subtitle  = get_theme_mod(
    'misty_house_hero_subtitle_text',
    __( 'OPIS … kultúru.', 'misty-house' )
);
?>

<header class="hero" style="background-image: url('<?php echo esc_url( $bg_url ); ?>');">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <?php if ( $title_img ) : ?>
      <img
        src="<?php echo esc_url( $title_img ); ?>"
        alt="<?php echo esc_attr( $title_txt ); ?>"
        class="hero-title-image"
        loading="eager"
        fetchpriority="high"
      />
    <?php elseif ( $title_txt ) : ?>
      <h1 class="hero-title-text">
        <?php echo esc_html( $title_txt ); ?>
      </h1>
    <?php endif; ?>

    <?php if ( $subtitle ) : ?>
      <p class="hero-subtitle">
        <?php echo wp_kses_post( $subtitle ); ?>
      </p>
    <?php endif; ?>
  </div>
</header>
