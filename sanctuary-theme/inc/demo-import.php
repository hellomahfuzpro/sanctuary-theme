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
 *   wp sanctuary build-pages                  (rebuilds every page)
 *   wp sanctuary build-pages --page=timetable (rebuilds just that one)
 *
 * Re-running updates the same page(s) (matched by slug) rather than
 * duplicating. Rebuilding is per-page by default (both in the admin screen
 * and via --page) so fixing/adding one page never overwrites manual edits
 * made in the Elementor editor on the others — "rebuild everything" is a
 * separate, clearly-labelled action for a full reset.
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
 * Wrap a list of [ widgetType => settings ] widgets, one per full-width
 * flexbox **container** (Elementor's current layout element, not the legacy
 * section/column structure). Each widget renders its own full-bleed <section>,
 * so the container is full-width with zero padding/gap.
 *
 * @param array $widgets Array of array( 'type' => string, 'settings' => array ).
 * @return array Elementor data tree.
 */
function snc_build_tree( array $widgets ) {
	$tree = array();
	foreach ( $widgets as $w ) {
		$tree[] = array(
			'id'       => snc_uid(),
			'elType'   => 'container',
			'settings' => array(
				'content_width' => 'full',
				'flex_gap'      => array( 'unit' => 'px', 'size' => 0, 'column' => '0', 'row' => '0' ),
				'padding'       => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
			),
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
 * One Timetable Day class-repeater row. Booking URLs are the real Acuity
 * links from the source timetable (ported from sanctuary-timetable.html),
 * not placeholders.
 */
function snc_tt_row( $time, $name, $category, $url ) {
	return array(
		'time'       => $time,
		'name'       => $name,
		'category'   => $category,
		'price'      => '£[X]',
		'book_label' => 'Book',
		'book_link'  => array( 'url' => $url, 'is_external' => true ),
	);
}

/* ===================================================================
   Auto free-image import
   The seeder downloads a curated set of free-licensed Unsplash photos
   (Unsplash License: free for commercial use, no attribution required)
   into the Media Library and assigns them to the widgets. Each unique
   image downloads once (cached by slot in an option); re-running the
   seeder reuses them. If a download fails (e.g. no outbound network),
   the widget simply falls back to its gradient placeholder.

   Swap in your own photos by filtering `sanctuary_image_sources`.
   =================================================================== */

/**
 * Slot => source URL. Filterable so real photography can replace these.
 */
function snc_image_sources() {
	$base = 'https://images.unsplash.com/photo-';
	$q    = '?auto=format&fit=crop&w=1400&q=80';
	$map  = array(
		'hero'      => $base . '1470229722913-7c0e2dbbafd3' . $q,
		'class'     => $base . '1533928298208-27ff66555d8d' . $q,
		'social'    => $base . '1504609773096-104ff2c73ba4' . $q,
		'venue'     => $base . '1519671482749-fd09be7ccebf' . $q,
		'wedding'   => $base . '1464349095431-e9a21285b5f3' . $q,
		'party'     => $base . '1516450360452-9312f5e86fc7' . $q,
		'corporate' => $base . '1511795409834-ef04bbd61622' . $q,
		'bar'       => $base . '1530103862676-de8c9debad1d' . $q,
		'detail'    => $base . '1478146059778-26028b07395a' . $q,
		'crowd'     => $base . '1519225421980-715cb0215aed' . $q,
		'food'      => $base . '1522337660859-02fbefca4702' . $q,
	);
	return apply_filters( 'sanctuary_image_sources', $map );
}

/**
 * Marker used inside page definitions; resolved to a real attachment at build time.
 */
function snc_img( $slot ) {
	return array( '_snc_slot' => $slot );
}

/**
 * Download one URL into the Media Library, returning an attachment ID (0 on failure).
 */
function snc_sideload_url( $url, $filename, $desc ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = download_url( $url, 30 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}
	$file = array( 'name' => $filename, 'tmp_name' => $tmp );
	$id   = media_handle_sideload( $file, 0, $desc );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return 0;
	}
	return (int) $id;
}

/**
 * Resolve a slot to a MEDIA control value ({id,url}), downloading + caching once.
 */
function snc_media_for_slot( $slot ) {
	$map = snc_image_sources();
	if ( empty( $map[ $slot ] ) ) {
		return array();
	}
	$cache = get_option( 'sanctuary_seeded_images', array() );
	if ( ! empty( $cache[ $slot ] ) && get_post( $cache[ $slot ] ) ) {
		$id = (int) $cache[ $slot ];
		return array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) );
	}
	$id = snc_sideload_url( $map[ $slot ], 'sanctuary-' . sanitize_file_name( $slot ) . '.jpg', 'The Sanctuary — ' . $slot );
	if ( ! $id ) {
		return array();
	}
	$cache[ $slot ] = $id;
	update_option( 'sanctuary_seeded_images', $cache );
	return array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) );
}

/**
 * Recursively replace `_snc_slot` markers in a settings tree with real media.
 */
function snc_resolve_images( $value ) {
	if ( is_array( $value ) ) {
		if ( isset( $value['_snc_slot'] ) ) {
			return snc_media_for_slot( $value['_snc_slot'] );
		}
		$out = array();
		foreach ( $value as $k => $v ) {
			$out[ $k ] = snc_resolve_images( $v );
		}
		return $out;
	}
	return $value;
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
				'image'  => snc_img( 'hero' ),
			) ),
			array( 'type' => 'sanctuary_stat_strip', 'settings' => array() ),
			array( 'type' => 'sanctuary_fork', 'settings' => array(
				'cards' => snc_rep( array(
					array( 'gradient' => 'g1', 'image' => snc_img( 'class' ), 'title' => 'Learn to dance & nights out', 'text' => 'Friendly classes for every level, plus social nights with a full bar. Come on your own or bring friends.', 'link_text' => "See classes & what's on →", 'link' => array( 'url' => '#classes' ), 'image_label' => 'A lively class in motion' ),
					array( 'gradient' => 'g4', 'image' => snc_img( 'venue' ), 'title' => 'Celebrate & hire the venue', 'text' => 'A bright, versatile space for weddings, parties and work events — with a sprung floor, a bar and room to make a day of it.', 'link_text' => 'Explore venue hire →', 'link' => array( 'url' => '#hire' ), 'image_label' => 'An event set up in the space' ),
				) ),
			) ),
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
				'image'       => snc_img( 'social' ),
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
				'image'       => snc_img( 'wedding' ),
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
				'image'       => snc_img( 'venue' ),
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
			array( 'type' => 'sanctuary_use_cases', 'settings' => array(
				'cards' => snc_rep( array(
					array( 'gradient' => 'g1', 'image' => snc_img( 'wedding' ), 'title' => 'Weddings', 'link_text' => 'See weddings →' ),
					array( 'gradient' => 'g5', 'image' => snc_img( 'party' ), 'title' => 'Parties', 'link_text' => 'See parties →' ),
					array( 'gradient' => 'g4', 'image' => snc_img( 'corporate' ), 'title' => 'Corporate', 'link_text' => 'See corporate →' ),
					array( 'gradient' => 'g2', 'image' => snc_img( 'crowd' ), 'title' => 'Christenings & more', 'link_text' => 'Enquire →' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_included_list', 'settings' => array( 'bg' => 'cream2' ) ),
			array( 'type' => 'sanctuary_gallery', 'settings' => array(
				'items' => snc_rep( array(
					array( 'gradient' => 'g1', 'image' => snc_img( 'venue' ), 'label' => 'Wide shot of a dressed event', 'big' => 'yes' ),
					array( 'gradient' => 'g4', 'image' => snc_img( 'detail' ), 'label' => 'Detail' ),
					array( 'gradient' => 'g5', 'image' => snc_img( 'crowd' ), 'label' => 'Guests / floor' ),
					array( 'gradient' => 'g2', 'image' => snc_img( 'bar' ), 'label' => 'Bar' ),
					array( 'gradient' => 'g1', 'image' => snc_img( 'party' ), 'label' => 'Setup' ),
				) ),
			) ),
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
				'image'       => snc_img( 'party' ),
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
				'image'       => snc_img( 'wedding' ),
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
				'image'       => snc_img( 'corporate' ),
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
				'image'       => snc_img( 'party' ),
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

	/* --------------------------------------------------------------- TIMETABLE */
	$pages['timetable'] = array(
		'title'    => 'Timetable',
		'is_front' => false,
		'widgets'  => array(
			array( 'type' => 'sanctuary_timetable_intro', 'settings' => array() ),
			array( 'type' => 'sanctuary_timetable', 'settings' => array(
				'day_label' => 'Monday',
				'accent'    => 'coral',
				'classes'   => snc_rep( array(
					snc_tt_row( '6:00–6:45pm', 'Absolute Beginners Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=68412778' ),
					snc_tt_row( '6:45–7:30pm', 'Ballroom & Latin Formation Team', 'bl', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=95115322' ),
					snc_tt_row( '7:30–8:15pm', 'Beginners Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=43431031' ),
					snc_tt_row( '8:15–9:00pm', 'Improvers Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/adultballroomandlatinimprovers' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_timetable', 'settings' => array(
				'day_label' => 'Tuesday',
				'accent'    => 'teal',
				'classes'   => snc_rep( array(
					snc_tt_row( '6:15–7:00pm', 'Absolute Beginners Line Dancing', 'ld', 'https://jheventsxthesanctuary.as.me/schedule/817f9984/?appointmentTypeIds[]=82211679' ),
					snc_tt_row( '7:00–8:00pm', 'Beginners Line Dancing', 'ld', 'https://jheventsxthesanctuary.as.me/schedule/817f9984/?appointmentTypeIds[]=78435908' ),
					snc_tt_row( '8:00–9:00pm', 'Improvers Line Dancing', 'ld', 'https://jheventsxthesanctuary.as.me/schedule/817f9984/?appointmentTypeIds[]=78435949' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_timetable', 'settings' => array(
				'day_label' => 'Wednesday',
				'accent'    => 'amber',
				'classes'   => snc_rep( array(
					snc_tt_row( '7:30–9:30pm', 'Open Ballroom & Latin Practice', 'pr', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=48207391' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_timetable', 'settings' => array(
				'day_label' => 'Thursday',
				'accent'    => 'coral-deep',
				'classes'   => snc_rep( array(
					snc_tt_row( '10:00–10:45am', 'Beginners Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=43431031' ),
					snc_tt_row( '6:00–6:45pm', 'Improvers Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/adultballroomandlatinimprovers' ),
					snc_tt_row( '6:45–7:30pm', 'Beginners Sequence Class', 'sq', 'https://thesanctuarybookinglink.as.me/?appointmentType=73920408' ),
					snc_tt_row( '7:30–8:15pm', 'Advanced Ballroom & Latin', 'bl', 'https://thesanctuarybookinglink.as.me/schedule/d1c7a59a/?appointmentTypeIds[]=54353079' ),
					snc_tt_row( '8:15–9:00pm', 'Beginners Line Dancing', 'ld', 'https://jheventsxthesanctuary.as.me/schedule/817f9984/?appointmentTypeIds[]=82211679' ),
				) ),
			) ),
			array( 'type' => 'sanctuary_closing_cta', 'settings' => array(
				'heading'   => 'Find your class, tap Book.',
				'text'      => "Every class links straight to its own booking page — pick a time that works and you're set.",
				'btn1_text' => '',
				'btn2_text' => '',
			) ),
		),
	);

	return $pages;
}

/**
 * Create/update pages. Returns a log array of slug => action.
 *
 * @param array|null $only_slugs Limit the rebuild to these slugs (e.g.
 *                                array( 'timetable' ) ). Null (default)
 *                                rebuilds every page defined in
 *                                snc_page_definitions() — use deliberately,
 *                                since it overwrites _elementor_data on
 *                                EVERY page, including any hand-edited in
 *                                the Elementor editor since it was last built.
 */
function snc_build_pages( $only_slugs = null ) {
	$log = array();

	// Make sure flexbox containers render (default on current Elementor; this
	// keeps older installs from falling back to the legacy layout).
	update_option( 'elementor_experiment-container', 'active' );

	foreach ( snc_page_definitions() as $slug => $def ) {
		if ( null !== $only_slugs && ! in_array( $slug, $only_slugs, true ) ) {
			continue;
		}

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

		$widgets = snc_resolve_images( $def['widgets'] );
		$data    = snc_build_tree( $widgets );

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
			// A specific "page" field means one page's button was clicked;
			// its absence means the "rebuild everything" button was.
			$requested = isset( $_POST['snc_page'] ) ? sanitize_key( wp_unslash( $_POST['snc_page'] ) ) : '';
			$only      = ( '' !== $requested ) ? array( $requested ) : null;

			$log = snc_build_pages( $only );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Done:', 'sanctuary' ) . ' ';
			foreach ( $log as $slug => $action ) {
				echo esc_html( "$slug: $action  " );
			}
			echo '</p></div>';
		}
	}

	echo '<p>' . esc_html__( 'Rebuilding a page overwrites its Elementor content with the definition below — including any manual edits made in the editor since it was last built. Rebuild only the page you actually changed; the other pages are left completely untouched.', 'sanctuary' ) . '</p>';

	echo '<table class="widefat striped" style="max-width:640px;"><tbody>';
	foreach ( snc_page_definitions() as $slug => $def ) {
		$existing = get_page_by_path( $slug );
		$status   = $existing
			? sprintf( /* translators: %s: last-modified date */ esc_html__( 'exists — last modified %s', 'sanctuary' ), esc_html( get_the_modified_date( '', $existing ) ) )
			: esc_html__( 'not created yet', 'sanctuary' );
		echo '<tr>';
		echo '<td><strong>' . esc_html( $def['title'] ) . '</strong><br><span style="color:#666;">/' . esc_html( $slug ) . '/ — ' . $status . '</span></td>';
		echo '<td style="text-align:right;">';
		echo '<form method="post" style="display:inline;">';
		wp_nonce_field( 'snc_build_pages' );
		echo '<input type="hidden" name="snc_page" value="' . esc_attr( $slug ) . '">';
		echo '<button class="button" name="snc_build" value="1">' . ( $existing ? esc_html__( 'Rebuild this page', 'sanctuary' ) : esc_html__( 'Create this page', 'sanctuary' ) ) . '</button>';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';

	echo '<p style="margin-top:1.6rem;"><details><summary style="cursor:pointer;color:#a00;">' . esc_html__( 'Rebuild every page at once', 'sanctuary' ) . '</summary>';
	echo '<p>' . esc_html__( 'This overwrites ALL pages listed above in one go, including any you\'ve hand-edited. Only use this for a full reset.', 'sanctuary' ) . '</p>';
	echo '<form method="post">';
	wp_nonce_field( 'snc_build_pages' );
	echo '<button class="button" name="snc_build" value="1">' . esc_html__( 'Rebuild ALL pages', 'sanctuary' ) . '</button>';
	echo '</form></details></p>';

	echo '</div>';
}

/* ------------------------------------------------------------- WP-CLI ----- */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'sanctuary build-pages', function ( $args, $assoc_args ) {
		$only = null;
		if ( ! empty( $assoc_args['page'] ) ) {
			$only = array_map( 'sanitize_key', explode( ',', $assoc_args['page'] ) );
		}

		$log = snc_build_pages( $only );
		foreach ( $log as $slug => $action ) {
			WP_CLI::log( "$slug: $action" );
		}
		WP_CLI::success( 'Pages built.' );
	}, array(
		'synopsis' => array(
			array(
				'type'        => 'assoc',
				'name'        => 'page',
				'optional'    => true,
				'description' => 'Comma-separated slug(s) to rebuild (e.g. --page=timetable). Omit to rebuild every page — overwrites any hand-edited pages too.',
			),
		),
	) );
}
