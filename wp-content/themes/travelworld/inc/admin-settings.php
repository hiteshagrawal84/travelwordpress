<?php
/**
 * Theme admin settings
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default settings values.
 */
function travelworld_default_settings() {
	return array(
		'topbar_enabled'        => '1',
		'topbar_phone'          => '+1 (234) 567-890',
		'topbar_show_phone'     => '1',
		'topbar_email'          => 'info@createswowtech.com',
		'topbar_show_email'     => '1',
		'topbar_support_text'   => '24/7 Customer Support',
		'topbar_show_support'   => '1',
		'phone'                 => '+1 (234) 567-890',
		'phone2'                => '+1 (234) 567-891',
		'email'                 => 'info@createswowtech.com',
		'email2'                => 'support@createswowtech.com',
		'address'               => '123 Travel Street, New York, NY 10001',
		'whatsapp'              => '+1234567890',
		'support_text'          => '24/7 Customer Support',
		'about_text'            => 'Making international travel accessible and stress-free for over 15 years.',
		'contact_hero_title'    => 'Contact Us',
		'contact_hero_subtitle' => 'Get in touch with our travel experts',
		'contact_hours_weekday' => 'Mon - Fri: 9:00 AM - 6:00 PM',
		'contact_hours_weekend' => 'Sat - Sun: 10:00 AM - 4:00 PM',
		'contact_form_title'    => 'Send Us a Message',
		'contact_form_intro'    => 'Fill out the form below and our travel experts will get back to you within 24 hours.',
		'contact_map_title'     => 'Our Location',
		'contact_map_intro'     => 'Visit our office or reach out — we are here to help plan your perfect trip.',
		'contact_map_embed'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878459418!3d40.74844097932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1635959222400!5m2!1sen!2sus',
		'contact_emergency_text'=> 'Need urgent assistance during your trip? We\'re always here for you.',
		'blog_enabled'          => '1',
		'blog_title'            => 'Travel Tips & Stories',
		'blog_subtitle'         => 'Inspiration and advice from our travel experts',
		'blog_posts_count'      => '3',
	);
}

/**
 * Get a single theme setting.
 */
function travelworld_get_setting( $key, $default = '' ) {
	$settings  = get_option( 'travelworld_settings', array() );
	$defaults  = travelworld_default_settings();

	if ( isset( $settings[ $key ] ) && $settings[ $key ] !== '' ) {
		return $settings[ $key ];
	}

	if ( isset( $defaults[ $key ] ) ) {
		return $defaults[ $key ];
	}

	$legacy = get_theme_mod( "travelworld_{$key}", '' );
	return $legacy !== '' ? $legacy : $default;
}

/**
 * Register admin menu.
 */
function travelworld_admin_menu() {
	add_theme_page(
		__( 'TravelWorld Settings', 'travelworld' ),
		__( 'Theme Settings', 'travelworld' ),
		'manage_options',
		'travelworld-settings',
		'travelworld_settings_page'
	);
}
add_action( 'admin_menu', 'travelworld_admin_menu' );

/**
 * Register settings.
 */
function travelworld_register_settings() {
	register_setting(
		'travelworld_settings_group',
		'travelworld_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'travelworld_sanitize_settings',
			'default'           => travelworld_default_settings(),
		)
	);
}
add_action( 'admin_init', 'travelworld_register_settings' );

/**
 * Sanitize settings input.
 */
function travelworld_sanitize_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return travelworld_default_settings();
	}

	$defaults = travelworld_default_settings();
	$output   = array();

	$checkboxes = array(
		'topbar_enabled',
		'topbar_show_phone',
		'topbar_show_email',
		'topbar_show_support',
		'blog_enabled',
	);

	foreach ( $defaults as $key => $default ) {
		if ( in_array( $key, $checkboxes, true ) ) {
			$output[ $key ] = ! empty( $input[ $key ] ) ? '1' : '0';
		} elseif ( 'contact_map_embed' === $key ) {
			$output[ $key ] = esc_url_raw( $input[ $key ] ?? $default );
		} elseif ( in_array( $key, array( 'contact_form_intro', 'contact_map_intro', 'contact_emergency_text', 'about_text', 'blog_subtitle' ), true ) ) {
			$output[ $key ] = sanitize_textarea_field( $input[ $key ] ?? $default );
		} elseif ( 'blog_posts_count' === $key ) {
			$output[ $key ] = (string) max( 1, min( 6, absint( $input[ $key ] ?? 3 ) ) );
		} else {
			$output[ $key ] = sanitize_text_field( $input[ $key ] ?? $default );
		}
	}

	return $output;
}

/**
 * Settings page render.
 */
function travelworld_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = wp_parse_args( get_option( 'travelworld_settings', array() ), travelworld_default_settings() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'TravelWorld Theme Settings', 'travelworld' ); ?></h1>

		<?php if ( ! travelworld_is_sample_data_imported() ) : ?>
			<div class="notice notice-warning inline" style="margin:15px 0;padding:12px;">
				<p>
					<?php esc_html_e( 'Sample data has not been imported yet.', 'travelworld' ); ?>
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=travelworld-import' ) ); ?>" class="button button-primary" style="margin-left:8px;">
						<?php esc_html_e( 'Import Sample Data', 'travelworld' ); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'travelworld_settings_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'Top Bar', 'travelworld' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Configure the dark contact bar shown at the top of every page.', 'travelworld' ); ?></p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable Top Bar', 'travelworld' ); ?></th>
					<td><label><input type="checkbox" name="travelworld_settings[topbar_enabled]" value="1" <?php checked( $settings['topbar_enabled'], '1' ); ?>> <?php esc_html_e( 'Show top bar', 'travelworld' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Phone Number', 'travelworld' ); ?></th>
					<td>
						<input type="text" name="travelworld_settings[topbar_phone]" value="<?php echo esc_attr( $settings['topbar_phone'] ); ?>" class="regular-text">
						<label style="margin-left:12px;"><input type="checkbox" name="travelworld_settings[topbar_show_phone]" value="1" <?php checked( $settings['topbar_show_phone'], '1' ); ?>> <?php esc_html_e( 'Show in top bar', 'travelworld' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Email Address', 'travelworld' ); ?></th>
					<td>
						<input type="email" name="travelworld_settings[topbar_email]" value="<?php echo esc_attr( $settings['topbar_email'] ); ?>" class="regular-text">
						<label style="margin-left:12px;"><input type="checkbox" name="travelworld_settings[topbar_show_email]" value="1" <?php checked( $settings['topbar_show_email'], '1' ); ?>> <?php esc_html_e( 'Show in top bar', 'travelworld' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Support Text', 'travelworld' ); ?></th>
					<td>
						<input type="text" name="travelworld_settings[topbar_support_text]" value="<?php echo esc_attr( $settings['topbar_support_text'] ); ?>" class="regular-text">
						<label style="margin-left:12px;"><input type="checkbox" name="travelworld_settings[topbar_show_support]" value="1" <?php checked( $settings['topbar_show_support'], '1' ); ?>> <?php esc_html_e( 'Show in top bar', 'travelworld' ); ?></label>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Contact Page', 'travelworld' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Hero Title', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[contact_hero_title]" value="<?php echo esc_attr( $settings['contact_hero_title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Hero Subtitle', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[contact_hero_subtitle]" value="<?php echo esc_attr( $settings['contact_hero_subtitle'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Primary Phone', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[phone]" value="<?php echo esc_attr( $settings['phone'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Secondary Phone', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[phone2]" value="<?php echo esc_attr( $settings['phone2'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Primary Email', 'travelworld' ); ?></th>
					<td><input type="email" name="travelworld_settings[email]" value="<?php echo esc_attr( $settings['email'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Secondary Email', 'travelworld' ); ?></th>
					<td><input type="email" name="travelworld_settings[email2]" value="<?php echo esc_attr( $settings['email2'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Office Address', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[address]" value="<?php echo esc_attr( $settings['address'] ); ?>" class="large-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'WhatsApp Number', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[whatsapp]" value="<?php echo esc_attr( $settings['whatsapp'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Weekday Hours', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[contact_hours_weekday]" value="<?php echo esc_attr( $settings['contact_hours_weekday'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Weekend Hours', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[contact_hours_weekend]" value="<?php echo esc_attr( $settings['contact_hours_weekend'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Form Title', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[contact_form_title]" value="<?php echo esc_attr( $settings['contact_form_title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Form Intro', 'travelworld' ); ?></th>
					<td><textarea name="travelworld_settings[contact_form_intro]" rows="3" class="large-text"><?php echo esc_textarea( $settings['contact_form_intro'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Map Embed URL', 'travelworld' ); ?></th>
					<td><input type="url" name="travelworld_settings[contact_map_embed]" value="<?php echo esc_attr( $settings['contact_map_embed'] ); ?>" class="large-text"><p class="description"><?php esc_html_e( 'Google Maps embed URL (src attribute only).', 'travelworld' ); ?></p></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Emergency Support Text', 'travelworld' ); ?></th>
					<td><textarea name="travelworld_settings[contact_emergency_text]" rows="2" class="large-text"><?php echo esc_textarea( $settings['contact_emergency_text'] ); ?></textarea></td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Homepage Blog Section', 'travelworld' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable Blog Section', 'travelworld' ); ?></th>
					<td><label><input type="checkbox" name="travelworld_settings[blog_enabled]" value="1" <?php checked( $settings['blog_enabled'], '1' ); ?>> <?php esc_html_e( 'Show blog section after testimonials', 'travelworld' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Section Title', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[blog_title]" value="<?php echo esc_attr( $settings['blog_title'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Section Subtitle', 'travelworld' ); ?></th>
					<td><input type="text" name="travelworld_settings[blog_subtitle]" value="<?php echo esc_attr( $settings['blog_subtitle'] ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Number of Posts', 'travelworld' ); ?></th>
					<td><input type="number" name="travelworld_settings[blog_posts_count]" value="<?php echo esc_attr( $settings['blog_posts_count'] ); ?>" min="1" max="6" class="small-text"></td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'General', 'travelworld' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Footer About Text', 'travelworld' ); ?></th>
					<td><textarea name="travelworld_settings[about_text]" rows="2" class="large-text"><?php echo esc_textarea( $settings['about_text'] ); ?></textarea></td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Set defaults on theme activation.
 */
function travelworld_activate_settings() {
	if ( ! get_option( 'travelworld_settings' ) ) {
		update_option( 'travelworld_settings', travelworld_default_settings() );
	}
}
add_action( 'after_switch_theme', 'travelworld_activate_settings' );
