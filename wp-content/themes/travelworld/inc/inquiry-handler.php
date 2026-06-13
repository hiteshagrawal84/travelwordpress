<?php
/**
 * Inquiry form handler
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function travelworld_handle_inquiry() {
	check_ajax_referer( 'travelworld_inquiry', 'nonce' );

	$name         = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email        = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone        = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$travel_date  = sanitize_text_field( wp_unslash( $_POST['travel_date'] ?? '' ) );
	$adults       = absint( $_POST['adults'] ?? 2 );
	$children     = absint( $_POST['children'] ?? 0 );
	$trip_type    = sanitize_text_field( wp_unslash( $_POST['trip_type'] ?? '' ) );
	$requirements = sanitize_textarea_field( wp_unslash( $_POST['requirements'] ?? '' ) );
	$package      = sanitize_text_field( wp_unslash( $_POST['package'] ?? '' ) );

	if ( empty( $name ) || empty( $email ) || empty( $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'travelworld' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'travelworld' ) ) );
	}

	$post_id = wp_insert_post( array(
		'post_type'   => 'inquiry',
		'post_title'  => sprintf( 'Inquiry from %s - %s', $name, current_time( 'mysql' ) ),
		'post_status' => 'private',
		'post_content' => sprintf(
			"Package: %s\nName: %s\nEmail: %s\nPhone: %s\nTravel Date: %s\nAdults: %d\nChildren: %d\nTrip Type: %s\nRequirements: %s",
			$package,
			$name,
			$email,
			$phone,
			$travel_date,
			$adults,
			$children,
			$trip_type,
			$requirements
		),
	) );

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'travelworld' ) ) );
	}

	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( '[TravelWorld] New Inquiry from %s', $name );
	$body        = sprintf(
		"A new travel inquiry has been received:\n\nPackage: %s\nName: %s\nEmail: %s\nPhone: %s\nTravel Date: %s\nAdults: %d\nChildren: %d\nTrip Type: %s\n\nSpecial Requirements:\n%s",
		$package,
		$name,
		$email,
		$phone,
		$travel_date,
		$adults,
		$children,
		$trip_type,
		$requirements
	);

	wp_mail( $admin_email, $subject, $body );

	wp_send_json_success( array(
		'message' => __( 'Thank you! Your inquiry has been submitted. We will contact you shortly.', 'travelworld' ),
	) );
}
add_action( 'wp_ajax_travelworld_inquiry', 'travelworld_handle_inquiry' );
add_action( 'wp_ajax_nopriv_travelworld_inquiry', 'travelworld_handle_inquiry' );

function travelworld_register_inquiry_cpt() {
	register_post_type( 'inquiry', array(
		'labels' => array(
			'name'          => __( 'Inquiries', 'travelworld' ),
			'singular_name' => __( 'Inquiry', 'travelworld' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-email-alt',
		'supports'     => array( 'title', 'editor' ),
	) );
}
add_action( 'init', 'travelworld_register_inquiry_cpt' );
