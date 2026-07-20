<?php
/**
 * Elementor integration: custom category + widget autoloader.
 *
 * Every file in /widgets defines one Elementor widget class extending the base
 * helper in widgets/class-base-widget.php. They are registered automatically,
 * so adding a new section widget is just dropping a new file in that folder.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the "The Sanctuary" panel category so all custom widgets group together.
 */
function sanctuary_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'sanctuary',
		array(
			'title' => __( 'The Sanctuary', 'sanctuary' ),
			'icon'  => 'fa fa-star',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'sanctuary_elementor_category' );

/**
 * Load the base class, then every widget file, then register each class.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function sanctuary_register_widgets( $widgets_manager ) {
	$dir = SANCTUARY_DIR . '/widgets/';

	// Base class first.
	require_once $dir . 'class-base-widget.php';

	foreach ( glob( $dir . 'class-*.php' ) as $file ) {
		if ( false !== strpos( $file, 'class-base-widget.php' ) ) {
			continue;
		}
		require_once $file;
	}

	// Map file → class name. Files are named class-{slug}.php and define
	// Sanctuary_Widget_{StudlySlug}.
	foreach ( glob( $dir . 'class-*.php' ) as $file ) {
		$base = basename( $file, '.php' ); // class-hero
		if ( 'class-base-widget' === $base ) {
			continue;
		}
		$slug  = substr( $base, strlen( 'class-' ) );        // hero
		$class = 'Sanctuary_Widget_' . str_replace( ' ', '', ucwords( str_replace( '-', ' ', $slug ) ) );
		if ( class_exists( $class ) ) {
			$widgets_manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'sanctuary_register_widgets' );
