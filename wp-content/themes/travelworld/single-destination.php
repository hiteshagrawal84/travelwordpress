<?php
/**
 * Single Destination template - shows packages for a country
 *
 * @package TravelWorld
 */

get_header();

while ( have_posts() ) :
	the_post();
	$tagline  = get_post_meta( get_the_ID(), '_tagline', true );
	$packages = travelworld_get_packages_by_destination( get_the_ID() );
	$back_url = get_permalink( get_page_by_path( 'tour-packages' ) ) ?: get_post_type_archive_link( 'destination' );
	?>
	<section class="section section--page-header section--light">
		<div class="container">
			<a href="<?php echo esc_url( $back_url ); ?>" class="back-link">
				<?php echo travelworld_icon_svg( 'arrow-left' ); ?>
				<?php esc_html_e( 'Back to Countries', 'travelworld' ); ?>
			</a>
			<div class="page-header">
				<h1 class="page-header__title"><?php the_title(); ?> <?php esc_html_e( 'Tour Packages', 'travelworld' ); ?></h1>
				<?php if ( $tagline ) : ?>
					<p class="page-header__subtitle"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section section--light">
		<div class="container">
			<div class="package-list">
				<?php
				if ( ! empty( $packages ) ) {
					foreach ( $packages as $package ) {
						travelworld_render_package_card( $package->ID, 'list' );
					}
				} else {
					echo '<p class="no-results">' . esc_html__( 'No packages available for this destination yet.', 'travelworld' ) . '</p>';
				}
				?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
