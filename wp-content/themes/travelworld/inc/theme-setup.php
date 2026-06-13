<?php
/**
 * Theme setup
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function travelworld_setup() {
	load_theme_textdomain( 'travelworld', TRAVELWORLD_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	add_image_size( 'destination-card', 600, 400, true );
	add_image_size( 'package-card', 400, 300, true );
	add_image_size( 'hero-slide', 1920, 800, true );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'travelworld' ),
		'footer'  => __( 'Footer Menu', 'travelworld' ),
	) );
}
add_action( 'after_setup_theme', 'travelworld_setup' );

/**
 * Default menu fallback.
 */
function travelworld_default_menu() {
	$pages = array(
		'home'          => __( 'Home', 'travelworld' ),
		'about-us'      => __( 'About Us', 'travelworld' ),
		'tour-packages' => __( 'Tour Packages', 'travelworld' ),
		'contact'       => __( 'Contact', 'travelworld' ),
	);

	echo '<ul class="nav-menu">';
	foreach ( $pages as $slug => $label ) {
		$page = get_page_by_path( $slug );
		$url  = $page ? get_permalink( $page ) : home_url( '/' );
		$current = ( is_page( $slug ) || ( $slug === 'home' && is_front_page() ) ) ? ' current-menu-item' : '';
		printf(
			'<li class="menu-item%s"><a href="%s">%s</a></li>',
			esc_attr( $current ),
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

function travelworld_footer_menu() {
	$pages = array(
		'home'          => __( 'Home', 'travelworld' ),
		'about-us'      => __( 'About Us', 'travelworld' ),
		'tour-packages' => __( 'Tour Packages', 'travelworld' ),
		'contact'       => __( 'Contact', 'travelworld' ),
	);

	echo '<ul class="footer-links">';
	foreach ( $pages as $slug => $label ) {
		$page = get_page_by_path( $slug );
		$url  = $page ? get_permalink( $page ) : home_url( '/' );
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}
