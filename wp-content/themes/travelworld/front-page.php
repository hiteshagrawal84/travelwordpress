<?php
/**
 * Front page template
 *
 * @package TravelWorld
 */

get_header();

$hero_slides = array(
	array(
		'title'    => 'Explore the Mountains of Georgia',
		'subtitle' => 'Adventure and culture in the heart of the Caucasus',
		'image'    => 'https://images.unsplash.com/photo-1565008576549-57569a49371d?w=1920&q=80',
		'link'     => get_post_type_archive_link( 'destination' ),
	),
	array(
		'title'    => 'Mauritius Paradise Awaits',
		'subtitle' => 'Tropical beaches and crystal-clear waters',
		'image'    => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1920&q=80',
		'link'     => get_post_type_archive_link( 'destination' ),
	),
	array(
		'title'    => 'Swiss Alps Experience',
		'subtitle' => 'Breathtaking views and alpine adventures',
		'image'    => 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=1920&q=80',
		'link'     => get_post_type_archive_link( 'destination' ),
	),
);

$trending = travelworld_get_trending_packages( 3 );
$packages_url = get_post_type_archive_link( 'destination' ) ?: home_url( '/tour-packages/' );
?>

<section class="hero-slider">
	<div class="hero-slider__track">
		<?php foreach ( $hero_slides as $i => $slide ) : ?>
			<div class="hero-slide <?php echo 0 === $i ? 'is-active' : ''; ?>" style="background-image: url('<?php echo esc_url( $slide['image'] ); ?>')">
				<div class="hero-slide__overlay"></div>
				<div class="container hero-slide__content">
					<h1 class="hero-slide__title"><?php echo esc_html( $slide['title'] ); ?></h1>
					<p class="hero-slide__subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p>
					<a href="<?php echo esc_url( $slide['link'] ); ?>" class="btn btn-primary btn-lg"><?php esc_html_e( 'Explore Packages', 'travelworld' ); ?></a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<button class="hero-slider__arrow hero-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'travelworld' ); ?>">&#8249;</button>
	<button class="hero-slider__arrow hero-slider__arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'travelworld' ); ?>">&#8250;</button>
	<div class="hero-slider__dots"></div>
</section>

<section class="section section--trending">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'Trending Now', 'travelworld' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Popular destinations loved by our travelers', 'travelworld' ); ?></p>
		</div>
		<div class="package-grid">
			<?php
			if ( ! empty( $trending ) ) {
				foreach ( $trending as $package ) {
					travelworld_render_package_card( $package->ID, 'grid' );
				}
			} else {
				echo '<p class="no-results">' . esc_html__( 'No trending packages yet.', 'travelworld' ) . '</p>';
			}
			?>
		</div>
		<div class="section-cta">
			<a href="<?php echo esc_url( $packages_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'View All Packages', 'travelworld' ); ?></a>
		</div>
	</div>
</section>

<section class="section section--features">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'Why Choose Us', 'travelworld' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'We make your travel dreams come true', 'travelworld' ); ?></p>
		</div>
		<div class="features-grid">
			<div class="feature-card">
				<div class="feature-card__icon">🛡️</div>
				<h3><?php esc_html_e( '24/7 Support', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'Round-the-clock assistance for all your travel needs, wherever you are in the world.', 'travelworld' ); ?></p>
			</div>
			<div class="feature-card">
				<div class="feature-card__icon">💰</div>
				<h3><?php esc_html_e( 'Best Price Guarantee', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'We offer competitive prices and exclusive deals on all our tour packages.', 'travelworld' ); ?></p>
			</div>
			<div class="feature-card">
				<div class="feature-card__icon">⚙️</div>
				<h3><?php esc_html_e( 'Customizable Itineraries', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'Tailor your trip to match your preferences with our flexible planning options.', 'travelworld' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section section--testimonials">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'What Our Travelers Say', 'travelworld' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Real experiences from real people', 'travelworld' ); ?></p>
		</div>
		<div class="testimonials-grid">
			<div class="testimonial-card">
				<div class="testimonial-card__stars">★★★★★</div>
				<p class="testimonial-card__quote">"<?php esc_html_e( 'Our Mauritius trip was absolutely perfect. Every detail was taken care of, and the resort exceeded our expectations.', 'travelworld' ); ?>"</p>
				<div class="testimonial-card__author">
					<div class="testimonial-card__avatar"></div>
					<div>
						<strong>Sarah Johnson</strong>
						<span>New York, USA</span>
					</div>
				</div>
			</div>
			<div class="testimonial-card">
				<div class="testimonial-card__stars">★★★★★</div>
				<p class="testimonial-card__quote">"<?php esc_html_e( 'Georgia was an incredible adventure. The mountain views and local culture made it a trip of a lifetime.', 'travelworld' ); ?>"</p>
				<div class="testimonial-card__author">
					<div class="testimonial-card__avatar"></div>
					<div>
						<strong>Michael Chen</strong>
						<span>San Francisco, USA</span>
					</div>
				</div>
			</div>
			<div class="testimonial-card">
				<div class="testimonial-card__stars">★★★★★</div>
				<p class="testimonial-card__quote">"<?php esc_html_e( 'Switzerland exceeded all expectations. TravelWorld made the entire process seamless and stress-free.', 'travelworld' ); ?>"</p>
				<div class="testimonial-card__author">
					<div class="testimonial-card__avatar"></div>
					<div>
						<strong>Emma Williams</strong>
						<span>London, UK</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if ( '1' === travelworld_get_setting( 'blog_enabled', '1' ) ) :
	$blog_count = (int) travelworld_get_setting( 'blog_posts_count', 3 );
	$blog_posts = get_posts( array(
		'post_type'      => 'post',
		'posts_per_page' => $blog_count,
		'post_status'    => 'publish',
	) );
	if ( ! empty( $blog_posts ) ) :
		$blog_page = get_option( 'page_for_posts' );
		$blog_url  = $blog_page ? get_permalink( $blog_page ) : home_url( '/blog/' );
		?>
		<section class="section section--blog">
			<div class="container">
				<div class="section-header">
					<h2 class="section-title"><?php echo esc_html( travelworld_get_setting( 'blog_title' ) ); ?></h2>
					<p class="section-subtitle"><?php echo esc_html( travelworld_get_setting( 'blog_subtitle' ) ); ?></p>
				</div>
				<div class="blog-grid">
					<?php foreach ( $blog_posts as $post ) :
						setup_postdata( $post );
						travelworld_render_blog_card( $post->ID );
					endforeach;
					wp_reset_postdata();
					?>
				</div>
				<div class="section-cta">
					<a href="<?php echo esc_url( $blog_url ); ?>" class="btn btn-primary"><?php esc_html_e( 'View All Articles', 'travelworld' ); ?></a>
				</div>
			</div>
		</section>
	<?php endif;
endif; ?>

<section class="section section--cta">
	<div class="container">
		<h2 class="cta-title"><?php esc_html_e( 'Ready to Start Your Journey?', 'travelworld' ); ?></h2>
		<p class="cta-subtitle"><?php esc_html_e( 'Let us help you create unforgettable memories', 'travelworld' ); ?></p>
		<div class="cta-buttons">
			<a href="<?php echo esc_url( $packages_url ); ?>" class="btn btn-white"><?php esc_html_e( 'Explore Packages', 'travelworld' ); ?></a>
			<button type="button" class="btn btn-outline-white inquiry-trigger"><?php esc_html_e( 'Get Custom Quote', 'travelworld' ); ?></button>
		</div>
	</div>
</section>

<?php get_footer(); ?>
