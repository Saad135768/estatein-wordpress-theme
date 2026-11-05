<?php
/**
 * Styles, scripts, font and hero preloading.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns a cache-busting version based on file modification time.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function estatein_asset_version( $relative_path ) {
	$file = ESTATEIN_DIR . '/' . ltrim( $relative_path, '/' );
	return file_exists( $file ) ? (string) filemtime( $file ) : ESTATEIN_VERSION;
}

/**
 * Enqueues the theme stylesheet and deferred script.
 */
function estatein_enqueue_assets() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ! file_exists( ESTATEIN_DIR . '/assets/css/main.min.css' ) ? '' : '.min';
	$css    = 'assets/css/main' . $suffix . '.css';
	$js     = 'assets/js/main' . $suffix . '.js';

	wp_enqueue_style( 'estatein-main', ESTATEIN_URI . '/' . $css, array(), estatein_asset_version( $css ) );

	wp_enqueue_script(
		'estatein-main',
		ESTATEIN_URI . '/' . $js,
		array(),
		estatein_asset_version( $js ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'estatein_enqueue_assets' );

/**
 * Preloads the primary font weights and, on the front page, the LCP hero image.
 */
function estatein_preload_assets() {
	foreach ( array( 500, 600 ) as $weight ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( ESTATEIN_URI . '/assets/fonts/urbanist-' . $weight . '.woff2' )
		);
	}

	if ( is_front_page() ) {
		$hero = estatein_hero_image();
		if ( ! empty( $hero['src'] ) ) {
			printf(
				'<link rel="preload" href="%1$s" as="image" fetchpriority="high"%2$s>' . "\n",
				esc_url( $hero['src'] ),
				$hero['srcset'] ? ' imagesrcset="' . esc_attr( $hero['srcset'] ) . '" imagesizes="' . esc_attr( $hero['sizes'] ) . '"' : ''
			);
		}
	}
}
add_action( 'wp_head', 'estatein_preload_assets', 1 );

/**
 * Adds the js class (and remembered banner dismissal) before first paint.
 */
function estatein_js_class() {
	echo "<script>document.documentElement.classList.add('js');try{if(localStorage.getItem('estatein-banner-dismissed')==='1'){document.documentElement.classList.add('banner-dismissed');}}catch(e){}</script>\n";
}

/**
 * Uses the Estatein mark as favicon when no Site Icon is configured.
 */
function estatein_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( ESTATEIN_URI . '/assets/icons/logo-symbol.svg' ) );
}
add_action( 'wp_head', 'estatein_favicon', 3 );
add_action( 'wp_head', 'estatein_js_class', 0 );
