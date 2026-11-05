<?php
/**
 * Site header: announcement banner and primary navigation.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_banner_text = get_theme_mod( 'estatein_banner_text', '✨Discover Your Dream Property with Estatein' );
$estatein_banner_link = get_theme_mod( 'estatein_banner_link', '#properties' );
$estatein_contact_url = estatein_contact_url();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'estatein' ); ?></a>

<header class="site-header" id="top">
	<?php if ( get_theme_mod( 'estatein_banner_enabled', true ) && $estatein_banner_text ) : ?>
		<div class="banner" data-banner>
			<p class="banner__text">
				<span><?php echo esc_html( $estatein_banner_text ); ?></span>
				<?php if ( $estatein_banner_link ) : ?>
					<a class="banner__link" href="<?php echo esc_url( $estatein_banner_link ); ?>"><?php esc_html_e( 'Learn More', 'estatein' ); ?></a>
				<?php endif; ?>
			</p>
			<button class="banner__close" type="button" data-banner-close aria-label="<?php esc_attr_e( 'Dismiss announcement', 'estatein' ); ?>">
				<?php estatein_the_icon( 'close', 'icon icon--24' ); ?>
			</button>
		</div>
	<?php endif; ?>

	<div class="navbar">
		<div class="container navbar__inner">
			<?php estatein_logo(); ?>

			<button class="navbar__toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'estatein' ); ?></span>
				<span class="navbar__burger" aria-hidden="true"></span>
			</button>

			<nav class="nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'estatein' ); ?>" data-nav>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav__list',
						'menu_id'        => 'primary-menu',
						'depth'          => 1,
						'fallback_cb'    => 'estatein_primary_menu_fallback',
					)
				);
				?>
				<a class="btn btn--ghost nav__cta nav__cta--mobile" href="<?php echo esc_url( $estatein_contact_url ); ?>"><?php esc_html_e( 'Contact Us', 'estatein' ); ?></a>
			</nav>

			<a class="btn btn--ghost nav__cta" href="<?php echo esc_url( $estatein_contact_url ); ?>"><?php esc_html_e( 'Contact Us', 'estatein' ); ?></a>
		</div>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
