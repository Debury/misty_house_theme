<?php
/**
 * The template for displaying the footer
 * Defers the footer background image until after load to avoid LCP blocking.
 *
 * @package Misty_House
 */

defined( 'ABSPATH' ) || exit;

// bump cache key so changes show immediately
$cache_key = 'misty_house_footer_html_v13';

// try cache
$footer_html = get_transient( $cache_key );

if ( false === $footer_html ) {
	ob_start();

	// footer bg from Customizer (fallback to theme asset)
	$footer_bg_url = get_theme_mod(
		'misty_house_footer_bg_image',
		get_template_directory_uri() . '/assets/images/Group.png'
	);

	// WooCommerce URLs (safe fallbacks if WC is not active)
	$shop_url     = function_exists( 'wc_get_page_id' )        ? get_permalink( wc_get_page_id( 'shop' ) )    : home_url( '/shop/' );
	$cart_url     = function_exists( 'wc_get_cart_url' )       ? wc_get_cart_url()                            : home_url( '/cart/' );
	$checkout_url = function_exists( 'wc_get_checkout_url' )   ? wc_get_checkout_url()                        : home_url( '/checkout/' );

	// helper: link by slug or fallback URL
	if ( ! function_exists( 'mh_link_or_fallback' ) ) {
		function mh_link_or_fallback( $slug, $fallback ) {
			if ( $p = get_page_by_path( $slug ) ) {
				return get_permalink( $p->ID );
			}
			return home_url( $fallback );
		}
	}
	?>
	<footer
		class="misty-footer"
		data-bg="<?php echo esc_url( $footer_bg_url ); ?>"
		style="background-color:#000;"
	>
		<div class="footer-content">

			<!-- TOP: copyright + claim -->
			<div class="footer-top">
				<p class="footer-copyright-text">
					<?php
					echo wp_kses_post(
						get_theme_mod(
							'misty_house_footer_copyright_text',
							sprintf(
								'&copy; %1$s %2$s',
								date( 'Y' ),
								__( 'Všetky práva vyhradené spoločnosťou MistyHouse', 'misty-house' )
							)
						)
					);
					?>
				</p>
				<p class="footer-claim">
					<?php
					echo wp_kses_post(
						get_theme_mod(
							'misty_house_footer_col2_paragraph',
							__( 'Posledný okruh pekla je rezervovaný pre ľudí, ktorí robia bordel na horách a pre tých, ktorí perú Misty tričká s inými.', 'misty-house' )
						)
					);
					?>
				</p>
			</div>

			<!-- GRID: columns under the top text -->
			<div class="footer-grid">

				<!-- LEFT: MistyHouse navigation -->
				<div class="footer-column footer-column--left">
					<h4>
						<?php echo esc_html( get_theme_mod( 'misty_house_footer_col1_title', __( 'MistyHouse', 'misty-house' ) ) ); ?>
					</h4>
					<ul class="footer-list footer-list--primary">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Domov', 'misty-house' ); ?></a></li>
						<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Obchod', 'misty-house' ); ?></a></li>
						<li><a href="<?php echo esc_url( $cart_url ); ?>"><?php esc_html_e( 'Košík', 'misty-house' ); ?></a></li>
						<li><a href="<?php echo esc_url( $checkout_url ); ?>"><?php esc_html_e( 'Pokladňa', 'misty-house' ); ?></a></li>
					</ul>
				</div>

				<div class="footer-column footer-column--support">
  <h4><?php esc_html_e( 'Podpora', 'misty-house' ); ?></h4>
  <ul class="footer-list footer-list--support">
    <li><a href="<?php echo esc_url( mh_link_or_fallback( 'kontakt', '/kontakt/' ) ); ?>"><?php esc_html_e( 'Kontakt', 'misty-house' ); ?></a></li>
    <li><a href="<?php echo esc_url( mh_link_or_fallback( 'reklamacie', '/reklamacie/' ) ); ?>"><?php esc_html_e( 'Reklamácie', 'misty-house' ); ?></a></li>
  </ul>
</div>

<!-- RIGHT: Informácie -->
<div class="footer-column footer-column--legal">
  <h4><?php esc_html_e( 'Informácie', 'misty-house' ); ?></h4>
  <ul class="footer-list footer-list--legal">
    <li>
      <a href="<?php echo esc_url( mh_link_or_fallback( 'vseobecne-obchodne-podmienky', '/vop/' ) ); ?>">
        <span class="label-desktop"><?php esc_html_e('Všeobecné obchodné podmienky (VOP)','misty-house'); ?></span>
        <span class="label-mobile"><?php esc_html_e('VOP','misty-house'); ?></span>
      </a>
    </li>
    <li>
      <a href="<?php echo esc_url( mh_link_or_fallback( 'gdpr', '/gdpr/' ) ); ?>">
        <span class="label-desktop"><?php esc_html_e('Ochrana osobných údajov (GDPR)','misty-house'); ?></span>
        <span class="label-mobile"><?php esc_html_e('GDPR','misty-house'); ?></span>
      </a>
    </li>
    <li class="hide-on-mobile">
      <a href="<?php echo esc_url( mh_link_or_fallback( 'cookies', '/cookies/' ) ); ?>">
        <?php esc_html_e('Zásady používania cookies','misty-house'); ?>
      </a>
    </li>
  </ul>
</div>
				</div>

			</div><!-- /.footer-grid -->
		</div><!-- /.footer-content -->
	</footer>
	<?php

	$footer_html = ob_get_clean();
	set_transient( $cache_key, $footer_html, HOUR_IN_SECONDS );
}

// output
echo $footer_html;

// wp footer hook
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
