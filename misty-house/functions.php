<?php
/**
 * Misty House functions and definitions
 *
 * @package Misty_House
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme and WooCommerce setup
 */
function misty_house_setup() {
    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_filter( 'woocommerce_blocks_enable_cart', '__return_false' );
    add_filter( 'woocommerce_blocks_enable_checkout', '__return_false' );
    add_filter( 'woocommerce_feature_custom_order_tables_enabled', '__return_false' );

    // WooCommerce gallery support
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // WordPress core features
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'misty-house' ),
    ) );
}
add_action( 'after_setup_theme', 'misty_house_setup' );

/**
 * Enqueue styles and scripts
 */
function misty_house_scripts() {
    // Main stylesheet versioned by modification time
    $style_version = filemtime( get_stylesheet_directory() . '/style.css' );
    wp_enqueue_style(
        'misty-house-style',
        get_stylesheet_uri(),
        array(),
        $style_version
    );

    // Custom overrides stylesheet
    $custom_css = get_template_directory() . '/assets/css/custom.css';
    if ( file_exists( $custom_css ) ) {
        wp_enqueue_style(
            'misty-house-custom',
            get_template_directory_uri() . '/assets/css/custom.css',
            array( 'misty-house-style' ),
            filemtime( $custom_css )
        );
    }

    // Main script
    $script_path = get_template_directory() . '/assets/js/main.js';
    if ( file_exists( $script_path ) ) {
        wp_enqueue_script(
            'misty-house-script',
            get_template_directory_uri() . '/assets/js/main.js',
            array( 'jquery' ),
            filemtime( $script_path ),
            true
        );
        wp_localize_script( 'misty-house-script', 'misty_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'misty_nonce' ),
        ) );
    }

    //Banner script only on front page
    if ( is_front_page() ) {
        wp_enqueue_script(
          'misty-house-banner',
          get_template_directory_uri() . '/assets/js/banner.js',
          ['misty-house-script'],
          filemtime( get_template_directory() . '/assets/js/banner.js' ),
          true
        );
      }

    // Featured products script
    wp_enqueue_script(
      'misty-house-tshirts',
      get_template_directory_uri() . '/assets/js/tshirts.js',
      ['misty-house-script'], // or ['jquery']
      filemtime( get_template_directory() . '/assets/js/tshirts.js' ),
      true
    );

    // Social media script
    wp_enqueue_script(
      'misty-house-social',
      get_template_directory_uri() . '/assets/js/social.js',
      ['misty-house-script'],
      filemtime( get_template_directory() . '/assets/js/social.js' ),
      true
    );

      // Navbar toggle
    wp_enqueue_script(
      'misty-house-navbar',
      get_template_directory_uri() . '/assets/js/navbar.js',
      array( 'misty-house-script' ), // or ['jquery'] if that’s your base
      filemtime( get_template_directory() . '/assets/js/navbar.js' ),
      true
    );

}
add_action( 'wp_enqueue_scripts', 'misty_house_scripts' );

function misty_house_enqueue_shop_assets() {
  if ( is_shop() || is_page_template('page-shop.php') ) {
    wp_enqueue_style( 'misty-house-shop', get_template_directory_uri() . '/assets/css/shop.css', [], '1.0' );
    wp_enqueue_script( 'misty-house-shop', get_template_directory_uri() . '/assets/js/shop.js', ['jquery'], '1.0', true );
  }
}
add_action( 'wp_enqueue_scripts', 'misty_house_enqueue_shop_assets' );

// 1a) Hook for logged-in users
add_action( 'admin_post_contact_form_submit', 'mh_handle_contact_form' );
// 1b) Hook for guests
add_action( 'admin_post_nopriv_contact_form_submit', 'mh_handle_contact_form' );

function mh_handle_contact_form() {
    // 1) Verify nonce
    if ( empty( $_POST['contact_nonce'] ) ||
         ! wp_verify_nonce( $_POST['contact_nonce'], 'contact_form_nonce' ) ) {
        wp_safe_redirect( add_query_arg( 'error', 'invalid_nonce', wp_get_referer() ) );
        exit;
    }

    // 2) Sanitize & collect
    $first   = sanitize_text_field( $_POST['first_name'] ?? '' );
    $last    = sanitize_text_field( $_POST['last_name']  ?? '' );
    $email   = sanitize_email(      $_POST['email']      ?? '' );
    $phone   = sanitize_text_field( $_POST['phone']      ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    // 3) Validate
    if ( ! $first || ! $last || ! $email || ! $message ) {
        wp_safe_redirect( add_query_arg( 'error', 'missing_fields', wp_get_referer() ) );
        exit;
    }
    if ( ! is_email( $email ) ) {
        wp_safe_redirect( add_query_arg( 'error', 'invalid_email', wp_get_referer() ) );
        exit;
    }

    // 4) Build email
    $to      = get_option( 'admin_email' );
    $subject = sprintf( 'Kontakt od %s %s', $first, $last );
    $body    = "Meno: $first $last\n"
             . "Email: $email\n"
             . "Telefón: $phone\n\n"
             . "Správa:\n$message\n";
    $headers = [ "Reply-To: $first $last <$email>" ];

    // 5) Send
    if ( wp_mail( $to, $subject, $body, $headers ) ) {
        wp_safe_redirect( add_query_arg( 'success', '1', wp_get_referer() ) );
    } else {
        wp_safe_redirect( add_query_arg( 'error', 'send_failed', wp_get_referer() ) );
    }
    exit;
}
/**
 * Customize WooCommerce behavior
 */
function misty_house_woocommerce_setup() {
    // Remove default WooCommerce styles
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

    // Products per row and page
    add_filter( 'loop_shop_columns', 'misty_house_products_columns' );
    add_filter( 'loop_shop_per_page', 'misty_house_products_per_page' );
}
add_action( 'init', 'misty_house_woocommerce_setup' );

function mistyhouse_enqueue_single_product_scripts() {
  if ( is_product() ) {
    // our toast & AJAX‐add‐to‐cart handler
    wp_enqueue_script(
      'mh-single-product',
      get_stylesheet_directory_uri() . '/assets/js/single-product.js',
      array( 'jquery', 'wc-add-to-cart' ),
      filemtime( get_stylesheet_directory() . '/assets/js/single-product.js' ),
      true
    );
  }
}
add_action( 'wp_enqueue_scripts', 'mistyhouse_enqueue_single_product_scripts' );

add_action( 'wp_enqueue_scripts', 'mh_localize_cart_quantities', 20 );
function mh_localize_cart_quantities() {
    // only on a single product page
    if ( ! is_product() ) {
        return;
    }

    // get the product object from the queried ID
    $post_id = get_queried_object_id();
    if ( ! $post_id ) {
        return;
    }
    $product = wc_get_product( $post_id );
    if ( ! $product ) {
        return;
    }

    // build an array of variation IDs (or the single product ID)
    $cart_counts = [];
    if ( $product->is_type( 'variable' ) ) {
        foreach ( $product->get_children() as $vid ) {
            $cart_counts[ $vid ] = 0;
        }
    } else {
        $cart_counts[ $product->get_id() ] = 0;
    }

    // tally up existing quantities in the cart
    foreach ( WC()->cart->get_cart() as $item ) {
        $pid = $item['variation_id'] ?: $item['product_id'];
        if ( isset( $cart_counts[ $pid ] ) ) {
            $cart_counts[ $pid ] += $item['quantity'];
        }
    }

    // pass that data to our single-product script
    wp_localize_script(
        'mh-single-product',   // make sure this matches the handle you used to enqueue single-product.js
        'MH_CART',
        [ 'qty' => $cart_counts ]
    );
}

add_action( 'wp_enqueue_scripts', function(){
  if ( is_product() ) {
    wp_enqueue_script( 'mh-single-product', get_stylesheet_directory_uri() . '/js/single-product.js', ['jquery'], '1.0', true );
  }
});

// 1) Remove the cart-table header row entirely
add_filter( 'woocommerce_cart_item_visible', function( $visible, $cart_item, $cart_item_key ) {
    // leave all cart items visible…
    return true;
}, 10, 3 );

// 2) Hide the Subtotal row in Cart Totals
add_filter( 'woocommerce_cart_totals_subtotal_html', '__return_empty_string' );
/**
 * Set number of columns in product grid
 */
function misty_house_products_columns() {
    return 3;
}

/**
 * Set number of products per page
 */
function misty_house_products_per_page() {
    return 12;
}


function mh_enqueue_shop_scripts() {
  if ( is_page_template('template-shop.php') || is_post_type_archive('product') ) {
    wp_enqueue_script(
      'mh-shop-filters',
      get_template_directory_uri() . '/assets/js/shop.js',
      ['jquery'],
      '1.0',
      true
    );
  }
}
add_action('wp_enqueue_scripts','mh_enqueue_shop_scripts');
/**
 * AJAX: Get cart count
 */
function misty_house_get_cart_count() {
    check_ajax_referer( 'misty_nonce', 'nonce' );
    $count = WC()->cart->get_cart_contents_count();
    wp_send_json_success( $count );
}
add_action( 'wp_ajax_get_cart_count', 'misty_house_get_cart_count' );
add_action( 'wp_ajax_nopriv_get_cart_count', 'misty_house_get_cart_count' );

/**
 * Performance optimizations
 */
function misty_house_performance_optimizations() {
    // Remove WP version
    remove_action( 'wp_head', 'wp_generator' );
    // Disable emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    // Disable REST API discovery links
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    // Disable XML-RPC
    add_filter( 'xmlrpc_enabled', '__return_false' );
    // Reduce Heartbeat frequency
    add_filter( 'heartbeat_settings', function( $settings ) {
        $settings['interval'] = 60;
        return $settings;
    } );
}
add_action( 'init', 'misty_house_performance_optimizations' );

/**
 * Include additional functionality
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/woocommerce.php';
require get_template_directory() . '/inc/admin-orders.php';
require get_template_directory() . '/inc/contact-form.php';

// End of functions.php
