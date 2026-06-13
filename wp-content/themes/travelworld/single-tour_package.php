<?php
/**
 * Single Tour Package template
 *
 * @package TravelWorld
 */

get_header();

while ( have_posts() ) :
	the_post();
	$post_id        = get_the_ID();
	$price          = travelworld_get_package_meta( $post_id, 'price' );
	$original_price = travelworld_get_package_meta( $post_id, 'original_price' );
	$duration       = travelworld_get_package_meta( $post_id, 'duration' );
	$meal_plan      = travelworld_get_package_meta( $post_id, 'meal_plan' );
	$hotel_rating   = travelworld_get_package_meta( $post_id, 'hotel_rating' );
	$hotel_name     = travelworld_get_package_meta( $post_id, 'hotel_name' );
	$rating         = travelworld_get_package_meta( $post_id, 'rating', '4.8' );
	$review_count   = travelworld_get_package_meta( $post_id, 'review_count', '124' );
	$destination_id = travelworld_get_package_meta( $post_id, 'destination_id' );
	$itinerary      = travelworld_get_itinerary( $post_id );
	$inclusions     = travelworld_get_list_items( get_post_meta( $post_id, '_inclusions', true ) );
	$exclusions     = travelworld_get_list_items( get_post_meta( $post_id, '_exclusions', true ) );
	$savings        = travelworld_get_savings_percent( $price, $original_price );
	$back_url       = $destination_id ? get_permalink( $destination_id ) : get_permalink( get_page_by_path( 'tour-packages' ) );
	$whatsapp       = preg_replace( '/[^0-9]/', '', travelworld_get_contact( 'whatsapp' ) );
	$phone          = travelworld_get_contact( 'phone' );
	$gallery_images = travelworld_get_package_gallery( $post_id );
	?>
	<section class="section section--package-detail section--light">
		<div class="container">
			<a href="<?php echo esc_url( $back_url ); ?>" class="back-link">
				<?php echo travelworld_icon_svg( 'arrow-left' ); ?>
				<?php esc_html_e( 'Back to Packages', 'travelworld' ); ?>
			</a>

			<div class="package-detail">
				<div class="package-detail__main">
					<div class="package-gallery">
						<div class="package-gallery__main">
							<?php if ( ! empty( $gallery_images[0] ) ) : ?>
								<img src="<?php echo esc_url( $gallery_images[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
							<?php elseif ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large' ); ?>
							<?php else : ?>
								<div class="package-gallery__placeholder"></div>
							<?php endif; ?>
						</div>
						<div class="package-gallery__side">
							<div class="package-gallery__thumb">
								<?php if ( ! empty( $gallery_images[1] ) ) : ?>
									<img src="<?php echo esc_url( $gallery_images[1] ); ?>" alt="">
								<?php else : ?>
									<div class="package-gallery__placeholder"></div>
								<?php endif; ?>
							</div>
							<div class="package-gallery__thumb">
								<?php if ( ! empty( $gallery_images[2] ) ) : ?>
									<img src="<?php echo esc_url( $gallery_images[2] ); ?>" alt="">
								<?php else : ?>
									<div class="package-gallery__placeholder"></div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="package-info">
						<h1 class="package-info__title"><?php the_title(); ?></h1>
						<div class="package-info__rating">
							<?php echo travelworld_star_rating( $rating ); ?>
							<span class="package-info__rating-text">
								<strong><?php echo esc_html( $rating ); ?></strong>
								(<?php echo esc_html( $review_count ); ?> <?php esc_html_e( 'reviews', 'travelworld' ); ?>)
							</span>
						</div>
						<div class="package-info__meta">
							<?php if ( $duration ) : ?>
								<div class="package-info__meta-item">
									<span class="package-info__meta-icon"><?php echo travelworld_icon_svg( 'clock' ); ?></span>
									<span><?php echo esc_html( $duration ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $hotel_rating || $hotel_name ) : ?>
								<div class="package-info__meta-item">
									<span class="package-info__meta-icon"><?php echo travelworld_icon_svg( 'building' ); ?></span>
									<span><?php echo esc_html( trim( $hotel_rating . ' - ' . $hotel_name, ' -' ) ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $meal_plan ) : ?>
								<div class="package-info__meta-item">
									<span class="package-info__meta-icon"><?php echo travelworld_icon_svg( 'utensils' ); ?></span>
									<span><?php echo esc_html( $meal_plan ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( ! empty( $itinerary ) ) : ?>
						<div class="itinerary-section">
							<h2 class="itinerary-section__title"><?php esc_html_e( 'Day-by-Day Itinerary', 'travelworld' ); ?></h2>
							<div class="itinerary-accordion">
								<?php foreach ( $itinerary as $i => $day ) :
									$activities = travelworld_get_list_items( $day['activities'] ?? '' );
									$is_first   = 0 === $i;
									?>
									<div class="itinerary-day <?php echo $is_first ? 'is-open' : ''; ?>">
										<button class="itinerary-day__header" type="button" aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>">
											<span class="itinerary-day__number"><?php echo esc_html( $i + 1 ); ?></span>
											<span class="itinerary-day__info">
												<span class="itinerary-day__title"><?php echo esc_html( $day['title'] ); ?></span>
												<?php if ( ! empty( $day['description'] ) ) : ?>
													<span class="itinerary-day__summary"><?php echo esc_html( $day['description'] ); ?></span>
												<?php endif; ?>
											</span>
											<span class="itinerary-day__chevron" aria-hidden="true">
												<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
											</span>
										</button>
										<?php if ( ! empty( $activities ) ) : ?>
											<div class="itinerary-day__content" <?php echo $is_first ? '' : 'hidden'; ?>>
												<ul class="itinerary-day__activities">
													<?php foreach ( $activities as $activity ) : ?>
														<li><?php echo esc_html( $activity ); ?></li>
													<?php endforeach; ?>
												</ul>
											</div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $inclusions ) || ! empty( $exclusions ) ) : ?>
						<div class="inclusions-section">
							<?php if ( ! empty( $inclusions ) ) : ?>
								<div class="inclusions-box inclusions-box--included">
									<h3>
										<span class="inclusions-box__icon inclusions-box__icon--check"><?php echo travelworld_icon_svg( 'check' ); ?></span>
										<?php esc_html_e( 'Inclusions', 'travelworld' ); ?>
									</h3>
									<ul>
										<?php foreach ( $inclusions as $item ) : ?>
											<li>
												<span class="inclusions-box__item-icon"><?php echo travelworld_icon_svg( 'check' ); ?></span>
												<?php echo esc_html( $item ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $exclusions ) ) : ?>
								<div class="inclusions-box inclusions-box--excluded">
									<h3>
										<span class="inclusions-box__icon inclusions-box__icon--x"><?php echo travelworld_icon_svg( 'x' ); ?></span>
										<?php esc_html_e( 'Exclusions', 'travelworld' ); ?>
									</h3>
									<ul>
										<?php foreach ( $exclusions as $item ) : ?>
											<li>
												<span class="inclusions-box__item-icon"><?php echo travelworld_icon_svg( 'x' ); ?></span>
												<?php echo esc_html( $item ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<aside class="package-detail__sidebar">
					<div class="booking-card">
						<?php if ( $original_price ) : ?>
							<span class="booking-card__original"><?php echo esc_html( travelworld_format_price( $original_price ) ); ?></span>
						<?php endif; ?>
						<div class="booking-card__price">
							<span class="booking-card__amount"><?php echo esc_html( travelworld_format_price( $price ) ); ?></span>
							<span class="booking-card__per"><?php esc_html_e( 'per person', 'travelworld' ); ?></span>
						</div>
						<?php if ( $savings > 0 ) : ?>
							<span class="booking-card__badge"><?php printf( esc_html__( 'Save %d%%', 'travelworld' ), $savings ); ?></span>
						<?php endif; ?>
						<button type="button" class="btn btn-primary btn-block inquiry-trigger" data-package="<?php echo esc_attr( get_the_title() ); ?>">
							<?php esc_html_e( 'Inquiry Now', 'travelworld' ); ?>
						</button>
						<a href="https://wa.me/<?php echo esc_attr( $whatsapp ); ?>?text=<?php echo esc_attr( rawurlencode( 'Hi, I am interested in ' . get_the_title() ) ); ?>" class="btn btn-whatsapp btn-block" target="_blank" rel="noopener">
							<?php echo travelworld_icon_svg( 'whatsapp' ); ?>
							<?php esc_html_e( 'WhatsApp', 'travelworld' ); ?>
						</a>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="btn btn-outline btn-block">
							<?php echo travelworld_icon_svg( 'phone' ); ?>
							<?php esc_html_e( 'Call Now', 'travelworld' ); ?>
						</a>
						<div class="booking-card__help">
							<strong><?php esc_html_e( 'Need Help?', 'travelworld' ); ?></strong>
							<p><?php esc_html_e( 'Our travel experts are available 24/7 to assist you with your booking.', 'travelworld' ); ?></p>
							<p class="booking-card__contact"><?php echo travelworld_icon_svg( 'phone' ); ?> <?php echo esc_html( $phone ); ?></p>
							<p class="booking-card__contact"><?php echo travelworld_icon_svg( 'email' ); ?> <?php echo esc_html( travelworld_get_contact( 'email' ) ); ?></p>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
