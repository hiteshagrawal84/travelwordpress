<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<?php if ( '1' === travelworld_get_setting( 'topbar_enabled', '1' ) ) : ?>
		<div class="top-bar">
			<div class="container top-bar__inner">
				<div class="top-bar__contact">
					<?php if ( '1' === travelworld_get_setting( 'topbar_show_phone', '1' ) ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', travelworld_get_setting( 'topbar_phone' ) ) ); ?>">
							<?php echo travelworld_icon_svg( 'phone' ); ?>
							<?php echo esc_html( travelworld_get_setting( 'topbar_phone' ) ); ?>
						</a>
					<?php endif; ?>
					<?php if ( '1' === travelworld_get_setting( 'topbar_show_email', '1' ) ) : ?>
						<a href="mailto:<?php echo esc_attr( travelworld_get_setting( 'topbar_email' ) ); ?>">
							<?php echo travelworld_icon_svg( 'email' ); ?>
							<?php echo esc_html( travelworld_get_setting( 'topbar_email' ) ); ?>
						</a>
					<?php endif; ?>
				</div>
				<?php if ( '1' === travelworld_get_setting( 'topbar_show_support', '1' ) ) : ?>
					<span class="top-bar__support"><?php echo esc_html( travelworld_get_setting( 'topbar_support_text' ) ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="main-header">
		<div class="container main-header__inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo">
				<span class="site-logo__icon"><?php echo travelworld_icon_svg( 'plane' ); ?></span>
				<span class="site-logo__text">TravelWorld</span>
			</a>
			<button class="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'travelworld' ); ?>" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
			<nav class="main-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'travelworld' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'nav-menu',
						'container'      => false,
						'fallback_cb'    => 'travelworld_default_menu',
					) );
				} else {
					travelworld_default_menu();
				}
				?>
			</nav>
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' ) ); ?>" class="btn btn-primary header-cta inquiry-trigger"><?php esc_html_e( 'Get Quote', 'travelworld' ); ?></a>
		</div>
	</div>
</header>

<main id="main-content" class="site-main">
