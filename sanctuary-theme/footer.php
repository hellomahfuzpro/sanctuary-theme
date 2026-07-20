<?php
/**
 * Site footer. Columns, contact block, socials and disclaimer come from the Customizer.
 *
 * @package Sanctuary
 */

$tagline    = get_theme_mod( 'sanctuary_footer_tagline', __( 'A bright, friendly place to dance, drink and celebrate — in the heart of Stone.', 'sanctuary' ) );
$address    = get_theme_mod( 'sanctuary_contact_address', '' );
$phone      = get_theme_mod( 'sanctuary_contact_phone', '' );
$email      = get_theme_mod( 'sanctuary_contact_email', '' );
$instagram  = get_theme_mod( 'sanctuary_social_instagram', '' );
$disclaimer = get_theme_mod( 'sanctuary_footer_disclaimer', '' );
?>
</main><!-- #content -->

<footer class="site">
	<div class="wrap snc">
		<div>
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				printf( '<a href="%1$s" class="brand">%2$s</a>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
			}
			?>
			<?php if ( $tagline ) : ?>
				<p style="margin:.2rem 0 0;max-width:32ch"><?php echo esc_html( $tagline ); ?></p>
			<?php endif; ?>
		</div>

		<div>
			<h4><?php esc_html_e( 'Visit', 'sanctuary' ); ?></h4>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-links',
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
			}
			?>
		</div>

		<div>
			<h4><?php esc_html_e( 'Say hello', 'sanctuary' ); ?></h4>
			<?php if ( $instagram ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Instagram', 'sanctuary' ); ?></a>
			<?php endif; ?>
			<?php if ( $email ) : ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
			<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<?php if ( $address ) : ?>
				<address style="font-style:normal"><?php echo nl2br( esc_html( $address ) ); ?></address>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $disclaimer ) : ?>
		<div class="disclaimer"><div class="wrap"><?php echo esc_html( $disclaimer ); ?></div></div>
	<?php endif; ?>
</footer>

<?php wp_footer(); ?>
</body>
</html>
