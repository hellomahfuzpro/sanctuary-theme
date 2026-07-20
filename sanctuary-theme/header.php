<?php
/**
 * Site header. Driven by the Customizer (logo, CTA button) and the primary menu.
 *
 * @package Sanctuary
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site">
	<div class="wrap nav snc">
		<?php
		if ( has_custom_logo() ) {
			the_custom_logo();
		} else {
			printf(
				'<a href="%1$s" class="brand" aria-label="%2$s">%3$s</a>',
				esc_url( home_url( '/' ) ),
				esc_attr__( 'Home', 'sanctuary' ),
				esc_html( get_bloginfo( 'name' ) )
			);
		}
		?>

		<button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="<?php esc_attr_e( 'Toggle menu', 'sanctuary' ); ?>">&#9776;</button>

		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-links',
				'menu_id'        => 'primary-nav',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
		}

		$cta_label = get_theme_mod( 'sanctuary_header_cta_label', __( 'Book a class', 'sanctuary' ) );
		$cta_link  = get_theme_mod( 'sanctuary_header_cta_link', '#' );
		if ( $cta_label ) {
			printf(
				'<a href="%1$s" class="btn btn-primary">%2$s</a>',
				esc_url( $cta_link ),
				esc_html( $cta_label )
			);
		}
		?>
	</div>
</header>

<main id="content">
