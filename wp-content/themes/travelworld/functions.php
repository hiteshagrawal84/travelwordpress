<?php
/**
 * TravelWorld Theme Functions
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TRAVELWORLD_VERSION', '1.0.0' );
define( 'TRAVELWORLD_DIR', get_template_directory() );
define( 'TRAVELWORLD_URI', get_template_directory_uri() );

require_once TRAVELWORLD_DIR . '/inc/theme-setup.php';
require_once TRAVELWORLD_DIR . '/inc/admin-settings.php';
require_once TRAVELWORLD_DIR . '/inc/sample-data.php';
require_once TRAVELWORLD_DIR . '/inc/custom-post-types.php';
require_once TRAVELWORLD_DIR . '/inc/meta-boxes.php';
require_once TRAVELWORLD_DIR . '/inc/template-tags.php';
require_once TRAVELWORLD_DIR . '/inc/inquiry-handler.php';

/**
 * Enqueue scripts and styles.
 */
function travelworld_enqueue_assets() {
	wp_enqueue_style(
		'travelworld-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'travelworld-style',
		TRAVELWORLD_URI . '/assets/css/main.css',
		array( 'travelworld-google-fonts' ),
		TRAVELWORLD_VERSION
	);

	wp_enqueue_script(
		'travelworld-main',
		TRAVELWORLD_URI . '/assets/js/main.js',
		array(),
		TRAVELWORLD_VERSION,
		true
	);

	wp_localize_script( 'travelworld-main', 'travelworldData', array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'travelworld_inquiry' ),
		'phone'    => travelworld_get_setting( 'phone', '+1 (234) 567-890' ),
		'whatsapp' => travelworld_get_setting( 'whatsapp', '+1234567890' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'travelworld_enqueue_assets' );

/**
 * Register widget areas.
 */
function travelworld_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Column 1', 'travelworld' ),
		'id'            => 'footer-1',
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
	) );
}
add_action( 'widgets_init', 'travelworld_widgets_init' );

/**
 * Customizer settings (legacy fallback).
 */
function travelworld_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'travelworld_contact', array(
		'title'    => __( 'Contact Info', 'travelworld' ),
		'priority' => 30,
		'description' => __( 'These settings are managed in Appearance → Theme Settings.', 'travelworld' ),
	) );
}
add_action( 'customize_register', 'travelworld_customize_register' );

/**
 * Add body classes.
 */
function travelworld_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-home';
	}
	if ( is_singular( 'tour_package' ) ) {
		$classes[] = 'page-package-detail';
	}
	return $classes;
}
add_filter( 'body_class', 'travelworld_body_classes' );
