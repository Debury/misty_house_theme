<?php
/**
 * The header for our theme
 *
 * @package Misty_House
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php
    if ( is_single() || is_page() ) {
      // Use post/page excerpt or content for individual pages
      $description = wp_strip_all_tags( get_the_excerpt() );
      if ( empty( $description ) ) {
        $description = wp_trim_words( wp_strip_all_tags( get_the_content() ), 25, '...' );
      }
      if ( empty( $description ) ) {
        $description = get_bloginfo( 'description' );
      }
    } elseif ( is_shop() || is_product_category() || is_product_tag() ) {
      // WooCommerce shop pages
      $description = 'Shop our exclusive graffiti and street art apparel collection. High-quality t-shirts, hoodies, and streetwear with unique urban designs.';
    } elseif ( is_home() || is_front_page() ) {
      // Homepage
      $description = get_bloginfo( 'description' ) ?: 'Misty House - Your graffiti & street art brand. Discover unique urban fashion and streetwear with authentic graffiti-inspired designs.';
    } else {
      // Fallback
      $description = get_bloginfo( 'description' ) ?: 'Misty House - Urban fashion and street art apparel';
    }
    echo esc_attr( wp_trim_words( $description, 25, '...' ) );
  ?>">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php
  // 1) Preload hero background for LCP (high fetch priority)
  $hero_bg = get_theme_mod(
    'misty_house_hero_bg_image',
    get_template_directory_uri() . '/assets/images/image 5.png'
  );
  ?>
  <link
    rel="preload"
    as="image"
    href="<?php echo esc_url( $hero_bg ); ?>"
    fetchpriority="high"
  />

  <?php
  // 2) Preload footer background (low priority since it's below the fold)
  $footer_bg = get_theme_mod(
    'misty_house_footer_bg_image',
    get_template_directory_uri() . '/assets/images/Group.png'
  );
  ?>
  <link
    rel="preload"
    as="image"
    href="<?php echo esc_url( $footer_bg ); ?>"
    fetchpriority="low"
  />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="navbar<?php if ( is_shop() || is_product_category() || is_product_tag() ) echo ' navbar-shop'; ?>">
  <div class="nav-logo">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img
        src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/icon_nav.svg' ); ?>"
        alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
      />
    </a>
  </div>

  <div class="nav-links" id="nav-links">
    <a href="<?php echo esc_url( class_exists('WooCommerce') ? wc_get_page_permalink('shop') : home_url('/shop/') ); ?>">
      <?php esc_html_e( 'SHOP', 'misty-house' ); ?>
    </a>
    <a href="<?php
      $kontakt = get_page_by_path( 'kontakt' );
      echo esc_url( $kontakt ? get_permalink( $kontakt->ID ) : home_url( '/kontakt/' ) );
    ?>">
      <?php esc_html_e( 'KONTAKT', 'misty-house' ); ?>
    </a>
  </div>

  <div class="nav-cart">
    <?php
      $cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
      $count    = function_exists( 'WC' ) ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <a href="<?php echo esc_url( $cart_url ); ?>">
      <img
        src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Artboard 2.png' ); ?>"
        alt="<?php esc_attr_e( 'Cart', 'misty-house' ); ?>"
      />
      <span class="cart-count"><?php echo esc_html( $count ); ?></span>
    </a>
  </div>

  <button
    class="mobile-menu-toggle"
    id="mobile-menu-toggle"
    aria-label="<?php esc_attr_e( 'Toggle navigation', 'misty-house' ); ?>"
  >
    <span></span><span></span><span></span>
  </button>
</nav>
