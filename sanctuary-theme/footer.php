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
			<h4><?php echo esc_html( get_theme_mod( 'sanctuary_hello_heading', __( 'Say hello', 'sanctuary' ) ) ); ?></h4>
			<?php
			$show_icons = (bool) get_theme_mod( 'sanctuary_hello_show_icons', true );

			// value, mod-key for label, default label, href builder.
			$points = array(
				'instagram' => array(
					'value'   => $instagram,
					'default' => __( 'Instagram', 'sanctuary' ),
					'href'    => $instagram,
					'attr'    => ' target="_blank" rel="noopener"',
				),
				'facebook'  => array(
					'value'   => get_theme_mod( 'sanctuary_hello_facebook_value', '' ),
					'default' => __( 'Facebook', 'sanctuary' ),
					'href'    => get_theme_mod( 'sanctuary_hello_facebook_value', '' ),
					'attr'    => ' target="_blank" rel="noopener"',
				),
				'whatsapp'  => array(
					'value'   => get_theme_mod( 'sanctuary_hello_whatsapp_value', '' ),
					'default' => __( 'WhatsApp', 'sanctuary' ),
					'href'    => sanctuary_whatsapp_href( get_theme_mod( 'sanctuary_hello_whatsapp_value', '' ) ),
					'attr'    => ' target="_blank" rel="noopener"',
				),
				'email'     => array(
					'value'   => get_theme_mod( 'sanctuary_hello_email_value', '' ) ?: $email,
					'default' => ( get_theme_mod( 'sanctuary_hello_email_value', '' ) ?: $email ),
					'href'    => 'mailto:' . ( get_theme_mod( 'sanctuary_hello_email_value', '' ) ?: $email ),
					'attr'    => '',
				),
				'phone'     => array(
					'value'   => get_theme_mod( 'sanctuary_hello_phone_value', '' ) ?: $phone,
					'default' => ( get_theme_mod( 'sanctuary_hello_phone_value', '' ) ?: $phone ),
					'href'    => 'tel:' . preg_replace( '/[^0-9+]/', '', ( get_theme_mod( 'sanctuary_hello_phone_value', '' ) ?: $phone ) ),
					'attr'    => '',
				),
			);

			foreach ( $points as $key => $p ) {
				if ( empty( $p['value'] ) ) {
					continue;
				}
				$label = get_theme_mod( 'sanctuary_hello_' . $key . '_label', '' );
				if ( '' === $label ) {
					$label = $p['default'];
				}
				printf(
					'<a class="hello-link" href="%1$s"%2$s>%3$s<span>%4$s</span></a>',
					esc_url( $p['href'] ),
					$p['attr'], // phpcs:ignore -- static, safe attribute string.
					$show_icons ? sanctuary_hello_icon( $key ) : '',
					esc_html( $label )
				);
			}
			?>
			<?php if ( $address ) : ?>
				<address class="hello-address" style="font-style:normal"><?php echo $show_icons ? sanctuary_hello_icon( 'location' ) : ''; // phpcs:ignore ?><span><?php echo nl2br( esc_html( $address ) ); ?></span></address>
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
