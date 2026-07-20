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

function sanctuary_customize_register( $wp_customize ) {

	/* ---- Panel -------------------------------------------------------- */
	$wp_customize->add_panel( 'sanctuary_options', array(
		'title'    => __( 'The Sanctuary Options', 'sanctuary' ),
		'priority' => 20,
	) );

	/* ---- Header ------------------------------------------------------- */
	$wp_customize->add_section( 'sanctuary_header', array(
		'title' => __( 'Header', 'sanctuary' ),
		'panel' => 'sanctuary_options',
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

	/* ---- Social ------------------------------------------------------- */
	$wp_customize->add_section( 'sanctuary_social', array(
		'title' => __( 'Social', 'sanctuary' ),
		'panel' => 'sanctuary_options',
	) );
	$wp_customize->add_setting( 'sanctuary_social_instagram', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sanctuary_social_instagram', array(
		'label'   => __( 'Instagram URL', 'sanctuary' ),
		'section' => 'sanctuary_social',
		'type'    => 'url',
	) );

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
