<?php
/**
 * Theme Customizer settings for Misty House.
 *
 * @package Misty_House
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function misty_house_customize_register( WP_Customize_Manager $wp_customize ) {
    //
    // HERO SECTION
    //
    $wp_customize->add_section( 'misty_house_hero_section', array(
        'title'       => __( 'Hero Section', 'misty-house' ),
        'priority'    => 30,
        'description' => __( 'Customize the hero section content.', 'misty-house' ),
    ) );

    // Background image
    $wp_customize->add_setting( 'misty_house_hero_bg_image', array(
        'default'           => get_template_directory_uri() . '/assets/images/image 5.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'misty_house_hero_bg_image_control', array(
        'label'    => __( 'Background Image', 'misty-house' ),
        'section'  => 'misty_house_hero_section',
        'settings' => 'misty_house_hero_bg_image',
    ) ) );

    // Title image (default to SVG, leave blank to fallback to text)
    $wp_customize->add_setting( 'misty_house_hero_title_image', array(
        'default'           => get_template_directory_uri() . '/assets/images/Vrstva_1.svg',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'misty_house_hero_title_image_control', array(
        'label'       => __( 'Title Image', 'misty-house' ),
        'section'     => 'misty_house_hero_section',
        'settings'    => 'misty_house_hero_title_image',
        'description' => __( 'If empty, the Title Text will display.', 'misty-house' ),
    ) ) );

    // Title text
    $wp_customize->add_setting( 'misty_house_hero_title_text', array(
        'default'           => __( 'Misty House', 'misty-house' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'misty_house_hero_title_text_control', array(
        'label'    => __( 'Title Text', 'misty-house' ),
        'section'  => 'misty_house_hero_section',
        'settings' => 'misty_house_hero_title_text',
        'type'     => 'text',
    ) );

    // Subtitle text
    $wp_customize->add_setting( 'misty_house_hero_subtitle_text', array(
        'default'           => __( 'Your graffiti & street art brand', 'misty-house' ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'misty_house_hero_subtitle_text_control', array(
        'label'    => __( 'Subtitle Text', 'misty-house' ),
        'section'  => 'misty_house_hero_section',
        'settings' => 'misty_house_hero_subtitle_text',
        'type'     => 'textarea',
    ) );

    //
    // NAVIGATION LINKS
    //
    $wp_customize->add_section( 'misty_house_nav_links_section', array(
        'title'       => __( 'Navigation Links', 'misty-house' ),
        'priority'    => 35,
        'description' => __( 'Customize main menu link texts and URLs.', 'misty-house' ),
    ) );

    $default_nav = array(
        array( 'text' => __( 'SHOP',    'misty-house' ), 'url' => get_permalink( wc_get_page_id( 'shop' ) ) ),
        array( 'text' => __( 'KONTAKT', 'misty-house' ), 'url' => home_url( '/kontakt/' ) ),
        array( 'text' => __( 'GDPR',    'misty-house' ), 'url' => home_url( '/gdpr/' ) ),
    );

    foreach ( $default_nav as $index => $item ) {
        $i = $index + 1;
        // Text
        $wp_customize->add_setting( "misty_house_nav_link_{$i}_text", array(
            'default'           => $item['text'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_nav_link_{$i}_text_control", array(
            'label'    => sprintf( __( 'Link %d Text', 'misty-house' ), $i ),
            'section'  => 'misty_house_nav_links_section',
            'settings' => "misty_house_nav_link_{$i}_text",
            'type'     => 'text',
        ) );

        // URL
        $wp_customize->add_setting( "misty_house_nav_link_{$i}_url", array(
            'default'           => $item['url'],
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_nav_link_{$i}_url_control", array(
            'label'    => sprintf( __( 'Link %d URL', 'misty-house' ), $i ),
            'section'  => 'misty_house_nav_links_section',
            'settings' => "misty_house_nav_link_{$i}_url",
            'type'     => 'url',
        ) );
    }

    //
    // BANNER CAROUSEL
    //
    $wp_customize->add_section( 'misty_house_banner_section', array(
        'title'       => __( 'Banner Carousel', 'misty-house' ),
        'priority'    => 40,
        'description' => __( 'Customize slide images and alt texts.', 'misty-house' ),
    ) );

    for ( $i = 1; $i <= 3; $i++ ) {
        // Slide image
        $wp_customize->add_setting( "misty_house_banner_image_{$i}", array(
            'default'           => get_template_directory_uri() . '/assets/images/Rectangle-5.png',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "misty_house_banner_image_control_{$i}", array(
            'label'    => sprintf( __( 'Banner Image %d', 'misty-house' ), $i ),
            'section'  => 'misty_house_banner_section',
            'settings' => "misty_house_banner_image_{$i}",
        ) ) );

        // Alt text
        $wp_customize->add_setting( "misty_house_banner_alt_{$i}", array(
            'default'           => sprintf( __( 'Banner Image %d', 'misty-house' ), $i ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_banner_alt_control_{$i}", array(
            'label'    => sprintf( __( 'Banner Alt Text %d', 'misty-house' ), $i ),
            'section'  => 'misty_house_banner_section',
            'settings' => "misty_house_banner_alt_{$i}",
            'type'     => 'text',
        ) );
    }

   //
    // ALBUMS SECTION
    //
    $wp_customize->add_section( 'misty_house_albums_section', array(
        'title'       => __( 'Albums Section', 'misty-house' ),
        'priority'    => 45,
        'description' => __( 'Manage the three album bubbles: image, title, and link.', 'misty-house' ),
    ) );
    
    // Optional titles above/below (keep your existing top/bottom title settings if you have them)
    // If not already defined elsewhere, you can add them here:
    // $wp_customize->add_setting( 'misty_house_albums_top_title', ... );
    // $wp_customize->add_setting( 'misty_house_albums_bottom_title', ... );
    
    // Exactly three albums, each with Image, Title, Link
    for ( $i = 1; $i <= 3; $i++ ) {
        // Image
        $wp_customize->add_setting( "misty_house_album_{$i}_image", array(
            'default'           => get_template_directory_uri() . "/assets/images/Ellipse-" . (12 + $i) . ".png",
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "misty_house_album_image_control_{$i}", array(
            'label'    => sprintf( __( 'Album %d Image', 'misty-house' ), $i ),
            'section'  => 'misty_house_albums_section',
            'settings' => "misty_house_album_{$i}_image",
        ) ) );
        
        // Title
        $wp_customize->add_setting( "misty_house_album_{$i}_title", array(
            'default'           => sprintf( __( 'Album %d', 'misty-house' ), $i ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_album_title_control_{$i}", array(
            'label'    => sprintf( __( 'Album %d Title', 'misty-house' ), $i ),
            'section'  => 'misty_house_albums_section',
            'settings' => "misty_house_album_{$i}_title",
            'type'     => 'text',
        ) );
        
        // Link (NEW)
        $wp_customize->add_setting( "misty_house_album_{$i}_link", array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_album_link_control_{$i}", array(
            'label'       => sprintf( __( 'Album %d Link URL', 'misty-house' ), $i ),
            'description' => __( 'When set, the image and the title will be clickable.', 'misty-house' ),
            'section'     => 'misty_house_albums_section',
            'settings'    => "misty_house_album_{$i}_link",
            'type'        => 'url',
        ) );
    }


    //
    // SOCIAL MOZAIC SECTION
    //
    $wp_customize->add_section( 'misty_house_social_section', array(
        'title'       => __( 'Social Mozaic', 'misty-house' ),
        'priority'    => 50,
        'description' => __( 'Images, alt text & links.', 'misty-house' ),
    ) );

    for ( $i = 1; $i <= 11; $i++ ) {
        $wp_customize->add_setting( "misty_house_mozaic_image_{$i}", array(
            'default'           => get_template_directory_uri() . '/assets/images/Rectangle-' . ( 27 + ( $i % 5 ) ) . '.png',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "misty_house_mozaic_image_control_{$i}", array(
            'label'    => sprintf( __( 'Mozaic Image %d', 'misty-house' ), $i ),
            'section'  => 'misty_house_social_section',
            'settings' => "misty_house_mozaic_image_{$i}",
        ) ) );

        $wp_customize->add_setting( "misty_house_mozaic_alt_{$i}", array(
            'default'           => sprintf( __( 'Social Image %d', 'misty-house' ), $i ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_mozaic_alt_control_{$i}", array(
            'label'    => sprintf( __( 'Mozaic Alt %d', 'misty-house' ), $i ),
            'section'  => 'misty_house_social_section',
            'settings' => "misty_house_mozaic_alt_{$i}",
            'type'     => 'text',
        ) );

        $wp_customize->add_setting( "misty_house_mozaic_link_{$i}", array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "misty_house_mozaic_link_control_{$i}", array(
            'label'    => sprintf( __( 'Mozaic Link %d', 'misty-house' ), $i ),
            'section'  => 'misty_house_social_section',
            'settings' => "misty_house_mozaic_link_{$i}",
            'type'     => 'url',
        ) );
    }
}
add_action( 'customize_register', 'misty_house_customize_register' );
