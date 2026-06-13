<?php
/**
 * Custom Post Types
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function travelworld_register_post_types() {
	register_post_type( 'destination', array(
		'labels' => array(
			'name'               => __( 'Destinations', 'travelworld' ),
			'singular_name'      => __( 'Destination', 'travelworld' ),
			'add_new'            => __( 'Add Destination', 'travelworld' ),
			'add_new_item'       => __( 'Add New Destination', 'travelworld' ),
			'edit_item'          => __( 'Edit Destination', 'travelworld' ),
			'new_item'           => __( 'New Destination', 'travelworld' ),
			'view_item'          => __( 'View Destination', 'travelworld' ),
			'search_items'       => __( 'Search Destinations', 'travelworld' ),
			'not_found'          => __( 'No destinations found', 'travelworld' ),
			'not_found_in_trash' => __( 'No destinations found in trash', 'travelworld' ),
			'menu_name'          => __( 'Destinations', 'travelworld' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'destinations' ),
		'menu_icon'    => 'dashicons-location-alt',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'tour_package', array(
		'labels' => array(
			'name'               => __( 'Tour Packages', 'travelworld' ),
			'singular_name'      => __( 'Tour Package', 'travelworld' ),
			'add_new'            => __( 'Add Package', 'travelworld' ),
			'add_new_item'       => __( 'Add New Package', 'travelworld' ),
			'edit_item'          => __( 'Edit Package', 'travelworld' ),
			'new_item'           => __( 'New Package', 'travelworld' ),
			'view_item'          => __( 'View Package', 'travelworld' ),
			'search_items'       => __( 'Search Packages', 'travelworld' ),
			'not_found'          => __( 'No packages found', 'travelworld' ),
			'not_found_in_trash' => __( 'No packages found in trash', 'travelworld' ),
			'menu_name'          => __( 'Tour Packages', 'travelworld' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'packages' ),
		'menu_icon'    => 'dashicons-palmtree',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'travelworld_register_post_types' );

/**
 * Flush rewrite rules on theme activation.
 */
function travelworld_rewrite_flush() {
	travelworld_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'travelworld_rewrite_flush' );
