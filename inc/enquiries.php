<?php
/**
 * Enquiry forms (property search request, property inquiry, contact):
 * field definitions, submission handling, storage and admin notification.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the private post type that stores submitted enquiries.
 */
function estatein_register_enquiry_type() {
	register_post_type(
		'estatein_enquiry',
		array(
			'labels'          => array(
				'name'          => __( 'Enquiries', 'estatein' ),
				'singular_name' => __( 'Enquiry', 'estatein' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 27,
			'supports'        => array( 'title', 'editor' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'estatein_register_enquiry_type' );

/**
 * Returns the option lists used by select fields.
 *
 * @return array<string, string[]>
 */
function estatein_enquiry_options() {
	$locations = estatein_property_locations();
	$types     = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => false, 'fields' => 'names' ) );

	return array(
		'location'     => $locations ? $locations : array( 'California', 'Florida', 'New York', 'Texas' ),
		'type'         => is_wp_error( $types ) || ! $types ? array( 'Villa', 'Condo', 'House' ) : $types,
		'bathrooms'    => array( '1', '2', '3', '4', '5+' ),
		'bedrooms'     => array( '1', '2', '3', '4', '5+' ),
		'budget'       => array( 'Under $500,000', '$500,000 – $1,000,000', '$1,000,000 – $2,000,000', 'Over $2,000,000' ),
		'inquiry_type' => array( 'Buying a property', 'Selling a property', 'Property management', 'Investment advice', 'Other' ),
		'source'       => array( 'Search engine', 'Social media', 'Friend or family', 'Advertisement', 'Other' ),
	);
}

/**
 * Field definitions for each form type.
 *
 * @param string $type Form type.
 * @return array<string, array>
 */
function estatein_enquiry_fields( $type ) {
	$base = array(
		'first_name' => array( 'label' => __( 'First Name', 'estatein' ), 'type' => 'text', 'placeholder' => __( 'Enter First Name', 'estatein' ), 'required' => true, 'autocomplete' => 'given-name' ),
		'last_name'  => array( 'label' => __( 'Last Name', 'estatein' ), 'type' => 'text', 'placeholder' => __( 'Enter Last Name', 'estatein' ), 'required' => true, 'autocomplete' => 'family-name' ),
		'email'      => array( 'label' => __( 'Email', 'estatein' ), 'type' => 'email', 'placeholder' => __( 'Enter your Email', 'estatein' ), 'required' => true, 'autocomplete' => 'email' ),
		'phone'      => array( 'label' => __( 'Phone', 'estatein' ), 'type' => 'tel', 'placeholder' => __( 'Enter Phone Number', 'estatein' ), 'required' => false, 'autocomplete' => 'tel' ),
	);
	$message = array(
		'message' => array( 'label' => __( 'Message', 'estatein' ), 'type' => 'textarea', 'placeholder' => __( 'Enter your Message here..', 'estatein' ), 'required' => true, 'span' => 'full' ),
	);

	switch ( $type ) {
		case 'property':
			return $base + array(
				'location'       => array( 'label' => __( 'Preferred Location', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select Location', 'estatein' ), 'options' => 'location' ),
				'property_type'  => array( 'label' => __( 'Property Type', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select Property Type', 'estatein' ), 'options' => 'type' ),
				'bathrooms'      => array( 'label' => __( 'No. of Bathrooms', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select no. of Bathrooms', 'estatein' ), 'options' => 'bathrooms' ),
				'bedrooms'       => array( 'label' => __( 'No. of Bedrooms', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select no. of Bedrooms', 'estatein' ), 'options' => 'bedrooms' ),
				'budget'         => array( 'label' => __( 'Budget', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select Budget', 'estatein' ), 'options' => 'budget', 'span' => 'half' ),
				'contact_method' => array( 'label' => __( 'Preferred Contact Method', 'estatein' ), 'type' => 'contact_method', 'span' => 'half' ),
			) + $message;
		case 'inquiry':
			return $base + array(
				'property' => array( 'label' => __( 'Selected Property', 'estatein' ), 'type' => 'property', 'span' => 'full' ),
			) + $message;
		default:
			return $base + array(
				'inquiry_type' => array( 'label' => __( 'Inquiry Type', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select Inquiry Type', 'estatein' ), 'options' => 'inquiry_type' ),
				'source'       => array( 'label' => __( 'How Did You Hear About Us?', 'estatein' ), 'type' => 'select', 'placeholder' => __( 'Select', 'estatein' ), 'options' => 'source' ),
			) + $message;
	}
}

/**
 * Handles a submitted enquiry form.
 */
function estatein_handle_enquiry() {
	$type     = isset( $_POST['enquiry_type'] ) ? sanitize_key( wp_unslash( $_POST['enquiry_type'] ) ) : 'contact';
	$type     = in_array( $type, array( 'property', 'inquiry', 'contact' ), true ) ? $type : 'contact';
	$redirect = wp_get_referer() ? remove_query_arg( 'enquiry', wp_get_referer() ) : home_url( '/' );
	$anchor   = '#enquiry-' . $type;
	$fail     = static function ( $status ) use ( $redirect, $anchor ) {
		wp_safe_redirect( add_query_arg( 'enquiry', $status, $redirect ) . $anchor );
		exit;
	};

	$nonce = isset( $_POST['estatein_enquiry_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['estatein_enquiry_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'estatein_enquiry_' . $type ) ) {
		$fail( 'error' );
	}
	if ( ! empty( $_POST['company'] ) ) {
		$fail( 'success' );
	}
	if ( empty( $_POST['terms'] ) ) {
		$fail( 'terms' );
	}

	$data = array();
	foreach ( estatein_enquiry_fields( $type ) as $name => $field ) {
		$raw = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';

		if ( 'contact_method' === $field['type'] ) {
			$method = isset( $_POST['contact_method'] ) ? sanitize_key( wp_unslash( $_POST['contact_method'] ) ) : 'email';
			$data[ $name ] = 'phone' === $method ? __( 'Phone', 'estatein' ) : __( 'Email', 'estatein' );
			continue;
		}
		if ( 'property' === $field['type'] ) {
			$property_id   = absint( $raw );
			$data[ $name ] = 'property' === get_post_type( $property_id ) ? get_the_title( $property_id ) : '';
			continue;
		}

		$value = 'textarea' === $field['type'] ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
		if ( 'email' === $field['type'] ) {
			$value = sanitize_email( $value );
			if ( ! is_email( $value ) ) {
				$fail( 'invalid' );
			}
		}
		if ( ! empty( $field['required'] ) && '' === $value ) {
			$fail( 'invalid' );
		}
		$data[ $name ] = $value;
	}

	$lines = array();
	foreach ( estatein_enquiry_fields( $type ) as $name => $field ) {
		if ( isset( $data[ $name ] ) && '' !== $data[ $name ] ) {
			$lines[] = $field['label'] . ': ' . $data[ $name ];
		}
	}
	$body  = implode( "\n", $lines );
	$title = sprintf( '%1$s %2$s — %3$s', $data['first_name'], $data['last_name'], ucfirst( $type ) );

	wp_insert_post(
		array(
			'post_type'    => 'estatein_enquiry',
			'post_title'   => $title,
			'post_content' => $body,
			'post_status'  => 'private',
		)
	);

	wp_mail(
		get_option( 'admin_email' ),
		sprintf( '[%1$s] %2$s', get_bloginfo( 'name' ), $title ),
		$body,
		array( 'Reply-To: ' . $data['email'] )
	);

	wp_safe_redirect( add_query_arg( 'enquiry', 'success', $redirect ) . $anchor );
	exit;
}
add_action( 'admin_post_estatein_enquiry', 'estatein_handle_enquiry' );
add_action( 'admin_post_nopriv_estatein_enquiry', 'estatein_handle_enquiry' );

/**
 * Returns the status message after an enquiry redirect.
 *
 * @return array{type:string,text:string}|null
 */
function estatein_enquiry_message() {
	$status   = isset( $_GET['enquiry'] ) ? sanitize_key( wp_unslash( $_GET['enquiry'] ) ) : '';
	$messages = array(
		'success' => array( 'type' => 'success', 'text' => __( 'Thank you! Your message has been sent. An Estatein agent will contact you within one business day.', 'estatein' ) ),
		'invalid' => array( 'type' => 'error', 'text' => __( 'Please fill in all required fields with valid information.', 'estatein' ) ),
		'terms'   => array( 'type' => 'error', 'text' => __( 'Please agree to the Terms of Use and Privacy Policy.', 'estatein' ) ),
		'error'   => array( 'type' => 'error', 'text' => __( 'Something went wrong. Please try again.', 'estatein' ) ),
	);
	return $messages[ $status ] ?? null;
}
