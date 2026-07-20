<?php
/**
 * The Sanctuary theme bootstrap.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SANCTUARY_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
define( 'SANCTUARY_DIR', get_template_directory() );
define( 'SANCTUARY_URI', get_template_directory_uri() );

/**
 * Theme supports & menus.
 */
function sanctuary_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 92,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	// Elementor full-width / canvas friendliness.
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'sanctuary' ),
		'footer'  => __( 'Footer Navigation', 'sanctuary' ),
	) );
}
add_action( 'after_setup_theme', 'sanctuary_setup' );

/**
 * The exact Google Fonts the client mockups use: Bricolage Grotesque (display),
 * Hanken Grotesk (body), Sacramento (script wordmark). Kept as the original
 * hosted stylesheet so the type and wordmark match the design out of the box.
 * (To self-host later for UK GDPR, drop woff2s in /assets/fonts and point this
 * handle at assets/fonts/fonts.css instead — see README-FONTS.md.)
 */
function sanctuary_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Hanken+Grotesk:wght@400;500;600;700&family=Sacramento&display=swap';
}

/**
 * Preconnect to the Google Fonts hosts for faster first paint.
 */
function sanctuary_resource_hints( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sanctuary_resource_hints', 10, 2 );

/**
 * Build a WhatsApp href from either a full link or a phone number.
 */
function sanctuary_whatsapp_href( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( 0 === strpos( $value, 'http' ) ) {
		return $value;
	}
	return 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $value );
}

/**
 * Inline SVG icon for a footer "Say hello" channel. Uses currentColor so it
 * inherits the link colour. Returns trusted static markup.
 *
 * @param string $key instagram|facebook|whatsapp|email|phone
 * @return string
 */
function sanctuary_hello_icon( $key ) {
	$open  = '<svg class="hello-ico" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';
	$paths = array(
		'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
		'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'whatsapp'  => '<path d="M21 11.5a8.5 8.5 0 0 1-12.6 7.4L3 21l2.2-5.2A8.5 8.5 0 1 1 21 11.5z"/><path d="M8.5 9c0 3.6 2.9 6.5 6.5 6.5"/>',
		'email'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m3 6 9 7 9-7"/>',
		'phone'     => '<path d="M22 16.9v2.6a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 3.7 2 2 0 0 1 4 1.5h2.6a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L7.6 9a16 16 0 0 0 6 6l1.1-1.1a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2z"/>',
	);
	if ( empty( $paths[ $key ] ) ) {
		return '';
	}
	return $open . $paths[ $key ] . '</svg>';
}

/**
 * Front-end assets.
 */
function sanctuary_assets() {
	wp_enqueue_style( 'sanctuary-fonts', sanctuary_fonts_url(), array(), null );
	wp_enqueue_style( 'sanctuary-tokens', SANCTUARY_URI . '/assets/css/tokens.css', array( 'sanctuary-fonts' ), SANCTUARY_VERSION );
	wp_enqueue_style( 'sanctuary-widgets', SANCTUARY_URI . '/assets/css/widgets.css', array( 'sanctuary-tokens' ), SANCTUARY_VERSION );
	// style.css last so a child theme / custom CSS can override.
	wp_enqueue_style( 'sanctuary-style', get_stylesheet_uri(), array( 'sanctuary-widgets' ), SANCTUARY_VERSION );

	wp_enqueue_script( 'sanctuary-theme', SANCTUARY_URI . '/assets/js/theme.js', array(), SANCTUARY_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'sanctuary_assets' );

/**
 * Load the design system inside the Elementor *preview iframe* only, so custom
 * widgets preview correctly. We deliberately do NOT enqueue into the editor
 * panel UI (elementor/editor/after_enqueue_styles) — our global body/reset rules
 * would override Elementor's own panel styling and break its dark mode.
 */
function sanctuary_editor_assets() {
	wp_enqueue_style( 'sanctuary-fonts', sanctuary_fonts_url(), array(), null );
	wp_enqueue_style( 'sanctuary-tokens', SANCTUARY_URI . '/assets/css/tokens.css', array(), SANCTUARY_VERSION );
	wp_enqueue_style( 'sanctuary-widgets', SANCTUARY_URI . '/assets/css/widgets.css', array( 'sanctuary-tokens' ), SANCTUARY_VERSION );
}
add_action( 'elementor/preview/enqueue_styles', 'sanctuary_editor_assets' );

/**
 * Modules.
 */
require_once SANCTUARY_DIR . '/inc/customizer.php';
require_once SANCTUARY_DIR . '/inc/elementor.php';
require_once SANCTUARY_DIR . '/inc/updater.php';
require_once SANCTUARY_DIR . '/inc/demo-import.php';

/**
 * Admin notice if Elementor is missing — the theme depends on it.
 */
function sanctuary_check_elementor() {
	if ( did_action( 'elementor/loaded' ) ) {
		return;
	}
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'The Sanctuary theme requires the Elementor plugin (Pro for enquiry forms). Please install and activate Elementor.', 'sanctuary' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'sanctuary_check_elementor' );
