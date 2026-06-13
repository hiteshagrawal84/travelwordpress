<?php
/**
 * Sample data importer
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if sample data was imported.
 */
function travelworld_is_sample_data_imported() {
	return (bool) get_option( 'travelworld_sample_data_imported', false );
}

/**
 * Import an image from URL into the media library.
 */
function travelworld_import_image( $url, $title = '' ) {
	if ( empty( $url ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachment_id = media_sideload_image( $url, 0, $title, 'id' );
	return is_wp_error( $attachment_id ) ? 0 : (int) $attachment_id;
}

/**
 * Get or create a page by slug.
 */
function travelworld_get_or_create_page( $title, $slug, $template = '' ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		if ( $template ) {
			update_post_meta( $page->ID, '_wp_page_template', $template );
		}
		return $page->ID;
	}

	$page_id = wp_insert_post( array(
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
		'post_type'   => 'page',
	) );

	if ( $template && $page_id ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return $page_id;
}

/**
 * Get or create a post by slug and type.
 */
function travelworld_get_or_create_post( $title, $slug, $post_type, $args = array() ) {
	$existing = get_page_by_path( $slug, OBJECT, $post_type );
	if ( $existing ) {
		return $existing->ID;
	}

	$defaults = array(
		'post_title'  => $title,
		'post_name'   => $slug,
		'post_status' => 'publish',
		'post_type'   => $post_type,
	);

	return wp_insert_post( wp_parse_args( $args, $defaults ) );
}

/**
 * Main sample data import.
 */
function travelworld_import_sample_data() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'forbidden', __( 'You do not have permission to import sample data.', 'travelworld' ) );
	}

	@set_time_limit( 300 );

	$log = array();

	// Theme settings.
	update_option( 'travelworld_settings', travelworld_default_settings() );
	$log[] = __( 'Theme settings configured.', 'travelworld' );

	// Site identity.
	update_option( 'blogname', 'TravelWorld' );
	update_option( 'blogdescription', 'Making international travel accessible and stress-free' );
	$log[] = __( 'Site title and tagline set.', 'travelworld' );

	// Permalinks.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules();
	$log[] = __( 'Permalink structure configured.', 'travelworld' );

	// Pages.
	$home_id     = travelworld_get_or_create_page( 'Home', 'home' );
	$about_id    = travelworld_get_or_create_page( 'About Us', 'about-us', 'page-about.php' );
	$packages_id = travelworld_get_or_create_page( 'Tour Packages', 'tour-packages', 'page-tour-packages.php' );
	$contact_id  = travelworld_get_or_create_page( 'Contact', 'contact', 'page-contact.php' );
	$blog_id     = travelworld_get_or_create_page( 'Blog', 'blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', $blog_id );
	$log[] = __( 'Pages created: Home, About Us, Tour Packages, Contact, Blog.', 'travelworld' );

	// Navigation menu.
	$menu_name = 'Primary Menu';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );

	if ( ! is_wp_error( $menu_id ) ) {
		$items = wp_get_nav_menu_items( $menu_id );
		if ( empty( $items ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => 'Home',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $home_id,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => 'About Us',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $about_id,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => 'Tour Packages',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $packages_id,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => 'Contact',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $contact_id,
				'menu-item-type'   => 'post_type',
				'menu-item-status' => 'publish',
			) );
		}
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		$log[] = __( 'Primary navigation menu configured.', 'travelworld' );
	}

	// Destinations.
	$destinations = array(
		'mauritius'   => array( 'title' => 'Mauritius',   'tagline' => 'Tropical paradise with pristine beaches',       'price' => 1899, 'image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80' ),
		'georgia'     => array( 'title' => 'Georgia',     'tagline' => 'Adventure and culture in the Caucasus',           'price' => 1299, 'image' => 'https://images.unsplash.com/photo-1565008576549-57569a49371d?w=800&q=80' ),
		'switzerland' => array( 'title' => 'Switzerland', 'tagline' => 'Alpine beauty and scenic landscapes',           'price' => 2499, 'image' => 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=800&q=80' ),
		'australia'   => array( 'title' => 'Australia',   'tagline' => 'Wildlife, beaches, and urban adventures',       'price' => 2199, 'image' => 'https://images.unsplash.com/photo-1523482580670-f738a847f5c0?w=800&q=80' ),
		'dubai'       => array( 'title' => 'Dubai',       'tagline' => 'Luxury and modern Arabian experiences',         'price' => 1599, 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80' ),
		'thailand'    => array( 'title' => 'Thailand',    'tagline' => 'Temples, beaches, and vibrant culture',         'price' => 999,  'image' => 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80' ),
	);

	$dest_ids = array();
	foreach ( $destinations as $slug => $data ) {
		$dest_id = travelworld_get_or_create_post( $data['title'], $slug, 'destination', array(
			'post_excerpt' => $data['tagline'],
		) );
		update_post_meta( $dest_id, '_tagline', $data['tagline'] );
		update_post_meta( $dest_id, '_starting_price', $data['price'] );

		$img_id = travelworld_import_image( $data['image'], $data['title'] );
		if ( $img_id ) {
			set_post_thumbnail( $dest_id, $img_id );
		}
		$dest_ids[ $slug ] = $dest_id;
	}
	$log[] = __( '6 destinations created with images.', 'travelworld' );

	// Tour packages.
	$packages = array(
		array( 'Mauritius Paradise Escape', 'mauritius-paradise-escape', 'mauritius', 1899, 2499, '7 Days / 6 Nights', 'Breakfast & Dinner', '5-Star', 'Paradise Beach Resort & Spa', 4.8, 124, 'Beach resort stay, Water sports, Island tours', 1, 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=800&q=80' ),
		array( 'Mauritius Luxury Retreat', 'mauritius-luxury-retreat', 'mauritius', 2499, 3199, '5 Days / 4 Nights', 'All Inclusive', '5-Star', 'Luxury Lagoon Resort', 4.9, 89, 'Private beach, Spa treatments, Catamaran cruise', 0, 'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&q=80' ),
		array( 'Georgia Mountain Adventure', 'georgia-mountain-adventure', 'georgia', 1299, 1699, '8 Days / 7 Nights', 'Breakfast', '4-Star', 'Mountain View Hotel Tbilisi', 4.9, 156, 'Mountain hiking, Wine tasting, Cultural tours', 1, 'https://images.unsplash.com/photo-1565008576549-57569a49371d?w=800&q=80' ),
		array( 'Swiss Alps Experience', 'swiss-alps-experience', 'switzerland', 2499, 3199, '7 Days / 6 Nights', 'Breakfast & Dinner', '5-Star', 'Alpine Grand Hotel', 4.8, 98, 'Scenic train rides, Mountain excursions, Lake cruises', 1, 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=800&q=80' ),
		array( 'Australia Explorer', 'australia-explorer', 'australia', 2199, 2799, '10 Days / 9 Nights', 'Breakfast', '4-Star', 'Sydney Harbour Hotel', 4.7, 112, 'Great Barrier Reef, Sydney Opera House, Wildlife parks', 0, 'https://images.unsplash.com/photo-1523482580670-f738a847f5c0?w=800&q=80' ),
		array( 'Dubai Luxury Getaway', 'dubai-luxury-getaway', 'dubai', 1599, 2099, '6 Days / 5 Nights', 'Breakfast & Dinner', '5-Star', 'Burj Al Arab Suites', 4.8, 203, 'Desert safari, Burj Khalifa, Luxury shopping', 0, 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80' ),
		array( 'Thailand Beach & Temple Tour', 'thailand-beach-temple', 'thailand', 999, 1399, '7 Days / 6 Nights', 'Breakfast', '4-Star', 'Phuket Beach Resort', 4.6, 178, 'Temple visits, Island hopping, Thai cooking class', 0, 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80' ),
	);

	foreach ( $packages as $pkg ) {
		list( $title, $slug, $dest_slug, $price, $original, $duration, $meal, $hotel_rating, $hotel_name, $rating, $reviews, $highlights, $trending, $image ) = $pkg;

		$pkg_id = travelworld_get_or_create_post( $title, $slug, 'tour_package' );
		update_post_meta( $pkg_id, '_destination_id', $dest_ids[ $dest_slug ] ?? '' );
		update_post_meta( $pkg_id, '_price', $price );
		update_post_meta( $pkg_id, '_original_price', $original );
		update_post_meta( $pkg_id, '_duration', $duration );
		update_post_meta( $pkg_id, '_meal_plan', $meal );
		update_post_meta( $pkg_id, '_hotel_rating', $hotel_rating );
		update_post_meta( $pkg_id, '_hotel_name', $hotel_name );
		update_post_meta( $pkg_id, '_rating', $rating );
		update_post_meta( $pkg_id, '_review_count', $reviews );
		update_post_meta( $pkg_id, '_highlights', $highlights );
		update_post_meta( $pkg_id, '_trending', $trending ? '1' : '0' );

		$img_id = travelworld_import_image( $image, $title );
		if ( $img_id ) {
			set_post_thumbnail( $pkg_id, $img_id );
		}
	}
	$log[] = __( '7 tour packages created with pricing and images.', 'travelworld' );

	// Mauritius itinerary detail.
	$mauritius_pkg = get_page_by_path( 'mauritius-paradise-escape', OBJECT, 'tour_package' );
	if ( $mauritius_pkg ) {
		$pkg_id = $mauritius_pkg->ID;
		update_post_meta( $pkg_id, '_gallery_images', array(
			'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=900&q=80',
			'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=500&q=80',
			'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=500&q=80',
		) );
		update_post_meta( $pkg_id, '_itinerary', array(
			array( 'title' => 'Arrival & Welcome', 'description' => 'Arrive at Sir Seewoosagur Ramgoolam International Airport and transfer to your beachfront resort.', 'activities' => "Airport pickup\nCheck-in to 5-star resort\nWelcome dinner\nLeisure time" ),
			array( 'title' => 'North Island Tour', 'description' => 'Explore the vibrant capital and northern attractions of Mauritius.', 'activities' => "Port Louis city tour\nCaudan Waterfront visit\nChamp de Mars racecourse\nLocal market exploration" ),
			array( 'title' => 'Ile aux Cerfs Island', 'description' => 'Full day catamaran cruise to the paradise island of Ile aux Cerfs.', 'activities' => "Speed boat transfer to the island\nBeach activities and swimming\nOptional water sports\nBBQ lunch on the island" ),
			array( 'title' => 'South Island Exploration', 'description' => 'Discover the natural wonders of southern Mauritius.', 'activities' => "Chamarel Seven Colored Earth\nAlexandra Falls visit\nGrand Bassin sacred lake\nVanille Nature Park" ),
			array( 'title' => 'Underwater Sea Walk & Spa', 'description' => 'Unique underwater experience and relaxation at the resort spa.', 'activities' => "Underwater sea walk experience\nCoral reef viewing\nAfternoon spa treatment\nSunset cocktails at the beach" ),
			array( 'title' => 'Leisure Day & Sunset Cruise', 'description' => 'Relax at the resort and enjoy an evening catamaran cruise.', 'activities' => "Resort leisure time\nOptional diving or snorkeling\nSunset catamaran cruise\nFarewell dinner" ),
			array( 'title' => 'Departure', 'description' => 'Check-out and transfer to the airport for your departure flight.', 'activities' => "Hotel check-out\nAirport transfer\nDeparture" ),
		) );
		update_post_meta( $pkg_id, '_inclusions', "Round-trip airport transfers\n6 nights accommodation at 5-star resort\nDaily breakfast and dinner\nAll sightseeing tours with English-speaking guide\nSpeed boat transfer to Ile aux Cerfs\nUnderwater sea walk experience\nSunset catamaran cruise\nWelcome and farewell dinner\nAll applicable taxes and service charges" );
		update_post_meta( $pkg_id, '_exclusions', "International airfare\nTravel insurance\nPersonal expenses and tips\nLunch on leisure days\nOptional water sports activities\nVisa fees (if applicable)\nLaundry and mini-bar charges\nCamera fees at tourist spots" );
		$log[] = __( 'Mauritius package itinerary, gallery, inclusions and exclusions added.', 'travelworld' );
	}

	// Blog posts.
	$cat_id = wp_create_category( 'Travel Tips' );
	if ( is_wp_error( $cat_id ) ) {
		$cat = get_category_by_slug( 'travel-tips' );
		$cat_id = $cat ? $cat->term_id : 1;
	}

	$blog_posts = array(
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

	foreach ( $blog_posts as $post_data ) {
		$post_id = travelworld_get_or_create_post( $post_data['title'], $post_data['slug'], 'post', array(
			'post_excerpt'  => $post_data['excerpt'],
			'post_content'  => $post_data['content'],
			'post_category' => array( (int) $cat_id ),
		) );
		$img_id = travelworld_import_image( $post_data['image'], $post_data['title'] );
		if ( $img_id && $post_id ) {
			set_post_thumbnail( $post_id, $img_id );
		}
	}
	$log[] = __( '3 blog posts created for homepage blog section.', 'travelworld' );

	flush_rewrite_rules();
	update_option( 'travelworld_sample_data_imported', time() );
	update_option( 'travelworld_sample_data_log', $log );

	return $log;
}

/**
 * Register import admin page.
 */
function travelworld_sample_data_menu() {
	add_theme_page(
		__( 'Import Sample Data', 'travelworld' ),
		__( 'Import Sample Data', 'travelworld' ),
		'manage_options',
		'travelworld-import',
		'travelworld_import_page'
	);
}
add_action( 'admin_menu', 'travelworld_sample_data_menu' );

/**
 * Handle import form submission.
 */
function travelworld_handle_sample_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized', 'travelworld' ) );
	}

	check_admin_referer( 'travelworld_import_sample_data' );

	$result = travelworld_import_sample_data();

	if ( is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( array(
			'page'    => 'travelworld-import',
			'import'  => 'error',
			'message' => rawurlencode( $result->get_error_message() ),
		), admin_url( 'themes.php' ) ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( array(
		'page'   => 'travelworld-import',
		'import' => 'success',
	), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_travelworld_import_sample_data', 'travelworld_handle_sample_import' );

/**
 * Admin notice after theme activation.
 */
function travelworld_sample_data_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( travelworld_is_sample_data_imported() ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'themes', 'appearance_page_travelworld-import', 'appearance_page_travelworld-settings' ), true ) ) {
		return;
	}

	$import_url = admin_url( 'themes.php?page=travelworld-import' );
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong><?php esc_html_e( 'Welcome to TravelWorld!', 'travelworld' ); ?></strong>
			<?php esc_html_e( 'Import sample data to set up pages, destinations, tour packages, blog posts, and theme settings to match the demo design.', 'travelworld' ); ?>
			<a href="<?php echo esc_url( $import_url ); ?>" class="button button-primary" style="margin-left:8px;">
				<?php esc_html_e( 'Import Sample Data', 'travelworld' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'travelworld_sample_data_notice' );

/**
 * Import page UI.
 */
function travelworld_import_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$imported = travelworld_is_sample_data_imported();
	$import_date = $imported ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $imported ) : '';
	$log = get_option( 'travelworld_sample_data_log', array() );

	if ( isset( $_GET['import'] ) && 'success' === $_GET['import'] ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Sample data imported successfully! Your site now matches the demo design.', 'travelworld' ) . '</p></div>';
	}
	if ( isset( $_GET['import'] ) && 'error' === $_GET['import'] && ! empty( $_GET['message'] ) ) {
		echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( urldecode( sanitize_text_field( wp_unslash( $_GET['message'] ) ) ) ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Sample Data', 'travelworld' ); ?></h1>

		<div class="card" style="max-width:800px;padding:20px;margin-top:20px;">
			<h2 style="margin-top:0;"><?php esc_html_e( 'One-Click Demo Setup', 'travelworld' ); ?></h2>
			<p><?php esc_html_e( 'Import all sample content to make your site look exactly like the TravelWorld demo design. This is safe to run on a fresh WordPress install.', 'travelworld' ); ?></p>

			<?php if ( $imported ) : ?>
				<p style="color:#059669;">
					<span class="dashicons dashicons-yes-alt" style="font-size:18px;width:18px;height:18px;"></span>
					<?php
					printf(
						/* translators: %s: import date */
						esc_html__( 'Sample data was last imported on %s.', 'travelworld' ),
						esc_html( $import_date )
					);
					?>
				</p>
			<?php endif; ?>

			<h3><?php esc_html_e( 'What gets imported:', 'travelworld' ); ?></h3>
			<ul style="list-style:disc;padding-left:20px;">
				<li><?php esc_html_e( 'Theme settings (top bar, contact info, blog section)', 'travelworld' ); ?></li>
				<li><?php esc_html_e( 'Pages: Home, About Us, Tour Packages, Contact, Blog', 'travelworld' ); ?></li>
				<li><?php esc_html_e( 'Primary navigation menu', 'travelworld' ); ?></li>
				<li><?php esc_html_e( '6 destinations with images and pricing', 'travelworld' ); ?></li>
				<li><?php esc_html_e( '7 tour packages with full details', 'travelworld' ); ?></li>
				<li><?php esc_html_e( 'Complete 7-day itinerary for Mauritius Paradise Escape', 'travelworld' ); ?></li>
				<li><?php esc_html_e( '3 blog posts for the homepage blog section', 'travelworld' ); ?></li>
				<li><?php esc_html_e( 'Homepage and permalink configuration', 'travelworld' ); ?></li>
			</ul>

			<p class="description" style="margin-top:16px;">
				<?php esc_html_e( 'Note: Existing content with the same slugs will be updated, not duplicated. Image import requires an internet connection.', 'travelworld' ); ?>
			</p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:20px;">
				<?php wp_nonce_field( 'travelworld_import_sample_data' ); ?>
				<input type="hidden" name="action" value="travelworld_import_sample_data">
				<?php submit_button( $imported ? __( 'Re-import Sample Data', 'travelworld' ) : __( 'Import Sample Data', 'travelworld' ), 'primary', 'submit', false ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button" target="_blank" style="margin-left:8px;">
					<?php esc_html_e( 'View Site', 'travelworld' ); ?>
				</a>
			</form>
		</div>

		<?php if ( ! empty( $log ) ) : ?>
			<div class="card" style="max-width:800px;padding:20px;margin-top:20px;">
				<h3 style="margin-top:0;"><?php esc_html_e( 'Last Import Log', 'travelworld' ); ?></h3>
				<ul style="list-style:disc;padding-left:20px;margin:0;">
					<?php foreach ( $log as $entry ) : ?>
						<li><?php echo esc_html( $entry ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
