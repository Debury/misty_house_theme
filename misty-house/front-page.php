<?php
/**
 * Template Name: Front Page
 * @package Misty_House
 */
get_header();
?>

<?php
// Hero Section
get_template_part( 'template-parts/hero' );

// Featured products (WooCommerce)
get_template_part( 'template-parts/featured-products-title' );
get_template_part( 'template-parts/featured-products' );

// Banner carousel / Novinky
get_template_part( 'template-parts/banner' );

// Albums grid
get_template_part( 'template-parts/albums' );

// Social mozaic
get_template_part( 'template-parts/social' );
?>

<?php get_footer(); ?>
