<?php
/**
 * Archive template for destinations
 *
 * @package TravelWorld
 */

get_header();
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
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					travelworld_render_destination_card( get_the_ID() );
				}
			} else {
				echo '<p class="no-results">' . esc_html__( 'No destinations found.', 'travelworld' ) . '</p>';
			}
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
