<?php
/**
 * Meta Boxes for Destinations and Tour Packages
 *
 * @package TravelWorld
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function travelworld_add_meta_boxes() {
	add_meta_box(
		'destination_details',
		__( 'Destination Details', 'travelworld' ),
		'travelworld_destination_meta_box',
		'destination',
		'normal',
		'high'
	);

	add_meta_box(
		'package_details',
		__( 'Package Details', 'travelworld' ),
		'travelworld_package_meta_box',
		'tour_package',
		'normal',
		'high'
	);

	add_meta_box(
		'package_itinerary',
		__( 'Day-by-Day Itinerary', 'travelworld' ),
		'travelworld_itinerary_meta_box',
		'tour_package',
		'normal',
		'default'
	);

	add_meta_box(
		'package_inclusions',
		__( 'Inclusions & Exclusions', 'travelworld' ),
		'travelworld_inclusions_meta_box',
		'tour_package',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'travelworld_add_meta_boxes' );

function travelworld_destination_meta_box( $post ) {
	wp_nonce_field( 'travelworld_save_meta', 'travelworld_meta_nonce' );
	$starting_price = get_post_meta( $post->ID, '_starting_price', true );
	$tagline        = get_post_meta( $post->ID, '_tagline', true );
	?>
	<p>
		<label for="tagline"><strong><?php esc_html_e( 'Tagline', 'travelworld' ); ?></strong></label><br>
		<input type="text" id="tagline" name="tagline" value="<?php echo esc_attr( $tagline ); ?>" class="widefat" placeholder="Tropical paradise with pristine beaches">
	</p>
	<p>
		<label for="starting_price"><strong><?php esc_html_e( 'Starting Price ($)', 'travelworld' ); ?></strong></label><br>
		<input type="number" id="starting_price" name="starting_price" value="<?php echo esc_attr( $starting_price ); ?>" class="widefat" min="0" step="1">
	</p>
	<?php
}

function travelworld_package_meta_box( $post ) {
	wp_nonce_field( 'travelworld_save_meta', 'travelworld_meta_nonce' );
	$fields = array(
		'destination_id' => get_post_meta( $post->ID, '_destination_id', true ),
		'price'          => get_post_meta( $post->ID, '_price', true ),
		'original_price' => get_post_meta( $post->ID, '_original_price', true ),
		'duration'       => get_post_meta( $post->ID, '_duration', true ),
		'meal_plan'      => get_post_meta( $post->ID, '_meal_plan', true ),
		'hotel_rating'   => get_post_meta( $post->ID, '_hotel_rating', true ),
		'hotel_name'     => get_post_meta( $post->ID, '_hotel_name', true ),
		'rating'         => get_post_meta( $post->ID, '_rating', true ),
		'review_count'   => get_post_meta( $post->ID, '_review_count', true ),
		'highlights'     => get_post_meta( $post->ID, '_highlights', true ),
		'trending'       => get_post_meta( $post->ID, '_trending', true ),
	);

	$destinations = get_posts( array(
		'post_type'      => 'destination',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );
	?>
	<table class="form-table">
		<tr>
			<th><label for="destination_id"><?php esc_html_e( 'Destination', 'travelworld' ); ?></label></th>
			<td>
				<select name="destination_id" id="destination_id" class="widefat">
					<option value=""><?php esc_html_e( 'Select Destination', 'travelworld' ); ?></option>
					<?php foreach ( $destinations as $dest ) : ?>
						<option value="<?php echo esc_attr( $dest->ID ); ?>" <?php selected( $fields['destination_id'], $dest->ID ); ?>>
							<?php echo esc_html( $dest->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="price"><?php esc_html_e( 'Price ($)', 'travelworld' ); ?></label></th>
			<td><input type="number" name="price" id="price" value="<?php echo esc_attr( $fields['price'] ); ?>" class="regular-text" min="0"></td>
		</tr>
		<tr>
			<th><label for="original_price"><?php esc_html_e( 'Original Price ($)', 'travelworld' ); ?></label></th>
			<td><input type="number" name="original_price" id="original_price" value="<?php echo esc_attr( $fields['original_price'] ); ?>" class="regular-text" min="0"></td>
		</tr>
		<tr>
			<th><label for="duration"><?php esc_html_e( 'Duration', 'travelworld' ); ?></label></th>
			<td><input type="text" name="duration" id="duration" value="<?php echo esc_attr( $fields['duration'] ); ?>" class="regular-text" placeholder="7 Days / 6 Nights"></td>
		</tr>
		<tr>
			<th><label for="meal_plan"><?php esc_html_e( 'Meal Plan', 'travelworld' ); ?></label></th>
			<td><input type="text" name="meal_plan" id="meal_plan" value="<?php echo esc_attr( $fields['meal_plan'] ); ?>" class="regular-text" placeholder="Breakfast & Dinner"></td>
		</tr>
		<tr>
			<th><label for="hotel_rating"><?php esc_html_e( 'Hotel Rating', 'travelworld' ); ?></label></th>
			<td><input type="text" name="hotel_rating" id="hotel_rating" value="<?php echo esc_attr( $fields['hotel_rating'] ); ?>" class="regular-text" placeholder="5-Star"></td>
		</tr>
		<tr>
			<th><label for="hotel_name"><?php esc_html_e( 'Hotel Name', 'travelworld' ); ?></label></th>
			<td><input type="text" name="hotel_name" id="hotel_name" value="<?php echo esc_attr( $fields['hotel_name'] ); ?>" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="rating"><?php esc_html_e( 'Rating', 'travelworld' ); ?></label></th>
			<td><input type="number" name="rating" id="rating" value="<?php echo esc_attr( $fields['rating'] ); ?>" class="small-text" min="0" max="5" step="0.1"></td>
		</tr>
		<tr>
			<th><label for="review_count"><?php esc_html_e( 'Review Count', 'travelworld' ); ?></label></th>
			<td><input type="number" name="review_count" id="review_count" value="<?php echo esc_attr( $fields['review_count'] ); ?>" class="small-text" min="0"></td>
		</tr>
		<tr>
			<th><label for="highlights"><?php esc_html_e( 'Highlights (comma-separated)', 'travelworld' ); ?></label></th>
			<td><input type="text" name="highlights" id="highlights" value="<?php echo esc_attr( $fields['highlights'] ); ?>" class="widefat" placeholder="Beach resort stay, Water sports, Island tours"></td>
		</tr>
		<tr>
			<th><label for="trending"><?php esc_html_e( 'Show in Trending', 'travelworld' ); ?></label></th>
			<td><input type="checkbox" name="trending" id="trending" value="1" <?php checked( $fields['trending'], '1' ); ?>></td>
		</tr>
	</table>
	<?php
}

function travelworld_itinerary_meta_box( $post ) {
	$itinerary = get_post_meta( $post->ID, '_itinerary', true );
	if ( ! is_array( $itinerary ) ) {
		$itinerary = array();
	}
	?>
	<div id="itinerary-fields">
		<?php
		if ( empty( $itinerary ) ) {
			$itinerary = array( array( 'title' => '', 'description' => '', 'activities' => '' ) );
		}
		foreach ( $itinerary as $i => $day ) :
			?>
			<div class="itinerary-day" style="border:1px solid #ddd;padding:12px;margin-bottom:10px;background:#f9f9f9;">
				<p><strong><?php printf( esc_html__( 'Day %d', 'travelworld' ), $i + 1 ); ?></strong></p>
				<p>
					<label><?php esc_html_e( 'Title', 'travelworld' ); ?></label><br>
					<input type="text" name="itinerary[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $day['title'] ?? '' ); ?>" class="widefat">
				</p>
				<p>
					<label><?php esc_html_e( 'Description', 'travelworld' ); ?></label><br>
					<textarea name="itinerary[<?php echo esc_attr( $i ); ?>][description]" class="widefat" rows="2"><?php echo esc_textarea( $day['description'] ?? '' ); ?></textarea>
				</p>
				<p>
					<label><?php esc_html_e( 'Activities (one per line)', 'travelworld' ); ?></label><br>
					<textarea name="itinerary[<?php echo esc_attr( $i ); ?>][activities]" class="widefat" rows="3"><?php echo esc_textarea( $day['activities'] ?? '' ); ?></textarea>
				</p>
			</div>
		<?php endforeach; ?>
	</div>
	<p><em><?php esc_html_e( 'Add up to 10 days. Empty days at the end will be ignored.', 'travelworld' ); ?></em></p>
	<?php
	for ( $j = count( $itinerary ); $j < 10; $j++ ) :
		?>
		<div class="itinerary-day-hidden" style="display:none;">
			<input type="hidden" name="itinerary[<?php echo esc_attr( $j ); ?>][title]" value="">
			<input type="hidden" name="itinerary[<?php echo esc_attr( $j ); ?>][description]" value="">
			<input type="hidden" name="itinerary[<?php echo esc_attr( $j ); ?>][activities]" value="">
		</div>
	<?php endfor; ?>
	<?php
}

function travelworld_inclusions_meta_box( $post ) {
	$inclusions = get_post_meta( $post->ID, '_inclusions', true );
	$exclusions = get_post_meta( $post->ID, '_exclusions', true );
	?>
	<p>
		<label><strong><?php esc_html_e( 'Inclusions (one per line)', 'travelworld' ); ?></strong></label><br>
		<textarea name="inclusions" class="widefat" rows="6"><?php echo esc_textarea( $inclusions ); ?></textarea>
	</p>
	<p>
		<label><strong><?php esc_html_e( 'Exclusions (one per line)', 'travelworld' ); ?></strong></label><br>
		<textarea name="exclusions" class="widefat" rows="6"><?php echo esc_textarea( $exclusions ); ?></textarea>
	</p>
	<?php
}

function travelworld_save_meta( $post_id ) {
	if ( ! isset( $_POST['travelworld_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['travelworld_meta_nonce'] ) ), 'travelworld_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );

	if ( 'destination' === $post_type ) {
		if ( isset( $_POST['tagline'] ) ) {
			update_post_meta( $post_id, '_tagline', sanitize_text_field( wp_unslash( $_POST['tagline'] ) ) );
		}
		if ( isset( $_POST['starting_price'] ) ) {
			update_post_meta( $post_id, '_starting_price', absint( $_POST['starting_price'] ) );
		}
	}

	if ( 'tour_package' === $post_type ) {
		$text_fields = array( 'destination_id', 'duration', 'meal_plan', 'hotel_rating', 'hotel_name', 'highlights' );
		$num_fields  = array( 'price', 'original_price', 'rating', 'review_count' );

		foreach ( $text_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, "_{$field}", sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
		foreach ( $num_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, "_{$field}", sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
		update_post_meta( $post_id, '_trending', isset( $_POST['trending'] ) ? '1' : '0' );

		if ( isset( $_POST['itinerary'] ) && is_array( $_POST['itinerary'] ) ) {
			$days = array();
			foreach ( $_POST['itinerary'] as $day ) {
				$title = sanitize_text_field( $day['title'] ?? '' );
				if ( empty( $title ) ) {
					continue;
				}
				$days[] = array(
					'title'       => $title,
					'description' => sanitize_textarea_field( $day['description'] ?? '' ),
					'activities'  => sanitize_textarea_field( $day['activities'] ?? '' ),
				);
			}
			update_post_meta( $post_id, '_itinerary', $days );
		}

		if ( isset( $_POST['inclusions'] ) ) {
			update_post_meta( $post_id, '_inclusions', sanitize_textarea_field( wp_unslash( $_POST['inclusions'] ) ) );
		}
		if ( isset( $_POST['exclusions'] ) ) {
			update_post_meta( $post_id, '_exclusions', sanitize_textarea_field( wp_unslash( $_POST['exclusions'] ) ) );
		}
	}
}
add_action( 'save_post', 'travelworld_save_meta' );
