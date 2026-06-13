<?php
/**
 * Blog posts archive template
 *
 * @package TravelWorld
 */

get_header();
?>

<section class="page-hero page-hero--contact">
	<div class="container page-hero__content">
		<h1 class="page-hero__title"><?php esc_html_e( 'Travel Tips & Stories', 'travelworld' ); ?></h1>
		<p class="page-hero__subtitle"><?php esc_html_e( 'Inspiration and advice from our travel experts', 'travelworld' ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="blog-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					travelworld_render_blog_card( get_the_ID() );
				endwhile;
				?>
			</div>
			<div class="pagination-wrap">
				<?php the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => '&larr; ' . __( 'Previous', 'travelworld' ),
					'next_text' => __( 'Next', 'travelworld' ) . ' &rarr;',
				) ); ?>
			</div>
		<?php else : ?>
			<p class="no-results"><?php esc_html_e( 'No articles found.', 'travelworld' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
