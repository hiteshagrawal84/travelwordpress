<?php
/**
 * Seed Mauritius Paradise Escape with design-accurate itinerary data
 */

$pkg = get_posts( array(
	'post_type'      => 'tour_package',
	'name'           => 'mauritius-paradise-escape',
	'posts_per_page' => 1,
) );

if ( empty( $pkg ) ) {
	echo "Package not found.\n";
	exit( 1 );
}

$pkg_id = $pkg[0]->ID;

$gallery = array(
	'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=900&q=80',
	'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=500&q=80',
	'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=500&q=80',
);
update_post_meta( $pkg_id, '_gallery_images', $gallery );

$itinerary = array(
	array(
		'title'       => 'Arrival & Welcome',
		'description' => 'Arrive at Sir Seewoosagur Ramgoolam International Airport and transfer to your beachfront resort.',
		'activities'  => "Airport pickup\nCheck-in to 5-star resort\nWelcome dinner\nLeisure time",
	),
	array(
		'title'       => 'North Island Tour',
		'description' => 'Explore the vibrant capital and northern attractions of Mauritius.',
		'activities'  => "Port Louis city tour\nCaudan Waterfront visit\nChamp de Mars racecourse\nLocal market exploration",
	),
	array(
		'title'       => 'Ile aux Cerfs Island',
		'description' => 'Full day catamaran cruise to the paradise island of Ile aux Cerfs.',
		'activities'  => "Speed boat transfer to the island\nBeach activities and swimming\nOptional water sports\nBBQ lunch on the island",
	),
	array(
		'title'       => 'South Island Exploration',
		'description' => 'Discover the natural wonders of southern Mauritius.',
		'activities'  => "Chamarel Seven Colored Earth\nAlexandra Falls visit\nGrand Bassin sacred lake\nVanille Nature Park",
	),
	array(
		'title'       => 'Underwater Sea Walk & Spa',
		'description' => 'Unique underwater experience and relaxation at the resort spa.',
		'activities'  => "Underwater sea walk experience\nCoral reef viewing\nAfternoon spa treatment\nSunset cocktails at the beach",
	),
	array(
		'title'       => 'Leisure Day & Sunset Cruise',
		'description' => 'Relax at the resort and enjoy an evening catamaran cruise.',
		'activities'  => "Resort leisure time\nOptional diving or snorkeling\nSunset catamaran cruise\nFarewell dinner",
	),
	array(
		'title'       => 'Departure',
		'description' => 'Check-out and transfer to the airport for your departure flight.',
		'activities'  => "Hotel check-out\nAirport transfer\nDeparture",
	),
);

update_post_meta( $pkg_id, '_itinerary', $itinerary );

$inclusions = "Round-trip airport transfers\n6 nights accommodation at 5-star resort\nDaily breakfast and dinner\nAll sightseeing tours with English-speaking guide\nSpeed boat transfer to Ile aux Cerfs\nUnderwater sea walk experience\nSunset catamaran cruise\nWelcome and farewell dinner\nAll applicable taxes and service charges";

$exclusions = "International airfare\nTravel insurance\nPersonal expenses and tips\nLunch on leisure days\nOptional water sports activities\nVisa fees (if applicable)\nLaundry and mini-bar charges\nCamera fees at tourist spots";

update_post_meta( $pkg_id, '_inclusions', $inclusions );
update_post_meta( $pkg_id, '_exclusions', $exclusions );

echo "Design-accurate data set for package ID: $pkg_id\n";
