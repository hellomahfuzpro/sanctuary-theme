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
