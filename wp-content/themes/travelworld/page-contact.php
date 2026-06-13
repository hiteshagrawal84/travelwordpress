<?php
/**
 * Contact page template
 *
 * Template Name: Contact
 *
 * @package TravelWorld
 */

get_header();

$phone     = travelworld_get_contact( 'phone' );
$phone2    = travelworld_get_contact( 'phone2' );
$email     = travelworld_get_contact( 'email' );
$email2    = travelworld_get_contact( 'email2' );
$address   = travelworld_get_contact( 'address' );
$whatsapp  = preg_replace( '/[^0-9]/', '', travelworld_get_contact( 'whatsapp' ) );
$map_embed = travelworld_get_setting( 'contact_map_embed' );
?>

<section class="page-hero page-hero--contact">
	<div class="container page-hero__content">
		<h1 class="page-hero__title"><?php echo esc_html( travelworld_get_setting( 'contact_hero_title' ) ); ?></h1>
		<p class="page-hero__subtitle"><?php echo esc_html( travelworld_get_setting( 'contact_hero_subtitle' ) ); ?></p>
	</div>
</section>

<section class="section section--contact-cards">
	<div class="container">
		<div class="contact-cards">
			<div class="contact-card">
				<div class="contact-card__icon"><?php echo travelworld_icon_svg( 'phone' ); ?></div>
				<h3><?php esc_html_e( 'Phone', 'travelworld' ); ?></h3>
				<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
				<?php if ( $phone2 ) : ?>
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone2 ) ); ?>"><?php echo esc_html( $phone2 ); ?></a></p>
				<?php endif; ?>
			</div>
			<div class="contact-card">
				<div class="contact-card__icon"><?php echo travelworld_icon_svg( 'email' ); ?></div>
				<h3><?php esc_html_e( 'Email', 'travelworld' ); ?></h3>
				<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
				<?php if ( $email2 ) : ?>
					<p><a href="mailto:<?php echo esc_attr( $email2 ); ?>"><?php echo esc_html( $email2 ); ?></a></p>
				<?php endif; ?>
			</div>
			<div class="contact-card">
				<div class="contact-card__icon"><?php echo travelworld_icon_svg( 'location' ); ?></div>
				<h3><?php esc_html_e( 'Office', 'travelworld' ); ?></h3>
				<p><?php echo esc_html( $address ); ?></p>
			</div>
			<div class="contact-card">
				<div class="contact-card__icon"><?php echo travelworld_icon_svg( 'clock' ); ?></div>
				<h3><?php esc_html_e( 'Working Hours', 'travelworld' ); ?></h3>
				<p><?php echo esc_html( travelworld_get_setting( 'contact_hours_weekday' ) ); ?></p>
				<p><?php echo esc_html( travelworld_get_setting( 'contact_hours_weekend' ) ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="section section--contact-form">
	<div class="container">
		<div class="contact-layout">
			<div class="contact-form-wrap">
				<h2><?php echo esc_html( travelworld_get_setting( 'contact_form_title' ) ); ?></h2>
				<p><?php echo esc_html( travelworld_get_setting( 'contact_form_intro' ) ); ?></p>
				<form id="contact-inquiry-form" class="inquiry-form inquiry-form--page">
					<div class="form-group">
						<label for="contact-name"><?php esc_html_e( 'Full Name', 'travelworld' ); ?></label>
						<input type="text" id="contact-name" name="name" placeholder="<?php esc_attr_e( 'John Doe', 'travelworld' ); ?>" required>
					</div>
					<div class="form-row">
						<div class="form-group">
							<label for="contact-email"><?php esc_html_e( 'Email', 'travelworld' ); ?></label>
							<input type="email" id="contact-email" name="email" placeholder="<?php esc_attr_e( 'john@example.com', 'travelworld' ); ?>" required>
						</div>
						<div class="form-group">
							<label for="contact-phone"><?php esc_html_e( 'Phone', 'travelworld' ); ?></label>
							<input type="tel" id="contact-phone" name="phone" placeholder="<?php esc_attr_e( '+1 234 567 890', 'travelworld' ); ?>" required>
						</div>
					</div>
					<div class="form-group">
						<label for="contact-date"><?php esc_html_e( 'Preferred Travel Date', 'travelworld' ); ?></label>
						<input type="date" id="contact-date" name="travel_date">
					</div>
					<div class="form-row">
						<div class="form-group">
							<label for="contact-adults"><?php esc_html_e( 'Number of Adults', 'travelworld' ); ?></label>
							<input type="number" id="contact-adults" name="adults" value="2" min="1">
						</div>
						<div class="form-group">
							<label for="contact-children"><?php esc_html_e( 'Number of Children', 'travelworld' ); ?></label>
							<input type="number" id="contact-children" name="children" value="0" min="0">
						</div>
					</div>
					<div class="form-group">
						<label><?php esc_html_e( 'Trip Type Preference', 'travelworld' ); ?></label>
						<div class="trip-type-pills">
							<label class="pill"><input type="radio" name="trip_type" value="budget"><span><?php esc_html_e( 'Budget Friendly', 'travelworld' ); ?></span></label>
							<label class="pill"><input type="radio" name="trip_type" value="standard" checked><span><?php esc_html_e( 'Standard', 'travelworld' ); ?></span></label>
							<label class="pill"><input type="radio" name="trip_type" value="luxury"><span><?php esc_html_e( 'Luxury', 'travelworld' ); ?></span></label>
							<label class="pill"><input type="radio" name="trip_type" value="family"><span><?php esc_html_e( 'Family Oriented', 'travelworld' ); ?></span></label>
						</div>
					</div>
					<div class="form-group">
						<label for="contact-requirements"><?php esc_html_e( 'Requirements & Special Requests', 'travelworld' ); ?></label>
						<textarea id="contact-requirements" name="requirements" rows="4" placeholder="<?php esc_attr_e( 'Tell us about your dream vacation, dietary needs, accessibility requirements, or any special occasions...', 'travelworld' ); ?>"></textarea>
					</div>
					<button type="submit" class="btn btn-primary btn-block contact-submit-btn">
						<?php esc_html_e( 'Send Inquiry', 'travelworld' ); ?>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
					</button>
					<div class="form-message" id="contact-message"></div>
				</form>
			</div>
			<div class="contact-sidebar">
				<div class="contact-map">
					<h3><?php echo esc_html( travelworld_get_setting( 'contact_map_title', 'Our Location' ) ); ?></h3>
					<?php if ( travelworld_get_setting( 'contact_map_intro' ) ) : ?>
						<p class="contact-map__intro"><?php echo esc_html( travelworld_get_setting( 'contact_map_intro' ) ); ?></p>
					<?php endif; ?>
					<div class="map-placeholder">
						<?php if ( $map_embed ) : ?>
							<iframe src="<?php echo esc_url( $map_embed ); ?>" width="100%" height="280" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Office location map', 'travelworld' ); ?>"></iframe>
						<?php endif; ?>
					</div>
				</div>
				<div class="quick-contact">
					<h3><?php esc_html_e( 'Quick Contact', 'travelworld' ); ?></h3>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="quick-contact__item">
						<span class="quick-contact__icon"><?php echo travelworld_icon_svg( 'phone' ); ?></span>
						<div>
							<strong><?php esc_html_e( 'Call Us', 'travelworld' ); ?></strong>
							<span><?php echo esc_html( $phone ); ?></span>
						</div>
					</a>
					<a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>" class="quick-contact__item" target="_blank" rel="noopener">
						<span class="quick-contact__icon quick-contact__icon--whatsapp"><?php echo travelworld_icon_svg( 'whatsapp' ); ?></span>
						<div>
							<strong><?php esc_html_e( 'WhatsApp', 'travelworld' ); ?></strong>
							<span><?php esc_html_e( 'Chat with us', 'travelworld' ); ?></span>
						</div>
					</a>
					<a href="mailto:<?php echo esc_attr( $email ); ?>" class="quick-contact__item">
						<span class="quick-contact__icon"><?php echo travelworld_icon_svg( 'email' ); ?></span>
						<div>
							<strong><?php esc_html_e( 'Email Us', 'travelworld' ); ?></strong>
							<span><?php echo esc_html( $email ); ?></span>
						</div>
					</a>
				</div>
				<div class="emergency-support">
					<h3><?php esc_html_e( '24/7 Emergency Support', 'travelworld' ); ?></h3>
					<p><?php echo esc_html( travelworld_get_setting( 'contact_emergency_text' ) ); ?></p>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="btn btn-white btn-sm emergency-btn">
						<?php echo travelworld_icon_svg( 'phone' ); ?>
						<?php esc_html_e( 'Emergency Hotline', 'travelworld' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
