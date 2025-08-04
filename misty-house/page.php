<?php
/**
 * The template for displaying all pages
 *
 * @package Misty_House
 */

get_header(); ?>

<main class="page-content">
    <div class="container">
        <?php
        while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();
                    wp_link_pages( array(
                        'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'misty-house' ),
                        'after'  => '</nav>',
                    ) );
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
