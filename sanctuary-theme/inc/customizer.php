<?php
/**
 * Customizer — the theme's "general page options": header CTA, footer content,
 * contact details, socials and site-wide integration URLs (Acuity, Maps) that
 * widgets can fall back to.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkbox sanitizer → strict bool.
 */
function sanctuary_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

function sanctuary_customize_register( $wp_customize ) {

	/* ---- Panel -------------------------------------------------------- */
	$wp_customize->add_panel( 'sanctuary_options', array(
		'title'    => __( 'The Sanctuary Options', 'sanctuary' ),
		'priority' => 20,
	) );

	/* ---- Header ------------------------------------------------------- */
	$wp_customize->add_section( 'sanctuary_header', array(
		'title'       => __( 'Header', 'sanctuary' ),
		'panel'       => 'sanctuary_options',
		'description' => __( 'The header logo is the site logo, set under Site Identity. The footer can use a different one — see the Footer section.', 'sanctuary' ),
	) );
	$wp_customize->add_setting( 'sanctuary_header_cta_label', array(
		'default'           => __( 'Book a class', 'sanctuary' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sanctuary_header_cta_label', array(
		'label'   => __( 'Header button label', 'sanctuary' ),
		'section' => 'sanctuary_header',
		'type'    => 'text',
	) );
	$wp_customize->add_setting( 'sanctuary_header_cta_link', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sanctuary_header_cta_link', array(
		'label'   => __( 'Header button link', 'sanctuary' ),
		'section' => 'sanctuary_header',
		'type'    => 'url',
	) );

	/* ---- Footer ------------------------------------------------------- */
	$wp_customize->add_section( 'sanctuary_footer', array(
		'title' => __( 'Footer', 'sanctuary' ),
		'panel' => 'sanctuary_options',
	) );

	// Separate footer logo. The header uses the core Site Identity logo; the
	// footer sits on the dark ink background, where a dark header logo often
	// disappears — so it needs its own (usually light/reversed) version.
	$wp_customize->add_setting( 'sanctuary_footer_logo', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sanctuary_footer_logo', array(
		'label'       => __( 'Footer logo', 'sanctuary' ),
		'description' => __( 'Optional. Leave empty to reuse the header logo from Site Identity, then the site name as text.', 'sanctuary' ),
		'section'     => 'sanctuary_footer',
		'mime_type'   => 'image',
	) ) );
	$wp_customize->add_setting( 'sanctuary_footer_logo_height', array(
		'default'           => 56,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sanctuary_footer_logo_height', array(
		'label'       => __( 'Footer logo height (px)', 'sanctuary' ),
		'description' => __( 'Caps the height; width scales automatically.', 'sanctuary' ),
		'section'     => 'sanctuary_footer',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 20, 'max' => 200, 'step' => 2 ),
	) );
	$wp_customize->add_setting( 'sanctuary_footer_tagline', array(
		'default'           => __( 'A bright, friendly place to dance, drink and celebrate — in the heart of Stone.', 'sanctuary' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sanctuary_footer_tagline', array(
		'label'   => __( 'Footer tagline', 'sanctuary' ),
		'section' => 'sanctuary_footer',
		'type'    => 'textarea',
	) );
	$wp_customize->add_setting( 'sanctuary_footer_disclaimer', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sanctuary_footer_disclaimer', array(
		'label'       => __( 'Disclaimer line', 'sanctuary' ),
		'description' => __( 'Small print shown under the footer. Leave blank to hide.', 'sanctuary' ),
		'section'     => 'sanctuary_footer',
		'type'        => 'textarea',
	) );

	/* ---- Contact ------------------------------------------------------ */
	$wp_customize->add_section( 'sanctuary_contact', array(
		'title' => __( 'Contact details', 'sanctuary' ),
		'panel' => 'sanctuary_options',
	) );
	foreach ( array(
		'sanctuary_contact_address' => array( __( 'Address', 'sanctuary' ), 'textarea', 'sanitize_textarea_field' ),
		'sanctuary_contact_phone'   => array( __( 'Phone', 'sanctuary' ), 'text', 'sanitize_text_field' ),
		'sanctuary_contact_email'   => array( __( 'Email', 'sanctuary' ), 'text', 'sanitize_email' ),
	) as $id => $args ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => $args[2] ) );
		$wp_customize->add_control( $id, array(
			'label'   => $args[0],
			'section' => 'sanctuary_contact',
			'type'    => $args[1],
		) );
	}

	/* ---- Social / Say hello ------------------------------------------- */
	$wp_customize->add_section( 'sanctuary_social', array(
		'title'       => __( 'Say hello (footer)', 'sanctuary' ),
		'description' => __( 'The footer "Say hello" column. Each point shows only if its URL/value is filled. Give a point a custom label, or leave the label blank to use the sensible default.', 'sanctuary' ),
		'panel'       => 'sanctuary_options',
	) );

	// Master: show icons.
	$wp_customize->add_setting( 'sanctuary_hello_show_icons', array(
		'default'           => true,
		'sanitize_callback' => 'sanctuary_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sanctuary_hello_show_icons', array(
		'label'   => __( 'Show icons next to each point', 'sanctuary' ),
		'section' => 'sanctuary_social',
		'type'    => 'checkbox',
	) );

	// Heading text for the column.
	$wp_customize->add_setting( 'sanctuary_hello_heading', array(
		'default'           => __( 'Say hello', 'sanctuary' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sanctuary_hello_heading', array(
		'label'   => __( 'Column heading', 'sanctuary' ),
		'section' => 'sanctuary_social',
		'type'    => 'text',
	) );

	// Channels: value setting + label setting.
	$channels = array(
		'instagram' => array( __( 'Instagram URL', 'sanctuary' ), 'url', __( 'Instagram', 'sanctuary' ) ),
		'facebook'  => array( __( 'Facebook URL', 'sanctuary' ), 'url', __( 'Facebook', 'sanctuary' ) ),
		'whatsapp'  => array( __( 'WhatsApp link/number', 'sanctuary' ), 'text', __( 'WhatsApp', 'sanctuary' ) ),
		'email'     => array( __( 'Email (uses Contact email if blank)', 'sanctuary' ), 'text', __( 'Email', 'sanctuary' ) ),
		'phone'     => array( __( 'Phone (uses Contact phone if blank)', 'sanctuary' ), 'text', __( 'Call', 'sanctuary' ) ),
	);
	foreach ( $channels as $key => $c ) {
		$val_id   = ( 'instagram' === $key ) ? 'sanctuary_social_instagram' : 'sanctuary_hello_' . $key . '_value';
		$sanitize = ( 'url' === $c[1] ) ? 'esc_url_raw' : 'sanitize_text_field';

		$wp_customize->add_setting( $val_id, array( 'default' => '', 'sanitize_callback' => $sanitize ) );
		$wp_customize->add_control( $val_id, array(
			'label'   => $c[0],
			'section' => 'sanctuary_social',
			'type'    => ( 'url' === $c[1] ) ? 'url' : 'text',
		) );

		$wp_customize->add_setting( 'sanctuary_hello_' . $key . '_label', array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'sanctuary_hello_' . $key . '_label', array(
			/* translators: %s: channel default label */
			'label'   => sprintf( __( '↳ %s label', 'sanctuary' ), $c[2] ),
			'section' => 'sanctuary_social',
			'type'    => 'text',
		) );
	}

	/* ---- Integrations (widget fallbacks) ------------------------------ */
	$wp_customize->add_section( 'sanctuary_integrations', array(
		'title'       => __( 'Integrations', 'sanctuary' ),
		'description' => __( 'Site-wide defaults the widgets fall back to when left blank.', 'sanctuary' ),
		'panel'       => 'sanctuary_options',
	) );
	$wp_customize->add_setting( 'sanctuary_acuity_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'sanctuary_acuity_embed', array(
		'label'       => __( 'Acuity embed code / URL', 'sanctuary' ),
		'description' => __( 'The Acuity scheduler embed. Used by the Booking Embed widget when its own field is empty.', 'sanctuary' ),
		'section'     => 'sanctuary_integrations',
		'type'        => 'textarea',
	) );
	$wp_customize->add_setting( 'sanctuary_map_embed', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'sanctuary_map_embed', array(
		'label'       => __( 'Google Maps embed', 'sanctuary' ),
		'description' => __( 'Paste the Google Maps iframe embed code.', 'sanctuary' ),
		'section'     => 'sanctuary_integrations',
		'type'        => 'textarea',
	) );
}
add_action( 'customize_register', 'sanctuary_customize_register' );
