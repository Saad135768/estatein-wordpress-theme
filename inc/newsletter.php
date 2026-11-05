<?php
/**
 * Footer newsletter sign-up: nonce-protected, honeypot-guarded, stored as a private CPT.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the subscriber post type visible to admins under Tools-like menu.
 */
function estatein_register_subscriber_type() {
	register_post_type(
		'estatein_subscriber',
		array(
			'labels'          => array(
				'name'          => __( 'Subscribers', 'estatein' ),
				'singular_name' => __( 'Subscriber', 'estatein' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'edit.php?post_type=property',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'estatein_register_subscriber_type' );

/**
 * Handles the newsletter form post for both logged-in and anonymous visitors.
 */
function estatein_handle_subscribe() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect = remove_query_arg( 'subscribed', $redirect );

	$nonce = isset( $_POST['estatein_newsletter_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['estatein_newsletter_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'estatein_subscribe' ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', 'error', $redirect ) . '#newsletter' );
		exit;
	}

	if ( ! empty( $_POST['company'] ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', 'success', $redirect ) . '#newsletter' );
		exit;
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', 'invalid', $redirect ) . '#newsletter' );
		exit;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'estatein_subscriber',
			'title'          => $email,
			'post_status'    => 'private',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( ! $existing ) {
		wp_insert_post(
			array(
				'post_type'   => 'estatein_subscriber',
				'post_title'  => $email,
				'post_status' => 'private',
			)
		);
	}

	wp_safe_redirect( add_query_arg( 'subscribed', 'success', $redirect ) . '#newsletter' );
	exit;
}
add_action( 'admin_post_estatein_subscribe', 'estatein_handle_subscribe' );
add_action( 'admin_post_nopriv_estatein_subscribe', 'estatein_handle_subscribe' );

/**
 * Returns the status message for the current newsletter redirect, if any.
 *
 * @return array{type:string,text:string}|null
 */
function estatein_newsletter_message() {
	$status = isset( $_GET['subscribed'] ) ? sanitize_key( wp_unslash( $_GET['subscribed'] ) ) : '';

	$messages = array(
		'success' => array( 'type' => 'success', 'text' => __( 'Thanks! You’re subscribed to Estatein updates.', 'estatein' ) ),
		'invalid' => array( 'type' => 'error', 'text' => __( 'Please enter a valid email address.', 'estatein' ) ),
		'error'   => array( 'type' => 'error', 'text' => __( 'Something went wrong. Please try again.', 'estatein' ) ),
	);

	return $messages[ $status ] ?? null;
}
