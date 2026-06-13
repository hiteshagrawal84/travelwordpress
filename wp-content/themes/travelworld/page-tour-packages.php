<?php
/**
 * Tour Packages page - redirects to destinations archive
 *
 * Template Name: Tour Packages
 *
 * @package TravelWorld
 */

get_header();

$destinations = get_posts( array(
	'post_type'      => 'destination',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
) );
?>

<section class="section section--page-header">
	<div class="container">
		<div class="page-header">
			<h1 class="page-header__title"><?php esc_html_e( 'Explore Destinations', 'travelworld' ); ?></h1>
			<p class="page-header__subtitle"><?php esc_html_e( 'Select a country to view available tour packages', 'travelworld' ); ?></p>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="destination-grid">
			<?php
			if ( ! empty( $destinations ) ) {
				foreach ( $destinations as $destination ) {
					travelworld_render_destination_card( $destination->ID );
				}
			} else {
				echo '<p class="no-results">' . esc_html__( 'No destinations found.', 'travelworld' ) . '</p>';
			}
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
