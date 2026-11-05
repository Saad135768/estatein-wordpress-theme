<?php
/**
 * Estatein theme bootstrap.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

define( 'ESTATEIN_VERSION', '1.0.0' );
define( 'ESTATEIN_DIR', get_template_directory() );
define( 'ESTATEIN_URI', get_template_directory_uri() );

$estatein_includes = array(
	'setup',
	'enqueue',
	'post-types',
	'acf-fields',
	'template-functions',
	'newsletter',
	'seo',
	'demo-content',
);

foreach ( $estatein_includes as $estatein_file ) {
	require_once ESTATEIN_DIR . '/inc/' . $estatein_file . '.php';
}
