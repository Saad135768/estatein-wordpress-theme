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
	wp_enqueue_style( 'estatein-main', ESTATEIN_URI . '/assets/css/main.css', array(), estatein_asset_version( 'assets/css/main.css' ) );

	wp_enqueue_script(
		'estatein-main',
		ESTATEIN_URI . '/assets/js/main.js',
		array(),
		estatein_asset_version( 'assets/js/main.js' ),
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
 * Swaps the no-js body class for js before first paint.
 */
function estatein_js_class() {
	echo "<script>document.documentElement.classList.add('js');</script>\n";
}
add_action( 'wp_head', 'estatein_js_class', 0 );
