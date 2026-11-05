<?php
/**
 * Custom post types: properties, testimonials and FAQs.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Builds a full labels array from singular and plural names.
 *
 * @param string $singular Singular label.
 * @param string $plural   Plural label.
 * @return array<string, string>
 */
function estatein_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'add_new_item'       => sprintf( __( 'Add New %s', 'estatein' ), $singular ),
		'edit_item'          => sprintf( __( 'Edit %s', 'estatein' ), $singular ),
		'new_item'           => sprintf( __( 'New %s', 'estatein' ), $singular ),
		'view_item'          => sprintf( __( 'View %s', 'estatein' ), $singular ),
		'search_items'       => sprintf( __( 'Search %s', 'estatein' ), $plural ),
		'not_found'          => sprintf( __( 'No %s found', 'estatein' ), strtolower( $plural ) ),
		'all_items'          => sprintf( __( 'All %s', 'estatein' ), $plural ),
		'menu_name'          => $plural,
	);
}

/**
 * Registers the property, testimonial and FAQ post types and the property-type taxonomy.
 */
function estatein_register_post_types() {
	register_post_type(
		'property',
		array(
			'labels'       => estatein_cpt_labels( __( 'Property', 'estatein' ), __( 'Properties', 'estatein' ) ),
			'public'       => true,
			'has_archive'  => 'properties',
			'rewrite'      => array( 'slug' => 'properties', 'with_front' => false ),
			'menu_icon'    => 'dashicons-building',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'testimonial',
		array(
			'labels'              => estatein_cpt_labels( __( 'Testimonial', 'estatein' ), __( 'Testimonials', 'estatein' ) ),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-format-quote',
			'menu_position'       => 21,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest'        => true,
		)
	);

	register_post_type(
		'faq',
		array(
			'labels'              => estatein_cpt_labels( __( 'FAQ', 'estatein' ), __( 'FAQs', 'estatein' ) ),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-editor-help',
			'menu_position'       => 22,
			'supports'            => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
			'show_in_rest'        => true,
		)
	);

	register_taxonomy(
		'property_type',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Property Types', 'estatein' ),
				'singular_name' => __( 'Property Type', 'estatein' ),
			),
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'property-type' ),
		)
	);
}
add_action( 'init', 'estatein_register_post_types' );

/**
 * Changes the title placeholder for testimonial and FAQ editors.
 *
 * @param string  $title Default placeholder.
 * @param WP_Post $post  Current post.
 * @return string
 */
function estatein_title_placeholder( $title, $post ) {
	if ( 'testimonial' === $post->post_type ) {
		return __( 'Headline, e.g. Exceptional Service!', 'estatein' );
	}
	if ( 'faq' === $post->post_type ) {
		return __( 'Question', 'estatein' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'estatein_title_placeholder', 10, 2 );

/**
 * Flushes rewrite rules once after the theme is activated.
 */
function estatein_flush_rewrites() {
	estatein_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'estatein_flush_rewrites' );
