
</main>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-col footer-col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo site-logo--footer">
					<span class="site-logo__icon"><?php echo travelworld_icon_svg( 'plane' ); ?></span>
					<span class="site-logo__text">TravelWorld</span>
				</a>
				<p class="footer-about"><?php echo esc_html( travelworld_get_contact( 'about_text' ) ?: 'Making international travel accessible and stress-free for over 15 years.' ); ?></p>
			</div>
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Quick Links', 'travelworld' ); ?></h4>
				<?php travelworld_footer_menu(); ?>
			</div>
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Popular Destinations', 'travelworld' ); ?></h4>
				<ul class="footer-links">
					<?php
					$destinations = get_posts( array(
						'post_type'      => 'destination',
						'posts_per_page' => 4,
						'orderby'        => 'title',
						'order'          => 'ASC',
					) );
					foreach ( $destinations as $dest ) :
						?>
						<li><a href="<?php echo esc_url( get_permalink( $dest ) ); ?>"><?php echo esc_html( $dest->post_title ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="footer-col">
				<h4 class="footer-heading"><?php esc_html_e( 'Contact Info', 'travelworld' ); ?></h4>
				<ul class="footer-contact">
					<li><?php echo travelworld_icon_svg( 'phone' ); ?> <?php echo esc_html( travelworld_get_contact( 'phone' ) ?: '+1 (234) 567-890' ); ?></li>
					<li><?php echo travelworld_icon_svg( 'email' ); ?> <?php echo esc_html( travelworld_get_contact( 'email' ) ?: 'info@createswowtech.com' ); ?></li>
					<li><?php echo travelworld_icon_svg( 'location' ); ?> <?php echo esc_html( travelworld_get_contact( 'address' ) ?: '123 Travel Street, City' ); ?></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> TravelWorld. <?php esc_html_e( 'All rights reserved.', 'travelworld' ); ?></p>
		</div>
	</div>
</footer>

<?php travelworld_render_inquiry_modal(); ?>

<?php wp_footer(); ?>
</body>
</html>
