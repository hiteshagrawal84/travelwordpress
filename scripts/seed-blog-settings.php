<?php
/**
 * Seed theme settings and blog posts
 */

if ( ! function_exists( 'travelworld_default_settings' ) ) {
	echo "TravelWorld theme must be active.\n";
	exit( 1 );
}

update_option( 'travelworld_settings', travelworld_default_settings() );
echo "Theme settings saved.\n";

$cat_id = wp_create_category( 'Travel Tips' );
if ( is_wp_error( $cat_id ) ) {
	$cat = get_category_by_slug( 'travel-tips' );
	$cat_id = $cat ? $cat->term_id : 1;
}

$posts = array(
	array(
		'title'   => '10 Essential Tips for Your First International Trip',
		'slug'    => 'first-international-trip-tips',
		'excerpt' => 'Planning your first trip abroad? Here are expert tips on passports, packing, budgeting, and staying safe while exploring the world.',
		'content' => '<p>Traveling internationally for the first time is exciting and a little overwhelming. Start by checking your passport validity — most countries require at least six months remaining. Research visa requirements early and consider travel insurance for peace of mind.</p><p>Pack light and smart: versatile clothing, a universal adapter, and copies of important documents. Notify your bank of travel plans to avoid card blocks abroad.</p>',
		'image'   => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80',
	),
	array(
		'title'   => 'Best Time to Visit Mauritius: A Seasonal Guide',
		'slug'    => 'best-time-visit-mauritius',
		'excerpt' => 'Discover the ideal months for beach weather, water sports, and wildlife encounters in Mauritius with our comprehensive seasonal breakdown.',
		'content' => '<p>Mauritius enjoys a tropical climate year-round, but the best time to visit is May through December when humidity is lower and rainfall is minimal. This period is perfect for beach activities, diving, and island hopping.</p><p>January to March is cyclone season with occasional heavy rains, though resorts remain open and offer great off-season deals.</p>',
		'image'   => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80',
	),
	array(
		'title'   => 'How to Plan the Perfect Family Vacation',
		'slug'    => 'plan-perfect-family-vacation',
		'excerpt' => 'From choosing kid-friendly destinations to balancing activities for all ages, learn how to create unforgettable family travel memories.',
		'content' => '<p>Family vacations require extra planning but deliver incredible rewards. Choose destinations with a mix of activities — beaches, cultural sites, and adventure options that suit different age groups.</p><p>Book accommodations with family suites or connecting rooms. Schedule downtime between activities and involve kids in planning to build excitement.</p>',
		'image'   => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80',
	),
);

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

foreach ( $posts as $post_data ) {
	$existing = get_page_by_path( $post_data['slug'], OBJECT, 'post' );
	if ( $existing ) {
		echo "  Post exists: {$post_data['title']}\n";
		continue;
	}

	$img_id = 0;
	if ( ! empty( $post_data['image'] ) ) {
		$img_id = media_sideload_image( $post_data['image'], 0, $post_data['title'], 'id' );
		if ( is_wp_error( $img_id ) ) {
			$img_id = 0;
		}
	}

	$post_id = wp_insert_post( array(
		'post_title'    => $post_data['title'],
		'post_name'     => $post_data['slug'],
		'post_excerpt'  => $post_data['excerpt'],
		'post_content'  => $post_data['content'],
		'post_status'   => 'publish',
		'post_type'     => 'post',
		'post_category' => array( (int) $cat_id ),
	) );

	if ( $img_id && ! is_wp_error( $post_id ) ) {
		set_post_thumbnail( $post_id, $img_id );
	}

	echo "  Created post: {$post_data['title']} (ID: $post_id)\n";
}

$blog_page = get_page_by_path( 'blog' );
if ( ! $blog_page ) {
	$blog_id = wp_insert_post( array(
		'post_title'  => 'Blog',
		'post_name'   => 'blog',
		'post_status' => 'publish',
		'post_type'   => 'page',
	) );
	update_option( 'page_for_posts', $blog_id );
	echo "Blog page created (ID: $blog_id).\n";
}

echo "Blog and settings seed complete.\n";
