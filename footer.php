<?php
/**
 * Site footer: newsletter form, link columns, legal links and socials.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_message = estatein_newsletter_message();
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__top">
		<div class="site-footer__brand" id="newsletter">
			<?php estatein_logo(); ?>

			<form class="newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="estatein_subscribe">
				<?php wp_nonce_field( 'estatein_subscribe', 'estatein_newsletter_nonce' ); ?>
				<label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Email address', 'estatein' ); ?></label>
				<?php estatein_the_icon( 'mail', 'icon icon--24 newsletter__icon' ); ?>
				<input class="newsletter__input" id="newsletter-email" type="email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'estatein' ); ?>">
				<input class="newsletter__trap" type="text" name="company" tabindex="-1" autocomplete="off" aria-hidden="true">
				<button class="newsletter__submit" type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'estatein' ); ?>">
					<?php estatein_the_icon( 'send', 'icon icon--send' ); ?>
				</button>
			</form>
			<?php if ( $estatein_message ) : ?>
				<p class="newsletter__message newsletter__message--<?php echo esc_attr( $estatein_message['type'] ); ?>" role="status"><?php echo esc_html( $estatein_message['text'] ); ?></p>
			<?php endif; ?>
		</div>

		<nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer', 'estatein' ); ?>">
			<?php foreach ( estatein_footer_columns() as $estatein_column ) : ?>
				<div class="footer-links__column">
					<p class="footer-links__title"><?php echo esc_html( $estatein_column['title'] ); ?></p>
					<ul class="footer-links__list">
						<?php foreach ( $estatein_column['links'] as $estatein_link ) : ?>
							<li><a href="<?php echo esc_url( $estatein_link['url'] ); ?>"><?php echo esc_html( $estatein_link['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</nav>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<div class="site-footer__legal">
				<p><?php echo esc_html( get_theme_mod( 'estatein_copyright', '@2023 Estatein. All Rights Reserved.' ) ); ?></p>
				<?php
				if ( has_nav_menu( 'legal' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'container'      => false,
							'menu_class'     => 'site-footer__legal-menu',
							'depth'          => 1,
						)
					);
				} else {
					printf( '<a href="%s">%s</a>', esc_url( get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/terms/' ) ), esc_html__( 'Terms & Conditions', 'estatein' ) );
				}
				?>
			</div>

			<ul class="socials">
				<?php foreach ( estatein_social_links() as $estatein_network => $estatein_url ) : ?>
					<li>
						<a class="socials__link" href="<?php echo esc_url( $estatein_url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $estatein_network ) ); ?>"<?php echo '#' === $estatein_url ? '' : ' target="_blank" rel="noopener"'; ?>>
							<?php estatein_the_icon( $estatein_network, 'icon icon--24' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
