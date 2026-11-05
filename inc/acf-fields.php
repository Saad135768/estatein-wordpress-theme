<?php
/**
 * ACF field groups registered in code so they are version-controlled.
 *
 * Works with the free version of ACF (no repeaters or options pages).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Builds an ACF field definition with a stable, prefixed key.
 *
 * @param string $name  Field name.
 * @param string $label Field label.
 * @param string $type  ACF field type.
 * @param array  $args  Extra field settings.
 * @return array
 */
function estatein_acf_field( $name, $label, $type = 'text', $args = array() ) {
	return array_merge(
		array(
			'key'   => 'field_estatein_' . $name,
			'name'  => $name,
			'label' => $label,
			'type'  => $type,
		),
		$args
	);
}

/**
 * Builds an ACF tab field.
 *
 * @param string $label Tab label.
 * @return array
 */
function estatein_acf_tab( $label ) {
	return array(
		'key'       => 'field_estatein_tab_' . sanitize_key( $label ),
		'label'     => $label,
		'type'      => 'tab',
		'placement' => 'top',
	);
}

/**
 * Builds the heading, description and button fields shared by every section header.
 *
 * @param string $prefix  Field name prefix.
 * @param string $heading Default heading shown as placeholder.
 * @return array[]
 */
function estatein_acf_section_header_fields( $prefix, $heading ) {
	return array(
		estatein_acf_field( $prefix . '_heading', __( 'Heading', 'estatein' ), 'text', array( 'placeholder' => $heading ) ),
		estatein_acf_field( $prefix . '_text', __( 'Description', 'estatein' ), 'textarea', array( 'rows' => 3 ) ),
		estatein_acf_field( $prefix . '_button', __( 'Button', 'estatein' ), 'link', array( 'return_format' => 'array' ) ),
	);
}

/**
 * Registers all local field groups.
 */
function estatein_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$icon_choices = array(
		'feature-home'        => __( 'Storefront / Home', 'estatein' ),
		'feature-value'       => __( 'Banknote / Value', 'estatein' ),
		'feature-management'  => __( 'Buildings / Management', 'estatein' ),
		'feature-investments' => __( 'Sun / Investments', 'estatein' ),
	);

	$stat_fields = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$stat_fields[] = estatein_acf_field(
			'hero_stat_' . $i,
			sprintf( __( 'Stat %d', 'estatein' ), $i ),
			'group',
			array(
				'layout'     => 'row',
				'wrapper'    => array( 'width' => 33 ),
				'sub_fields' => array(
					estatein_acf_field( 'value', __( 'Value', 'estatein' ), 'text', array( 'key' => 'field_estatein_hero_stat_' . $i . '_value' ) ),
					estatein_acf_field( 'label', __( 'Label', 'estatein' ), 'text', array( 'key' => 'field_estatein_hero_stat_' . $i . '_label' ) ),
				),
			)
		);
	}

	$feature_fields = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$feature_fields[] = estatein_acf_field(
			'feature_' . $i,
			sprintf( __( 'Feature %d', 'estatein' ), $i ),
			'group',
			array(
				'layout'     => 'block',
				'wrapper'    => array( 'width' => 50 ),
				'sub_fields' => array(
					estatein_acf_field( 'title', __( 'Title', 'estatein' ), 'text', array( 'key' => 'field_estatein_feature_' . $i . '_title' ) ),
					estatein_acf_field( 'icon', __( 'Icon', 'estatein' ), 'select', array( 'key' => 'field_estatein_feature_' . $i . '_icon', 'choices' => $icon_choices ) ),
					estatein_acf_field( 'link', __( 'Link', 'estatein' ), 'page_link', array( 'key' => 'field_estatein_feature_' . $i . '_link', 'allow_null' => 1, 'allow_archives' => 1 ) ),
				),
			)
		);
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_estatein_front_page',
			'title'                 => __( 'Home Page Sections', 'estatein' ),
			'location'              => array( array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ) ),
			'position'              => 'acf_after_title',
			'style'                 => 'seamless',
			'hide_on_screen'        => array( 'the_content' ),
			'fields'                => array_merge(
				array(
					estatein_acf_tab( __( 'Hero', 'estatein' ) ),
					estatein_acf_field( 'hero_title', __( 'Title', 'estatein' ), 'text', array( 'placeholder' => 'Discover Your Dream Property with Estatein' ) ),
					estatein_acf_field( 'hero_text', __( 'Intro text', 'estatein' ), 'textarea', array( 'rows' => 3 ) ),
					estatein_acf_field( 'hero_secondary_button', __( 'Secondary button', 'estatein' ), 'link', array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_acf_field( 'hero_primary_button', __( 'Primary button', 'estatein' ), 'link', array( 'wrapper' => array( 'width' => 50 ) ) ),
					estatein_acf_field( 'hero_image', __( 'Hero image', 'estatein' ), 'image', array( 'return_format' => 'id', 'preview_size' => 'medium', 'instructions' => __( 'Portrait building shot, at least 920×814px.', 'estatein' ) ) ),
					estatein_acf_field( 'hero_badge_text', __( 'Rotating badge text', 'estatein' ), 'text', array( 'placeholder' => 'Discover Your Dream Property' ) ),
				),
				$stat_fields,
				array( estatein_acf_tab( __( 'Feature cards', 'estatein' ) ) ),
				$feature_fields,
				array( estatein_acf_tab( __( 'Featured properties', 'estatein' ) ) ),
				estatein_acf_section_header_fields( 'properties', 'Featured Properties' ),
				array(
					estatein_acf_field(
						'properties_source',
						__( 'Which properties?', 'estatein' ),
						'radio',
						array(
							'choices'       => array(
								'featured' => __( 'Only properties marked “Featured”', 'estatein' ),
								'latest'   => __( 'Latest properties', 'estatein' ),
							),
							'default_value' => 'featured',
							'layout'        => 'horizontal',
						)
					),
					estatein_acf_field( 'properties_limit', __( 'Number of properties', 'estatein' ), 'number', array( 'default_value' => 9, 'min' => 3, 'max' => 30 ) ),
				),
				array( estatein_acf_tab( __( 'Testimonials', 'estatein' ) ) ),
				estatein_acf_section_header_fields( 'testimonials', 'What Our Clients Say' ),
				array( estatein_acf_tab( __( 'FAQs', 'estatein' ) ) ),
				estatein_acf_section_header_fields( 'faqs', 'Frequently Asked Questions' ),
				array(
					estatein_acf_tab( __( 'Call to action', 'estatein' ) ),
					estatein_acf_field( 'cta_title', __( 'Title', 'estatein' ), 'text', array( 'placeholder' => 'Start Your Real Estate Journey Today' ) ),
					estatein_acf_field( 'cta_text', __( 'Text', 'estatein' ), 'textarea', array( 'rows' => 4 ) ),
					estatein_acf_field( 'cta_button', __( 'Button', 'estatein' ), 'link' ),
				)
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_estatein_property',
			'title'    => __( 'Property Details', 'estatein' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'property' ) ) ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_acf_field( 'price', __( 'Price (USD)', 'estatein' ), 'number', array( 'min' => 0, 'prepend' => '$', 'required' => 1, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'bedrooms', __( 'Bedrooms', 'estatein' ), 'number', array( 'min' => 0, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'bathrooms', __( 'Bathrooms', 'estatein' ), 'number', array( 'min' => 0, 'step' => 0.5, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'area', __( 'Area (sq ft)', 'estatein' ), 'number', array( 'min' => 0, 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'location', __( 'Location', 'estatein' ), 'text', array( 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_acf_field( 'is_featured', __( 'Featured on Home page', 'estatein' ), 'true_false', array( 'ui' => 1, 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_acf_field( 'tagline', __( 'Card tagline', 'estatein' ), 'text', array( 'placeholder' => 'Coastal Escapes - Where Waves Beckon', 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_acf_field( 'build_year', __( 'Build year', 'estatein' ), 'number', array( 'min' => 1800, 'max' => 2100, 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_acf_field( 'key_features', __( 'Key features & amenities (one per line)', 'estatein' ), 'textarea', array( 'rows' => 5, 'new_lines' => '' ) ),
				estatein_acf_tab( __( 'Pricing details', 'estatein' ) ),
				estatein_acf_field( 'fee_transfer_tax', __( 'Property transfer tax', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'fee_legal', __( 'Legal fees', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'fee_inspection', __( 'Home inspection', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'fee_insurance', __( 'Property insurance (annual)', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'monthly_tax', __( 'Property taxes (monthly)', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field( 'monthly_hoa', __( 'HOA fee (monthly)', 'estatein' ), 'number', array( 'prepend' => '$', 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field( 'down_payment_percent', __( 'Down payment %', 'estatein' ), 'number', array( 'default_value' => 20, 'min' => 0, 'max' => 100, 'append' => '%', 'wrapper' => array( 'width' => 34 ) ) ),
			),
		)
	);

	$simple_groups = array(
		'team_member' => array(
			__( 'Team Member Details', 'estatein' ),
			array(
				estatein_acf_field( 'role', __( 'Role', 'estatein' ), 'text', array( 'placeholder' => 'Founder', 'wrapper' => array( 'width' => 50 ) ) ),
				estatein_acf_field( 'twitter_url', __( 'Twitter / X URL', 'estatein' ), 'url', array( 'wrapper' => array( 'width' => 50 ) ) ),
			),
		),
		'client'      => array(
			__( 'Client Details', 'estatein' ),
			array(
				estatein_acf_field( 'since', __( 'Client since (year)', 'estatein' ), 'number', array( 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'website', __( 'Website', 'estatein' ), 'url', array( 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'domain', __( 'Domain', 'estatein' ), 'text', array( 'placeholder' => 'Commercial Real Estate', 'wrapper' => array( 'width' => 25 ) ) ),
				estatein_acf_field( 'category', __( 'Category', 'estatein' ), 'text', array( 'placeholder' => 'Luxury Home Development', 'wrapper' => array( 'width' => 25 ) ) ),
			),
		),
		'service'     => array(
			__( 'Service Details', 'estatein' ),
			array(
				estatein_acf_field(
					'service_group',
					__( 'Group', 'estatein' ),
					'select',
					array(
						'choices'       => estatein_service_group_choices(),
						'default_value' => 'selling',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				estatein_acf_field(
					'service_icon',
					__( 'Icon', 'estatein' ),
					'select',
					array(
						'choices' => array_combine( estatein_service_icons(), estatein_service_icons() ),
						'wrapper' => array( 'width' => 50 ),
					)
				),
			),
		),
		'office'      => array(
			__( 'Office Details', 'estatein' ),
			array(
				estatein_acf_field( 'office_label', __( 'Label', 'estatein' ), 'text', array( 'placeholder' => 'Main Headquarters', 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field(
					'office_region',
					__( 'Region', 'estatein' ),
					'select',
					array(
						'choices' => array(
							'regional'      => __( 'Regional', 'estatein' ),
							'international' => __( 'International', 'estatein' ),
						),
						'wrapper' => array( 'width' => 33 ),
					)
				),
				estatein_acf_field( 'office_city', __( 'City', 'estatein' ), 'text', array( 'wrapper' => array( 'width' => 34 ) ) ),
				estatein_acf_field( 'office_email', __( 'Email', 'estatein' ), 'email', array( 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field( 'office_phone', __( 'Phone', 'estatein' ), 'text', array( 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field( 'office_map', __( 'Directions URL', 'estatein' ), 'url', array( 'wrapper' => array( 'width' => 34 ) ) ),
			),
		),
	);

	foreach ( $simple_groups as $type => $group ) {
		acf_add_local_field_group(
			array(
				'key'      => 'group_estatein_' . $type,
				'title'    => $group[0],
				'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => $type ) ) ),
				'position' => 'acf_after_title',
				'fields'   => $group[1],
			)
		);
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_estatein_page_hero',
			'title'    => __( 'Page Hero', 'estatein' ),
			'location' => array(
				array(
					array( 'param' => 'post_type', 'operator' => '==', 'value' => 'page' ),
					array( 'param' => 'page_type', 'operator' => '!=', 'value' => 'front_page' ),
				),
			),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_acf_field( 'page_hero_title', __( 'Hero title', 'estatein' ), 'text' ),
				estatein_acf_field( 'page_hero_text', __( 'Hero text', 'estatein' ), 'textarea', array( 'rows' => 3 ) ),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_estatein_testimonial',
			'title'    => __( 'Client Details', 'estatein' ),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'testimonial' ) ) ),
			'position' => 'acf_after_title',
			'fields'   => array(
				estatein_acf_field( 'rating', __( 'Rating', 'estatein' ), 'range', array( 'min' => 1, 'max' => 5, 'step' => 1, 'default_value' => 5, 'wrapper' => array( 'width' => 34 ) ) ),
				estatein_acf_field( 'client_name', __( 'Client name', 'estatein' ), 'text', array( 'required' => 1, 'wrapper' => array( 'width' => 33 ) ) ),
				estatein_acf_field( 'client_location', __( 'Client location', 'estatein' ), 'text', array( 'placeholder' => 'USA, California', 'wrapper' => array( 'width' => 33 ) ) ),
			),
		)
	);
}
add_action( 'acf/include_fields', 'estatein_register_acf_fields' );
