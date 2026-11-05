<?php
/**
 * Property search and filters for the Properties archive.
 *
 * Reads GET parameters (keyword, location, type, price, size, year) and
 * applies them to the main archive query.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Range filters shown as dropdowns: key => [ label, meta key, ranges ].
 *
 * @return array<string, array>
 */
function estatein_property_range_filters() {
	return array(
		'price' => array(
			__( 'Pricing Range', 'estatein' ),
			'price',
			array(
				'0-500000'        => __( 'Under $500K', 'estatein' ),
				'500000-1000000'  => __( '$500K – $1M', 'estatein' ),
				'1000000-2000000' => __( '$1M – $2M', 'estatein' ),
				'2000000-'        => __( 'Over $2M', 'estatein' ),
			),
		),
		'size'  => array(
			__( 'Property Size', 'estatein' ),
			'area',
			array(
				'0-1500'    => __( 'Under 1,500 sq ft', 'estatein' ),
				'1500-2500' => __( '1,500 – 2,500 sq ft', 'estatein' ),
				'2500-'     => __( 'Over 2,500 sq ft', 'estatein' ),
			),
		),
		'year'  => array(
			__( 'Build Year', 'estatein' ),
			'build_year',
			array(
				'0-1999'    => __( 'Before 2000', 'estatein' ),
				'2000-2014' => __( '2000 – 2014', 'estatein' ),
				'2015-'     => __( '2015 or newer', 'estatein' ),
			),
		),
	);
}

/**
 * Returns the distinct property locations stored in post meta.
 *
 * @return string[]
 */
function estatein_property_locations() {
	global $wpdb;

	$cached = wp_cache_get( 'estatein_locations' );
	if ( false !== $cached ) {
		return $cached;
	}

	$locations = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE pm.meta_key = %s AND pm.meta_value <> '' AND p.post_type = %s AND p.post_status = 'publish' ORDER BY pm.meta_value",
			'location',
			'property'
		)
	);

	wp_cache_set( 'estatein_locations', $locations );
	return $locations;
}

/**
 * Returns the sanitized active filter values from the request.
 *
 * @return array<string, string>
 */
function estatein_property_filter_values() {
	$keys   = array( 'keyword', 'location', 'ptype', 'price', 'size', 'year' );
	$values = array();
	foreach ( $keys as $key ) {
		$values[ $key ] = isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
	}
	return $values;
}

/**
 * Applies the search filters to the main properties archive query.
 *
 * @param WP_Query $query Query instance.
 */
function estatein_filter_properties( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'property' ) ) {
		return;
	}

	$values     = estatein_property_filter_values();
	$meta_query = array();

	$query->set( 'posts_per_page', 30 );
	$query->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );

	if ( $values['keyword'] ) {
		$query->set( 's', $values['keyword'] );
	}

	if ( $values['location'] ) {
		$meta_query[] = array(
			'key'   => 'location',
			'value' => $values['location'],
		);
	}

	if ( $values['ptype'] ) {
		$query->set(
			'tax_query',
			array(
				array(
					'taxonomy' => 'property_type',
					'field'    => 'slug',
					'terms'    => sanitize_title( $values['ptype'] ),
				),
			)
		);
	}

	foreach ( estatein_property_range_filters() as $key => $filter ) {
		if ( ! $values[ $key ] || ! isset( $filter[2][ $values[ $key ] ] ) ) {
			continue;
		}
		list( $min, $max ) = array_pad( explode( '-', $values[ $key ] ), 2, '' );
		$range             = array(
			'key'  => $filter[1],
			'type' => 'NUMERIC',
		);
		if ( '' !== $max ) {
			$range['value']   = array( (int) $min, (int) $max );
			$range['compare'] = 'BETWEEN';
		} else {
			$range['value']   = (int) $min;
			$range['compare'] = '>=';
		}
		$meta_query[] = $range;
	}

	if ( $meta_query ) {
		$query->set( 'meta_query', array_merge( array( 'relation' => 'AND' ), $meta_query ) );
	}
}
add_action( 'pre_get_posts', 'estatein_filter_properties' );

/**
 * Whether any property filter is active.
 *
 * @return bool
 */
function estatein_property_filters_active() {
	return (bool) array_filter( estatein_property_filter_values() );
}
