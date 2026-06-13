<?php
/**
 * Default index template
 *
 * @package TravelWorld
 */

get_header();
?>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="post-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'post-card' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="no-results"><?php esc_html_e( 'Nothing found.', 'travelworld' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
