<?php
/**
 * Theme supports, menus, image sizes and front-end cleanup.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers theme supports, navigation locations and image sizes.
 */
function estatein_setup() {
	load_theme_textdomain( 'estatein', ESTATEIN_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 160,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'estatein' ),
			'footer'  => __( 'Footer Menu (top-level items become column headings)', 'estatein' ),
			'legal'   => __( 'Footer Legal Links', 'estatein' ),
		)
	);

	add_image_size( 'estatein-card', 864, 636, true );
	add_image_size( 'estatein-hero', 1840, 1628, false );
	add_image_size( 'estatein-avatar', 120, 120, true );
}
add_action( 'after_setup_theme', 'estatein_setup' );

/**
 * Removes front-end assets a classic theme does not use.
 */
function estatein_cleanup() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'estatein_cleanup' );

/**
 * Dequeues block-editor styles on the front end since templates are hand-built.
 */
function estatein_dequeue_block_styles() {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'estatein_dequeue_block_styles', 100 );
