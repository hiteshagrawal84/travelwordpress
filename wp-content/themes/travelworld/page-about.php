<?php
/**
 * About Us page template
 *
 * Template Name: About Us
 *
 * @package TravelWorld
 */

get_header();
?>

<section class="page-hero page-hero--about" style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1920&q=80')">
	<div class="page-hero__overlay"></div>
	<div class="container page-hero__content">
		<h1 class="page-hero__title"><?php esc_html_e( 'About TravelWorld', 'travelworld' ); ?></h1>
		<p class="page-hero__subtitle"><?php echo esc_html( travelworld_get_contact( 'about_text' ) ); ?></p>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="two-col">
			<div class="two-col__text">
				<h2><?php esc_html_e( 'Our Mission', 'travelworld' ); ?></h2>
				<p><?php esc_html_e( 'At TravelWorld, we believe that travel should be accessible, affordable, and unforgettable. For over 15 years, we have been crafting personalized travel experiences that connect people with the world\'s most beautiful destinations.', 'travelworld' ); ?></p>
				<p><?php esc_html_e( 'Our team of expert travel consultants works tirelessly to ensure every detail of your journey is perfectly planned. From luxury beach resorts to mountain adventures, we offer a diverse range of packages to suit every traveler\'s dream.', 'travelworld' ); ?></p>
				<p><?php esc_html_e( 'We partner with the best hotels, airlines, and local guides worldwide to deliver exceptional value and authentic experiences. Your satisfaction and safety are our top priorities.', 'travelworld' ); ?></p>
			</div>
			<div class="two-col__image">
				<img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&q=80" alt="<?php esc_attr_e( 'Travel adventure', 'travelworld' ); ?>" loading="lazy">
			</div>
		</div>
	</div>
</section>

<section class="section section--stats">
	<div class="container">
		<div class="stats-grid">
			<div class="stat-card">
				<div class="stat-card__icon">🏆</div>
				<span class="stat-card__number">15+</span>
				<span class="stat-card__label"><?php esc_html_e( 'Years Experience', 'travelworld' ); ?></span>
			</div>
			<div class="stat-card">
				<div class="stat-card__icon">🌍</div>
				<span class="stat-card__number">500+</span>
				<span class="stat-card__label"><?php esc_html_e( 'Destinations', 'travelworld' ); ?></span>
			</div>
		</div>
	</div>
</section>

<section class="section section--features">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'Our Values', 'travelworld' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'What drives us every day', 'travelworld' ); ?></p>
		</div>
		<div class="features-grid features-grid--4">
			<div class="feature-card">
				<div class="feature-card__icon">❤️</div>
				<h3><?php esc_html_e( 'Passion for Travel', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'We love what we do and it shows in every trip we plan.', 'travelworld' ); ?></p>
			</div>
			<div class="feature-card">
				<div class="feature-card__icon">🛡️</div>
				<h3><?php esc_html_e( 'Trust & Safety', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'Your safety and satisfaction are our highest priorities.', 'travelworld' ); ?></p>
			</div>
			<div class="feature-card">
				<div class="feature-card__icon">🏅</div>
				<h3><?php esc_html_e( 'Excellence', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'We strive for excellence in every aspect of your journey.', 'travelworld' ); ?></p>
			</div>
			<div class="feature-card">
				<div class="feature-card__icon">📈</div>
				<h3><?php esc_html_e( 'Innovation', 'travelworld' ); ?></h3>
				<p><?php esc_html_e( 'Constantly evolving to bring you the best travel experiences.', 'travelworld' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'Meet Our Team', 'travelworld' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Expert travel consultants ready to help you', 'travelworld' ); ?></p>
		</div>
		<div class="team-grid">
			<div class="team-card">
				<img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80" alt="Sarah Mitchell" loading="lazy">
				<h3>Sarah Mitchell</h3>
				<span class="team-card__role"><?php esc_html_e( 'Family Travel Expert', 'travelworld' ); ?></span>
				<p><?php esc_html_e( 'Specializing in family-friendly destinations with 10+ years of experience.', 'travelworld' ); ?></p>
			</div>
			<div class="team-card">
				<img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80" alt="James Rodriguez" loading="lazy">
				<h3>James Rodriguez</h3>
				<span class="team-card__role"><?php esc_html_e( 'Adventure Travel Specialist', 'travelworld' ); ?></span>
				<p><?php esc_html_e( 'Expert in adventure and outdoor travel across 30+ countries.', 'travelworld' ); ?></p>
			</div>
			<div class="team-card">
				<img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&q=80" alt="Priya Sharma" loading="lazy">
				<h3>Priya Sharma</h3>
				<span class="team-card__role"><?php esc_html_e( 'Luxury Travel Consultant', 'travelworld' ); ?></span>
				<p><?php esc_html_e( 'Curating premium experiences at the world\'s finest resorts and hotels.', 'travelworld' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section section--cta">
	<div class="container">
		<h2 class="cta-title"><?php esc_html_e( 'Ready to Plan Your Dream Vacation?', 'travelworld' ); ?></h2>
		<p class="cta-subtitle"><?php esc_html_e( 'Let our expert team create a personalized itinerary just for you', 'travelworld' ); ?></p>
		<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn btn-white"><?php esc_html_e( 'Contact Us Today', 'travelworld' ); ?></a>
	</div>
</section>

<?php get_footer(); ?>
