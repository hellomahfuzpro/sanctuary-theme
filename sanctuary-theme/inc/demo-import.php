<?php
/**
 * Page seeder — assembles the five pages out of the custom widgets.
 *
 * Because Elementor stores a page as a JSON tree in the `_elementor_data` post
 * meta, we build that tree programmatically from our own widget types and their
 * settings. This is more robust than hand-editing an exported template kit and
 * stays in sync with the widget names defined in /widgets.
 *
 * Run it from **Tools → Sanctuary: Build pages**, or via WP-CLI:
 *   wp sanctuary build-pages
 *
 * Re-running updates the same pages (matched by slug) rather than duplicating.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Short unique element id, Elementor style (7 hex chars).
 */
function snc_uid() {
	return substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 7 );
}

/**
 * Wrap a list of [ widgetType => settings ] widgets, one per full-width section.
 *
 * @param array $widgets Array of array( 'type' => string, 'settings' => array ).
 * @return array Elementor data tree.
 */
function snc_build_sections( array $widgets ) {
	$tree = array();
	foreach ( $widgets as $w ) {
		$tree[] = array(
			'id'       => snc_uid(),
			'elType'   => 'section',
			'settings' => array( 'content_width' => 'full', 'gap' => 'no', 'padding' => array( 'unit' => 'px', 'top' => '0', 'bottom' => '0', 'left' => '0', 'right' => '0', 'isLinked' => true ) ),
			'isInner'  => false,
			'elements' => array(
				array(
					'id'       => snc_uid(),
					'elType'   => 'column',
					'settings' => array( '_column_size' => 100, '_inline_size' => null ),
					'isInner'  => false,
					'elements' => array(
						array(
							'id'         => snc_uid(),
							'elType'     => 'widget',
							'widgetType' => $w['type'],
							'settings'   => isset( $w['settings'] ) ? $w['settings'] : array(),
							'elements'   => array(),
						),
					),
				),
			),
		);
	}
	return $tree;
}

/**
 * Add Elementor `_id`s to repeater rows so they persist correctly.
 */
function snc_rep( array $rows ) {
	return array_map(
		function ( $row ) {
			$row['_id'] = snc_uid();
			return $row;
		},
		$rows
	);
}

/**
 * Definitions for all five pages: slug => [ title, is_front, widgets[] ].
 */
function snc_page_definitions() {
	$pages = array();

	/* ------------------------------------------------------------------ HOME */
	$pages['home'] = array(
		'title'    => 'Home',
		'is_front' => true,
		'widgets'  => array(
			array( 'type' => 'sanctuary_hero', 'settings' => array(
				'credit' => 'Led by international champion <b>Oliver Hand</b> · <span class="note">[verify]</span>',
			) ),
			array( 'type' => 'sanctuary_stat_strip', 'settings' => array() ),
			array( 'type' => 'sanctuary_fork', 'settings' => array() ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'        => 'cream2',
				'anchor'    => 'classes',
				'eyebrow'   => 'Classes',
				'title'     => "New to dancing? You'll fit right in.",
				'lede'      => 'No experience, no partner, no problem. Our beginner classes are warm, easy and a genuinely good night.',
				'body'      => '',
				'chips'     => snc_rep( array(
					array( 'label' => 'Ballroom' ), array( 'label' => 'Latin' ), array( 'label' => 'Line' ), array( 'label' => 'Private lessons' ),
				) ),
				'offer'     => '<b>[FIRST-CLASS OFFER — load-bearing]</b> e.g. "First class £X" or "Your first class is on us."',
				'btn_text'  => 'See the timetable & book',
				'gradient'  => 'g5',
				'image_label' => 'Beginners laughing through a class',
			) ),
			array( 'type' => 'sanctuary_event_cards', 'settings' => array() ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'          => 'ink',
				'anchor'      => 'hire',
				'eyebrow'     => 'Venue hire',
				'title'       => 'A bright space for your big day.',
				'lede'        => 'A versatile 3,000 sq ft venue in the heart of Stone — sprung dance floor, café-cocktail bar, changing rooms and surround sound.',
				'body'        => '',
				'uc_tags'     => snc_rep( array(
					array( 'label' => 'Weddings' ), array( 'label' => 'Parties' ), array( 'label' => 'Corporate' ), array( 'label' => 'Christenings' ),
				) ),
				'btn_text'    => 'Explore venue hire',
				'btn_style'   => 'btn-amber',
				'specs'       => 'Capacity: <span class="note">[seated / standing]</span> · Rates: <span class="note">[from £X]</span>',
				'gradient'    => 'g2',
				'image_label' => 'A real event dressed in the space',
			) ),
			array( 'type' => 'sanctuary_testimonials', 'settings' => array() ),
			array( 'type' => 'sanctuary_instagram', 'settings' => array() ),
			array( 'type' => 'sanctuary_find_us', 'settings' => array() ),
			array( 'type' => 'sanctuary_closing_cta', 'settings' => array() ),
		),
	);

	/* --------------------------------------------------------------- CLASSES */
	$pages['classes'] = array(
		'title'    => 'Classes',
		'is_front' => false,
		'widgets'  => array(
			array( 'type' => 'sanctuary_hero', 'settings' => array(
				'layout'    => 'center',
				'eyebrow'   => 'Classes',
				'heading'   => 'Everybody started where you are.',
				'sub'       => 'New to dancing? Good — so was everyone, once. Our classes are warm, easy and a proper good night. No experience, no partner, no pressure.',
				'btn1_text' => 'Book your first class',
				'btn1_link' => array( 'url' => '#timetable' ),
				'btn2_text' => '',
			) ),
			array( 'type' => 'sanctuary_stat_strip', 'settings' => array(
				'style'   => 'cards',
				'columns' => '3',
				'items'   => snc_rep( array(
					array( 'big' => 'No partner needed', 'sub' => 'Come on your own — most people do.' ),
					array( 'big' => 'No experience needed', 'sub' => 'Beginner classes start from the very start.' ),
					array( 'big' => 'Just turn up', 'sub' => 'Wear comfy clothes and flat shoes. [confirm]' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_style_cards', 'settings' => array() ),
			array( 'type' => 'sanctuary_booking_embed', 'settings' => array() ),
			array( 'type' => 'sanctuary_pricing', 'settings' => array() ),
			array( 'type' => 'sanctuary_faq', 'settings' => array() ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'         => 'ink',
				'show_media' => '',
				'eyebrow'    => 'Private lessons',
				'title'      => 'Want one-to-one?',
				'lede'       => 'Private lessons in 30, 45 or 90 minutes — great for a wedding first dance or just faster progress.',
				'body'       => '',
				'btn_text'   => 'Book a private lesson',
				'btn_style'  => 'btn-ghost',
			) ),
			array( 'type' => 'sanctuary_closing_cta', 'settings' => array(
				'color'     => 'coral',
				'heading'   => 'Your first class is closer than you think.',
				'text'      => "Pick a time, book your spot, and we'll see you on the floor.",
				'btn1_text' => 'See the timetable',
				'btn1_link' => array( 'url' => '#timetable' ),
				'btn2_text' => '',
			) ),
		),
	);

	/* ------------------------------------------------------------ VENUE HIRE */
	$pages['venue-hire'] = array(
		'title'    => 'Venue Hire',
		'is_front' => false,
		'widgets'  => array(
			array( 'type' => 'sanctuary_hero', 'settings' => array(
				'layout'      => 'split',
				'bg'          => 'teal',
				'eyebrow'     => 'Venue hire',
				'heading'     => 'A bright, versatile venue in the heart of Stone.',
				'sub'         => '3,000 sq ft, a sprung dance floor, a café-cocktail bar and a warm, welcoming feel — lovely for weddings, easy for parties, capable for work events.',
				'btn1_text'   => 'Enquire now',
				'btn1_link'   => array( 'url' => '#enquire' ),
				'btn2_text'   => '',
				'show_card'   => '',
				'gradient'    => 'g4',
				'image_label' => 'The space looking its best — dressed, or full of people',
			) ),
			array( 'type' => 'sanctuary_stat_strip', 'settings' => array(
				'style'   => 'cards',
				'columns' => '3',
				'items'   => snc_rep( array(
					array( 'big' => '2,000 sq ft sprung floor', 'sub' => 'Kind on feet, made for dancing.' ),
					array( 'big' => 'Café-cocktail bar', 'sub' => 'Fully stocked, staffed, open before & after.' ),
					array( 'big' => 'Surround sound', 'sub' => 'In the café and ballroom.' ),
					array( 'big' => 'Changing & treatment rooms', 'sub' => 'Space to get ready on site.' ),
					array( 'big' => 'Capacity', 'sub' => '<span class="note">[seated / standing — load-bearing]</span>' ),
					array( 'big' => 'Accessibility & parking', 'sub' => '<span class="note">[add]</span>' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_use_cases', 'settings' => array() ),
			array( 'type' => 'sanctuary_included_list', 'settings' => array( 'bg' => 'cream2' ) ),
			array( 'type' => 'sanctuary_gallery', 'settings' => array() ),
			array( 'type' => 'sanctuary_rates', 'settings' => array() ),
			array( 'type' => 'sanctuary_enquiry_form', 'settings' => array() ),
			array( 'type' => 'sanctuary_testimonials', 'settings' => array(
				'eyebrow' => 'Couples & clients',
				'title'   => 'They held it here.',
				'lede'    => 'Venue testimonials — weddings especially are bought on other people\'s experiences.',
			) ),
			array( 'type' => 'sanctuary_closing_cta', 'settings' => array(
				'color'     => 'teal',
				'heading'   => "Let's talk about your event.",
				'text'      => "Check a date or ask a question — we're happy to show you around.",
				'btn1_text' => 'Enquire about hire',
				'btn1_link' => array( 'url' => '#enquire' ),
				'btn2_text' => '',
			) ),
		),
	);

	/* ---------------------------------------------------------- PRIVATE HIRE */
	$pages['private-hire'] = array(
		'title'    => 'Private Hire',
		'is_front' => false,
		'widgets'  => array(
			array( 'type' => 'sanctuary_hero', 'settings' => array(
				'layout'      => 'split',
				'bg'          => 'teal',
				'eyebrow'     => 'Private hire',
				'heading'     => 'Celebrate it here.',
				'sub'         => 'One bright, versatile venue in the heart of Stone — a sprung floor, a cocktail bar and room to make a day of it. Weddings, work events and parties all welcome.',
				'btn1_text'   => 'Check your date',
				'btn1_link'   => array( 'url' => '#enquire' ),
				'btn2_text'   => '',
				'show_card'   => '',
				'gradient'    => 'g5',
				'image_label' => 'The space, dressed and full of people',
				'jump_items'  => snc_rep( array(
					array( 'label' => 'Weddings', 'link' => array( 'url' => '#weddings' ) ),
					array( 'label' => 'Corporate', 'link' => array( 'url' => '#corporate' ) ),
					array( 'label' => 'Parties', 'link' => array( 'url' => '#parties' ) ),
				) ),
			) ),
			array( 'type' => 'sanctuary_stat_strip', 'settings' => array(
				'columns' => '4',
				'items'   => snc_rep( array(
					array( 'big' => '3,000 sq ft', 'sub' => 'versatile venue' ),
					array( 'big' => '2,000 sq ft', 'sub' => 'sprung dance floor' ),
					array( 'big' => 'Cocktail bar', 'sub' => 'staffed & stocked' ),
					array( 'big' => 'Capacity', 'sub' => '<span class="note">[seated / standing]</span>' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'          => 'cream',
				'anchor'      => 'weddings',
				'pill'        => 'Weddings',
				'pill_color'  => 'teal',
				'eyebrow'     => '',
				'title'       => 'A warm, beautiful place to say "I do".',
				'lede'        => '',
				'body'        => 'Intimate and characterful, with a sprung floor made for a first dance and a bar to keep the night going.',
				'ticks'       => snc_rep( array(
					array( 'text' => '<b>Sprung floor</b> — kind on feet, perfect for the first dance' ),
					array( 'text' => '<b>Getting-ready space</b> on site <span class="note">[confirm]</span>' ),
					array( 'text' => '<b>Capacity & layouts</b> <span class="note">[load-bearing]</span>' ),
				) ),
				'xsell'       => 'Planning a first dance? Pair your hire with private lessons — <a href="#" style="color:var(--coral-deep);font-weight:700">see lessons →</a>',
				'btn_text'    => 'Enquire about your wedding',
				'btn_style'   => 'btn-ghost',
				'btn_link'    => array( 'url' => '#enquire' ),
				'gradient'    => 'g4',
				'image_label' => 'A real wedding held here',
			) ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'          => 'cream2',
				'flip'        => 'yes',
				'anchor'      => 'corporate',
				'pill'        => 'Corporate',
				'pill_color'  => 'ink',
				'eyebrow'     => '',
				'title'       => "A flexible space for work that isn't work-like.",
				'lede'        => '',
				'body'        => 'Away-days, workshops, networking and team celebrations — an open, adaptable room with AV and a café-bar.',
				'ticks'       => snc_rep( array(
					array( 'text' => '<b>Flexible layouts</b> — theatre, cabaret, standing' ),
					array( 'text' => '<b>Surround sound & AV</b> <span class="note">[confirm mic / screen]</span>' ),
					array( 'text' => '<b>Catering & bar</b>, parking & access <span class="note">[load-bearing]</span>' ),
				) ),
				'btn_text'    => 'Enquire for your team',
				'btn_style'   => 'btn-ghost',
				'btn_link'    => array( 'url' => '#enquire' ),
				'gradient'    => 'g4',
				'image_label' => 'A workshop / team event set up',
			) ),
			array( 'type' => 'sanctuary_feature', 'settings' => array(
				'bg'          => 'peach',
				'anchor'      => 'parties',
				'pill'        => 'Parties',
				'pill_color'  => 'coral',
				'eyebrow'     => '',
				'title'       => 'Birthdays, big ones, and everything in between.',
				'lede'        => '',
				'body'        => 'Milestone birthdays, anniversaries, christenings and kids\' parties — a big floor, a proper bar and a warm welcome.',
				'ticks'       => snc_rep( array(
					array( 'text' => '<b>Whole-venue feel</b> with room to dance' ),
					array( 'text' => '<b>Staffed bar</b> for the grown-ups' ),
					array( 'text' => '<b>What\'s included & add-ons</b> <span class="note">[load-bearing]</span>' ),
				) ),
				'btn_text'    => 'Enquire about a party',
				'btn_style'   => 'btn-primary',
				'btn_link'    => array( 'url' => '#enquire' ),
				'gradient'    => 'g1',
				'image_label' => 'A lively party in full swing',
			) ),
			array( 'type' => 'sanctuary_included_list', 'settings' => array(
				'bg'      => 'cream',
				'eyebrow' => 'Whatever the occasion',
				'title'   => 'What every hire includes.',
			) ),
			array( 'type' => 'sanctuary_rates', 'settings' => array() ),
			array( 'type' => 'sanctuary_enquiry_form', 'settings' => array(
				'title' => 'Check your date.',
				'lede'  => "Tell us the occasion, the date and rough numbers, and we'll come back with availability and a quote.",
			) ),
			array( 'type' => 'sanctuary_testimonials', 'settings' => array(
				'eyebrow' => 'They celebrated here',
				'title'   => 'A word from people who did.',
				'items'   => snc_rep( array(
					array( 'tag' => 'Wedding', 'quote' => 'A short quote from a couple married here.', 'by' => '— [Couple], [date]' ),
					array( 'tag' => 'Corporate', 'quote' => 'A short quote from a company / organiser.', 'by' => '— [Name], [company]' ),
					array( 'tag' => 'Party', 'quote' => 'A short quote from a birthday / celebration.', 'by' => '— [Name]' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_closing_cta', 'settings' => array(
				'color'     => 'teal',
				'heading'   => "Let's talk about your day.",
				'text'      => "Check a date, ask a question, or come and see the space — we'd love to help.",
				'btn1_text' => 'Enquire now',
				'btn1_link' => array( 'url' => '#enquire' ),
				'btn2_text' => '',
			) ),
		),
	);

	return $pages;
}

/**
 * Create/update all pages. Returns a log array of slug => action.
 */
function snc_build_pages() {
	$log = array();

	foreach ( snc_page_definitions() as $slug => $def ) {
		$existing = get_page_by_path( $slug );

		$postarr = array(
			'post_title'   => $def['title'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$page_id       = wp_update_post( $postarr );
			$log[ $slug ]  = 'updated';
		} else {
			$page_id      = wp_insert_post( $postarr );
			$log[ $slug ] = 'created';
		}

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			$log[ $slug ] = 'error';
			continue;
		}

		$data = snc_build_sections( $def['widgets'] );

		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			update_post_meta( $page_id, '_elementor_version', ELEMENTOR_VERSION );
		}

		if ( ! empty( $def['is_front'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_id );
		}
	}

	// Flush Elementor CSS cache so the new pages render immediately.
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	return $log;
}

/* -------------------------------------------------------- Admin trigger --- */
add_action( 'admin_menu', function () {
	add_management_page(
		__( 'Sanctuary: Build pages', 'sanctuary' ),
		__( 'Sanctuary: Build pages', 'sanctuary' ),
		'manage_options',
		'sanctuary-build-pages',
		'sanctuary_build_pages_screen'
	);
} );

function sanctuary_build_pages_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<div class="wrap"><h1>' . esc_html__( 'Build the Sanctuary pages', 'sanctuary' ) . '</h1>';

	if ( isset( $_POST['snc_build'] ) && check_admin_referer( 'snc_build_pages' ) ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Elementor is not active — activate it first.', 'sanctuary' ) . '</p></div>';
		} else {
			$log = snc_build_pages();
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Done:', 'sanctuary' ) . ' ';
			foreach ( $log as $slug => $action ) {
				echo esc_html( "$slug: $action  " );
			}
			echo '</p></div>';
		}
	}

	echo '<p>' . esc_html__( 'Creates (or updates) Home, Classes, Venue Hire and Private Hire as Elementor pages built from the custom widgets, and sets Home as the front page.', 'sanctuary' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'snc_build_pages' );
	echo '<p><button class="button button-primary" name="snc_build" value="1">' . esc_html__( 'Build / rebuild pages', 'sanctuary' ) . '</button></p>';
	echo '</form></div>';
}

/* ------------------------------------------------------------- WP-CLI ----- */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'sanctuary build-pages', function () {
		$log = snc_build_pages();
		foreach ( $log as $slug => $action ) {
			WP_CLI::log( "$slug: $action" );
		}
		WP_CLI::success( 'Pages built.' );
	} );
}
