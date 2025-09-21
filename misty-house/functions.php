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
    $to = get_theme_mod( 'misty_house_contact_recipient', 'mistyhouse.store@gmail.com' );

    $subject = sprintf( 'Kontakt od %s %s', $first, $last );
    $body    = "Meno: $first $last\n"
             . "Email: $email\n"
             . "Telefón: $phone\n\n"
             . "Správa:\n$message\n";

    // nech je to korektne enkódované, Reply-To zostáva na odpisovanie
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . sprintf('%s %s <%s>', $first, $last, $email),
    );

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


/* ========================================================================
 * BANNER REPEATER pre Customizer (image + alt, ľubovoľný počet položiek)
 * - Sanitizácia JSON
 * - Vlastný Customizer Control (s inline CSS/JS)
 * - Registrácia sekcie, settingu a ovládacieho prvku
 * ===================================================================== */

/** 1) Sanitizácia JSON hodnoty (pole objektov { image, alt }) */
if ( ! function_exists( 'misty_house_sanitize_banners_json' ) ) {
    function misty_house_sanitize_banners_json( $value ) {
        $raw = json_decode( wp_unslash( $value ), true );
        if ( ! is_array( $raw ) ) {
            return wp_json_encode( [] );
        }
        $out = [];
        foreach ( $raw as $item ) {
            $img = isset( $item['image'] ) ? esc_url_raw( $item['image'] ) : '';
            $alt = isset( $item['alt'] )   ? sanitize_text_field( $item['alt'] ) : '';
            if ( $img ) {
                $out[] = [ 'image' => $img, 'alt' => $alt ];
            }
        }
        return wp_json_encode( $out );
    }
}

/** 2) Vlastný repeater control do Customizera */
if ( ! class_exists( 'Misty_House_Repeater_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
    class Misty_House_Repeater_Control extends WP_Customize_Control {
        public $type = 'misty_house_repeater';

        public function enqueue() {
            wp_enqueue_media();

            // Malé štýly priamo do Customizera
            wp_add_inline_style( 'customize-controls', '
                .mh-repeater .mh-items{ margin:8px 0 10px; }
                .mh-repeater .mh-item{ background:#fff; border:1px solid #ddd; padding:10px; margin-bottom:8px; display:flex; gap:10px; align-items:flex-start; }
                .mh-repeater .mh-thumb{ width:90px; height:60px; background:#f5f5f5; border:1px solid #e5e5e5; display:flex; align-items:center; justify-content:center; overflow:hidden }
                .mh-repeater .mh-thumb img{ max-width:100%; max-height:100%; display:block }
                .mh-repeater .mh-fields{ flex:1 }
                .mh-repeater .mh-row{ display:flex; gap:8px; margin:6px 0 }
                .mh-repeater .button-link-danger{ color:#b32d2e; }
            ');

            // Logika repeateru (bezpečnejšie volanie .set)
            wp_add_inline_script( 'customize-controls', <<<'JS'
            (function(api,$){
              api.bind('ready', function(){
                $('.mh-repeater').each(function(){
                  var wrap = $(this);
                  var input = wrap.find('.mh-repeater-input');
                  var itemsWrap = wrap.find('.mh-items');
                  var settingId = wrap.data('setting'); // <- SETTING id!

                  function read(){
                    try{ return JSON.parse(input.val()||'[]'); }catch(e){ return []; }
                  }
                  function write(arr){
                    var val = JSON.stringify(arr);
                    input.val(val);
                    var setting = api(settingId);
                    if (setting) setting.set(val); // bezpečné volanie
                  }
                  function render(){
                    var arr = read();
                    itemsWrap.empty();
                    arr.forEach(function(it, idx){
                      var row   = $('<div class="mh-item"></div>');
                      var thumb = $('<div class="mh-thumb"></div>');
                      thumb.append(it.image ? $('<img/>').attr('src', it.image) : $('<span>—</span>'));

                      var fields = $('<div class="mh-fields"></div>');

                      var urlRow   = $('<div class="mh-row"></div>');
                      var urlInput = $('<input type="text" class="regular-text" placeholder="Image URL">').val(it.image||'');
                      var pickBtn  = $('<button type="button" class="button">Select image</button>');
                      urlRow.append(urlInput, pickBtn);

                      var altRow   = $('<div class="mh-row"></div>');
                      var altInput = $('<input type="text" class="regular-text" placeholder="Alt text">').val(it.alt||'');
                      altRow.append(altInput);

                      var actions  = $('<div class="mh-row"></div>');
                      var delBtn   = $('<button type="button" class="button-link button-link-danger">Remove</button>');
                      actions.append(delBtn);

                      fields.append(urlRow, altRow, actions);
                      row.append(thumb, fields);
                      itemsWrap.append(row);

                      pickBtn.on('click', function(e){
                        e.preventDefault();
                        var frame = wp.media({ title:'Select image', library:{ type:'image' }, multiple:false });
                        frame.on('select', function(){
                          var at = frame.state().get('selection').first().toJSON();
                          urlInput.val(at.url).trigger('input');
                        });
                        frame.open();
                      });

                      urlInput.on('input', function(){
                        arr[idx].image = $(this).val();
                        write(arr); render();
                      });
                      altInput.on('input', function(){
                        arr[idx].alt = $(this).val();
                        write(arr);
                      });
                      delBtn.on('click', function(e){
                        e.preventDefault();
                        arr.splice(idx,1); write(arr); render();
                      });
                    });
                  }

                  wrap.find('.mh-add').on('click', function(e){
                    e.preventDefault();
                    var arr = read(); arr.push({image:'', alt:''}); write(arr); render();
                  });

                  if(!input.val()){ write([]); }
                  render();
                });
              });
            })(wp.customize, jQuery);
            JS);
        }

        public function render_content() {
            $value = $this->value();
            if ( empty( $value ) ) $value = '[]';

            // Získaj SETTING id (nie control id)
            $first_setting = is_array( $this->settings ) ? reset( $this->settings ) : null;
            $setting_id    = $first_setting ? $first_setting->id : $this->id;
            ?>
            <div class="mh-repeater" data-setting="<?php echo esc_attr( $setting_id ); ?>">
              <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
              <?php if ( $this->description ) : ?>
                <p class="description customize-control-description"><?php echo esc_html( $this->description ); ?></p>
              <?php endif; ?>
              <input type="hidden" class="mh-repeater-input" value="<?php echo esc_attr( $value ); ?>">
              <div class="mh-items"></div>
              <p><button type="button" class="button button-primary mh-add"><?php esc_html_e( 'Add banner', 'misty-house' ); ?></button></p>
            </div>
            <?php
        }
    }
}

/** 3) Registrácia sekcie + settingu + controlu (samostatný hook) */
if ( ! function_exists( 'misty_house_register_banner_repeater' ) ) {
    function misty_house_register_banner_repeater( WP_Customize_Manager $wp_customize ) {

        // Sekcia
        $wp_customize->add_section( 'misty_house_banner_section', [
            'title'       => __( 'Banner Carousel', 'misty-house' ),
            'priority'    => 40,
            'description' => __( 'Add any number of banner slides (image + alt).', 'misty-house' ),
        ] );

        // Setting – JSON pole bannerov
        $wp_customize->add_setting( 'misty_house_banners', [
            'default'           => '[]',
            'sanitize_callback' => 'misty_house_sanitize_banners_json',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ] );

        // Control – náš repeater
        if ( class_exists( 'Misty_House_Repeater_Control' ) ) {
            $wp_customize->add_control(
                new Misty_House_Repeater_Control(
                    $wp_customize,
                    'misty_house_banners_control',
                    [
                        'label'       => __( 'Banners', 'misty-house' ),
                        'section'     => 'misty_house_banner_section',
                        'settings'    => 'misty_house_banners',
                        'description' => __( 'Add as many banners as you wish. Each has an image and alt text.', 'misty-house' ),
                    ]
                )
            );
        }
    }
    add_action( 'customize_register', 'misty_house_register_banner_repeater' );
}

/* ===================================================================== */

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
add_filter('rest_batch_enable', '__return_true');
add_filter('wc_stripe_show_payment_request_on_checkout', '__return_false');
add_filter('wc_stripe_show_payment_request_on_cart', '__return_false');
add_filter('wc_stripe_show_payment_request_on_product_page', '__return_false');








// End of functions.php


