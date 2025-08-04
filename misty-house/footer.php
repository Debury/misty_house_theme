<?php
/**
 * The template for displaying the footer
 * Defers the footer background image until after load to avoid LCP blocking.
 *
 * @package Misty_House
 */

defined( 'ABSPATH' ) || exit;

// Unique cache key
$cache_key = 'misty_house_footer_html';

// Try to get cached footer
$footer_html = get_transient( $cache_key );

if ( false === $footer_html ) {
    ob_start();

    // Get the customizable footer background image URL (or fallback)
    $footer_bg_url = get_theme_mod(
        'misty_house_footer_bg_image',
        get_template_directory_uri() . '/assets/images/Group.png'
    );
    ?>
    <footer
      class="misty-footer"
      data-bg="<?php echo esc_url( $footer_bg_url ); ?>"
      style="background-color: #000;"
    >
      <div class="footer-content">
        <div class="footer-columns">

          <!-- Left column -->
          <div class="footer-column footer-column--left">
            <h4><?php echo esc_html( get_theme_mod(
              'misty_house_footer_col1_title',
              __( 'MistyHouse', 'misty-house' )
            ) ); ?></h4>
            <ul>
              <li>
                <a href="<?php echo esc_url( get_theme_mod(
                  'misty_house_footer_col1_link1_url',
                  home_url( '/' )
                ) ); ?>">
                  <?php echo esc_html( get_theme_mod(
                    'misty_house_footer_col1_link1_text',
                    __( 'Domov', 'misty-house' )
                  ) ); ?>
                </a>
              </li>
            </ul>
          </div>

          <!-- Center column -->
          <div class="footer-column footer-column--center">
            <p class="footer-copyright-text">
              <?php echo wp_kses_post( get_theme_mod(
                'misty_house_footer_copyright_text',
                sprintf(
                  '&copy; %1$s %2$s',
                  date( 'Y' ),
                  __( 'Všetky práva vyhradené spoločnosťou MistyHouse', 'misty-house' )
                )
              ) ); ?>
            </p>
            <p><?php echo wp_kses_post( get_theme_mod(
              'misty_house_footer_col2_paragraph',
              __( 'Posledný okruh pekla je rezervovaný pre ľudí, ktorí robia bordel na horách a pre tých, ktorí perú Misty tričká s inými.', 'misty-house' )
            ) ); ?></p>
          </div>

          <!-- Right column -->
          <div class="footer-column footer-column--right">
            <h4><?php echo esc_html( get_theme_mod(
              'misty_house_footer_col3_title',
              __( 'Podpora', 'misty-house' )
            ) ); ?></h4>
            <ul>
              <li>
                <a href="<?php
                  $p = get_page_by_path( 'kontakt' );
                  echo esc_url( $p ? get_permalink( $p->ID ) : home_url( '/kontakt/' ) );
                ?>"><?php esc_html_e( 'Kontakt', 'misty-house' ); ?></a>
              </li>
              <li>
                <a href="<?php
                  $p = get_page_by_path( 'gdpr' );
                  echo esc_url( $p ? get_permalink( $p->ID ) . '#gdpr' : home_url( '/gdpr/' ) );
                ?>"><?php esc_html_e( 'GDPR', 'misty-house' ); ?></a>
              </li>
              <li>
                <a href="<?php
                  $p = get_page_by_path( 'gdpr' );
                  echo esc_url( $p ? get_permalink( $p->ID ) . '#vratenie' : home_url( '/gdpr/' ) );
                ?>"><?php esc_html_e( 'Vrátenie tovaru', 'misty-house' ); ?></a>
              </li>
            </ul>
          </div>

        </div>
      </div>
    </footer>
    <?php

    $footer_html = ob_get_clean();
    set_transient( $cache_key, $footer_html, HOUR_IN_SECONDS );
}

// Output the cached or freshly generated footer
echo $footer_html;

// Standard WP footer hook
wp_footer();
?>
</body>
</html>

<script>
// Defer loading of the footer background image until after the page fully loads
window.addEventListener('load', function() {
  var footer = document.querySelector('.misty-footer');
  if (!footer || !footer.dataset.bg) return;

  function applyBackground() {
    footer.style.backgroundImage    = 'url(' + footer.dataset.bg + ')';
    footer.style.backgroundSize     = 'cover';
    footer.style.backgroundPosition = 'center';
  }

  if ('requestIdleCallback' in window) {
    requestIdleCallback(applyBackground);
  } else {
    setTimeout(applyBackground, 1000);
  }
});
</script>
