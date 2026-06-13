<?php
/**
 * Template helper functions
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function travelworld_get_contact( $key ) {
	return travelworld_get_setting( $key );
}

function travelworld_format_price( $price ) {
	return '$' . number_format( (float) $price );
}

function travelworld_get_savings_percent( $price, $original ) {
	if ( ! $original || $original <= $price ) {
		return 0;
	}
	return round( ( ( $original - $price ) / $original ) * 100 );
}

function travelworld_star_rating( $rating ) {
	$rating = (float) $rating;
	$output = '<span class="stars" aria-label="' . esc_attr( sprintf( __( '%s out of 5 stars', 'travelworld' ), $rating ) ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$output .= $i <= floor( $rating ) ? '★' : ( $i - $rating < 1 ? '★' : '☆' );
	}
	$output .= '</span>';
	return $output;
}

function travelworld_get_packages_by_destination( $destination_id, $limit = -1 ) {
	return get_posts( array(
		'post_type'      => 'tour_package',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			array(
				'key'   => '_destination_id',
				'value' => $destination_id,
			),
		),
	) );
}

function travelworld_get_trending_packages( $limit = 3 ) {
	return get_posts( array(
		'post_type'      => 'tour_package',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			array(
				'key'   => '_trending',
				'value' => '1',
			),
		),
	) );
}

function travelworld_get_package_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, "_{$key}", true );
	return $value !== '' ? $value : $default;
}

function travelworld_get_highlights( $post_id ) {
	$highlights = travelworld_get_package_meta( $post_id, 'highlights' );
	if ( empty( $highlights ) ) {
		return array();
	}
	return array_map( 'trim', explode( ',', $highlights ) );
}

function travelworld_get_itinerary( $post_id ) {
	$itinerary = get_post_meta( $post_id, '_itinerary', true );
	return is_array( $itinerary ) ? $itinerary : array();
}

function travelworld_get_package_gallery( $post_id ) {
	$gallery = get_post_meta( $post_id, '_gallery_images', true );
	if ( is_array( $gallery ) && ! empty( $gallery ) ) {
		return $gallery;
	}
	if ( is_string( $gallery ) && ! empty( $gallery ) ) {
		return array_map( 'trim', explode( ',', $gallery ) );
	}
	$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( $thumb ) {
		return array( $thumb );
	}
	return array();
}

function travelworld_get_list_items( $text ) {
	if ( empty( $text ) ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $text );
	return array_filter( array_map( 'trim', $lines ) );
}

function travelworld_icon_svg( $name ) {
	$icons = array(
		'phone'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>',
		'email'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'clock'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'utensils' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 2v7c0 1.1.9 2 2 2h0a2 2 0 002-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 00-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></svg>',
		'building' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/></svg>',
		'plane'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 00-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>',
		'location' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>',
		'arrow-left' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>',
		'check'    => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
		'x'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
		'whatsapp' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
	);
	return $icons[ $name ] ?? '';
}

function travelworld_render_package_card( $post_id, $layout = 'grid' ) {
	$price          = travelworld_get_package_meta( $post_id, 'price' );
	$original_price = travelworld_get_package_meta( $post_id, 'original_price' );
	$duration       = travelworld_get_package_meta( $post_id, 'duration' );
	$rating         = travelworld_get_package_meta( $post_id, 'rating', '4.8' );
	$review_count   = travelworld_get_package_meta( $post_id, 'review_count', '124' );
	$meal_plan      = travelworld_get_package_meta( $post_id, 'meal_plan' );
	$hotel_rating   = travelworld_get_package_meta( $post_id, 'hotel_rating' );
	$highlights     = travelworld_get_highlights( $post_id );
	$savings        = travelworld_get_savings_percent( $price, $original_price );
	$permalink      = get_permalink( $post_id );
	$title          = get_the_title( $post_id );

	if ( 'list' === $layout ) :
		?>
		<article class="package-card package-card--list">
			<div class="package-card__images">
				<?php if ( has_post_thumbnail( $post_id ) ) : ?>
					<?php echo get_the_post_thumbnail( $post_id, 'package-card', array( 'class' => 'package-card__img' ) ); ?>
				<?php else : ?>
					<div class="package-card__img package-card__img--placeholder"></div>
				<?php endif; ?>
			</div>
			<div class="package-card__body">
				<h2 class="package-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h2>
				<div class="package-card__rating">
					<?php echo travelworld_star_rating( $rating ); ?>
					<span><?php echo esc_html( $rating ); ?> (<?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'travelworld' ); ?>)</span>
				</div>
				<div class="package-card__meta">
					<?php if ( $duration ) : ?>
						<span><?php echo travelworld_icon_svg( 'clock' ); ?> <?php echo esc_html( $duration ); ?></span>
					<?php endif; ?>
					<?php if ( $meal_plan ) : ?>
						<span><?php echo travelworld_icon_svg( 'utensils' ); ?> <?php echo esc_html( $meal_plan ); ?></span>
					<?php endif; ?>
					<?php if ( $hotel_rating ) : ?>
						<span><?php echo travelworld_icon_svg( 'building' ); ?> <?php echo esc_html( $hotel_rating ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $highlights ) ) : ?>
					<div class="package-card__highlights">
						<?php foreach ( $highlights as $highlight ) : ?>
							<span class="highlight-tag"><?php echo esc_html( $highlight ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="package-card__pricing">
				<?php if ( $original_price ) : ?>
					<span class="package-card__original"><?php echo esc_html( travelworld_format_price( $original_price ) ); ?></span>
				<?php endif; ?>
				<span class="package-card__price"><?php echo esc_html( travelworld_format_price( $price ) ); ?></span>
				<span class="package-card__per-person"><?php esc_html_e( 'per person', 'travelworld' ); ?></span>
				<a href="<?php echo esc_url( $permalink ); ?>" class="btn btn-primary btn-block"><?php esc_html_e( 'View Full Itinerary', 'travelworld' ); ?></a>
				<button type="button" class="btn btn-outline btn-block inquiry-trigger" data-package="<?php echo esc_attr( $title ); ?>"><?php esc_html_e( 'Inquiry Now', 'travelworld' ); ?></button>
			</div>
		</article>
		<?php
	else :
		?>
		<article class="package-card package-card--grid">
			<div class="package-card__image-wrap">
				<?php if ( has_post_thumbnail( $post_id ) ) : ?>
					<?php echo get_the_post_thumbnail( $post_id, 'package-card', array( 'class' => 'package-card__img' ) ); ?>
				<?php else : ?>
					<div class="package-card__img package-card__img--placeholder"></div>
				<?php endif; ?>
				<?php if ( $savings > 0 ) : ?>
					<span class="package-card__badge"><?php printf( esc_html__( 'Save %d%%', 'travelworld' ), $savings ); ?></span>
				<?php endif; ?>
			</div>
			<div class="package-card__content">
				<div class="package-card__rating">
					<?php echo travelworld_star_rating( $rating ); ?>
					<span><?php echo esc_html( $rating ); ?></span>
				</div>
				<h3 class="package-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
				<?php if ( $duration ) : ?>
					<p class="package-card__duration"><?php echo esc_html( $duration ); ?></p>
				<?php endif; ?>
				<div class="package-card__price-row">
					<?php if ( $original_price ) : ?>
						<span class="package-card__original"><?php echo esc_html( travelworld_format_price( $original_price ) ); ?></span>
					<?php endif; ?>
					<span class="package-card__price"><?php echo esc_html( travelworld_format_price( $price ) ); ?></span>
				</div>
			</div>
		</article>
		<?php
	endif;
}

function travelworld_render_destination_card( $post_id ) {
	$tagline        = get_post_meta( $post_id, '_tagline', true );
	$starting_price = get_post_meta( $post_id, '_starting_price', true );
	$permalink      = get_permalink( $post_id );
	$title          = get_the_title( $post_id );
	?>
	<article class="destination-card">
		<a href="<?php echo esc_url( $permalink ); ?>" class="destination-card__link">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $post_id, 'destination-card', array( 'class' => 'destination-card__img' ) ); ?>
			<?php else : ?>
				<div class="destination-card__img destination-card__img--placeholder"></div>
			<?php endif; ?>
			<div class="destination-card__overlay">
				<span class="destination-card__view-btn">
					<?php echo travelworld_icon_svg( 'location' ); ?>
					<?php esc_html_e( 'View Packages', 'travelworld' ); ?>
				</span>
				<div class="destination-card__info">
					<h2 class="destination-card__title"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $tagline ) : ?>
						<p class="destination-card__tagline"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $starting_price ) : ?>
					<div class="destination-card__price">
						<span><?php esc_html_e( 'Starting from', 'travelworld' ); ?></span>
						<strong><?php echo esc_html( travelworld_format_price( $starting_price ) ); ?></strong>
					</div>
				<?php endif; ?>
			</div>
		</a>
	</article>
	<?php
}

function travelworld_render_inquiry_modal() {
	?>
	<div id="inquiry-modal" class="modal" aria-hidden="true">
		<div class="modal__backdrop"></div>
		<div class="modal__dialog" role="dialog" aria-labelledby="inquiry-modal-title">
			<button type="button" class="modal__close" aria-label="<?php esc_attr_e( 'Close', 'travelworld' ); ?>">&times;</button>
			<h2 id="inquiry-modal-title" class="modal__title"><?php esc_html_e( 'Send Inquiry', 'travelworld' ); ?></h2>
			<form id="inquiry-form" class="inquiry-form">
				<input type="hidden" name="package" id="inquiry-package" value="">
				<div class="form-group">
					<label for="inquiry-name"><?php esc_html_e( 'Full Name', 'travelworld' ); ?></label>
					<input type="text" id="inquiry-name" name="name" required>
				</div>
				<div class="form-group">
					<label for="inquiry-email"><?php esc_html_e( 'Email', 'travelworld' ); ?></label>
					<input type="email" id="inquiry-email" name="email" required>
				</div>
				<div class="form-group">
					<label for="inquiry-phone"><?php esc_html_e( 'Phone', 'travelworld' ); ?></label>
					<input type="tel" id="inquiry-phone" name="phone" required>
				</div>
				<div class="form-group">
					<label for="inquiry-date"><?php esc_html_e( 'Preferred Travel Date', 'travelworld' ); ?></label>
					<input type="date" id="inquiry-date" name="travel_date">
				</div>
				<div class="form-row">
					<div class="form-group">
						<label for="inquiry-adults"><?php esc_html_e( 'Number of Adults', 'travelworld' ); ?></label>
						<input type="number" id="inquiry-adults" name="adults" value="2" min="1">
					</div>
					<div class="form-group">
						<label for="inquiry-children"><?php esc_html_e( 'Number of Children', 'travelworld' ); ?></label>
						<input type="number" id="inquiry-children" name="children" value="0" min="0">
					</div>
				</div>
				<div class="form-group">
					<label for="inquiry-trip-type"><?php esc_html_e( 'Trip Type', 'travelworld' ); ?></label>
					<select id="inquiry-trip-type" name="trip_type">
						<option value="budget"><?php esc_html_e( 'Budget Friendly', 'travelworld' ); ?></option>
						<option value="standard" selected><?php esc_html_e( 'Standard', 'travelworld' ); ?></option>
						<option value="luxury"><?php esc_html_e( 'Luxury', 'travelworld' ); ?></option>
						<option value="family"><?php esc_html_e( 'Family Oriented', 'travelworld' ); ?></option>
					</select>
				</div>
				<div class="form-group">
					<label for="inquiry-requirements"><?php esc_html_e( 'Special Requirements', 'travelworld' ); ?></label>
					<textarea id="inquiry-requirements" name="requirements" rows="3"></textarea>
				</div>
				<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Submit Inquiry', 'travelworld' ); ?></button>
				<div class="form-message" id="inquiry-message"></div>
			</form>
		</div>
	</div>
	<?php
}

function travelworld_render_blog_card( $post_id ) {
	$permalink = get_permalink( $post_id );
	$title     = get_the_title( $post_id );
	$excerpt   = get_the_excerpt( $post_id );
	$date      = get_the_date( 'M j, Y', $post_id );
	$categories = get_the_category( $post_id );
	$cat_name  = ! empty( $categories ) ? $categories[0]->name : __( 'Travel', 'travelworld' );
	?>
	<article class="blog-card">
		<a href="<?php echo esc_url( $permalink ); ?>" class="blog-card__image-link">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $post_id, 'package-card', array( 'class' => 'blog-card__img' ) ); ?>
			<?php else : ?>
				<div class="blog-card__img blog-card__img--placeholder"></div>
			<?php endif; ?>
			<span class="blog-card__category"><?php echo esc_html( $cat_name ); ?></span>
		</a>
		<div class="blog-card__content">
			<time class="blog-card__date" datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
			<h3 class="blog-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
			<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 18 ) ); ?></p>
			<a href="<?php echo esc_url( $permalink ); ?>" class="blog-card__link">
				<?php esc_html_e( 'Read More', 'travelworld' ); ?> &rarr;
			</a>
		</div>
	</article>
	<?php
}
