<?php
/**
 * Base class for every Sanctuary section widget.
 *
 * Provides the shared Elementor category plus small render helpers so the
 * individual widget files stay focused on their own controls & markup.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

abstract class Sanctuary_Base_Widget extends Widget_Base {

	public function get_categories() {
		return array( 'sanctuary' );
	}

	public function get_keywords() {
		return array( 'sanctuary', 'section' );
	}

	/**
	 * Every widget gets its own content controls plus the shared Style tab.
	 *
	 * Widgets define `register_content_controls()` (their own Content tab);
	 * the Style tab below is appended automatically so it stays consistent
	 * across all of them and new widgets get it for free.
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->add_style_controls();
	}

	/**
	 * Each widget's own Content-tab controls. Overridden by every widget.
	 */
	protected function register_content_controls() {}

	/**
	 * Shared Style tab: alignment, spacing, background, colours, border.
	 *
	 * Every control defaults to empty on purpose — an unset control emits no
	 * CSS at all, so existing pages render exactly as they did before these
	 * controls existed. Elementor stores only what the editor actually sets,
	 * and generates the CSS per widget instance from the `selectors` below,
	 * scoped to `{{WRAPPER}}` (that instance's own element).
	 *
	 * All 21 widgets render a root element carrying the `.snc` class (either
	 * `<section class="snc-section snc …">` or `<div class="snc">`), so one
	 * selector reaches every widget's outermost box.
	 */
	protected function add_style_controls() {
		$root = '{{WRAPPER}} .snc';

		/* ---- Layout & spacing ---- */
		$this->start_controls_section( 'snc_style_layout', array(
			'label' => __( 'Layout & Spacing', 'sanctuary' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_responsive_control( 'snc_align', array(
			'label'     => __( 'Text alignment', 'sanctuary' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => array(
				'left'   => array( 'title' => __( 'Left', 'sanctuary' ), 'icon' => 'eicon-text-align-left' ),
				'center' => array( 'title' => __( 'Center', 'sanctuary' ), 'icon' => 'eicon-text-align-center' ),
				'right'  => array( 'title' => __( 'Right', 'sanctuary' ), 'icon' => 'eicon-text-align-right' ),
			),
			'default'   => '',
			'selectors' => array( $root => 'text-align: {{VALUE}};' ),
		) );

		$this->add_responsive_control( 'snc_padding', array(
			'label'      => __( 'Padding', 'sanctuary' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', 'rem', '%' ),
			'selectors'  => array( $root => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_responsive_control( 'snc_margin', array(
			'label'      => __( 'Margin', 'sanctuary' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', 'rem', '%' ),
			'selectors'  => array( $root => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->end_controls_section();

		/* ---- Background ---- */
		$this->start_controls_section( 'snc_style_bg', array(
			'label' => __( 'Background', 'sanctuary' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Background::get_type(), array(
			'name'     => 'snc_bg',
			'types'    => array( 'classic', 'gradient' ),
			'selector' => $root,
		) );

		$this->end_controls_section();

		/* ---- Colours ---- */
		$this->start_controls_section( 'snc_style_colors', array(
			'label' => __( 'Colours', 'sanctuary' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_control( 'snc_heading_color', array(
			'label'     => __( 'Heading colour', 'sanctuary' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				"{$root} .title, {$root} h1, {$root} h2, {$root} h3, {$root} h4" => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'snc_text_color', array(
			'label'     => __( 'Text colour', 'sanctuary' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array(
				"{$root}, {$root} p, {$root} li, {$root} dd, {$root} .lede, {$root} .sub" => 'color: {{VALUE}};',
			),
		) );

		$this->add_control( 'snc_eyebrow_color', array(
			'label'     => __( 'Eyebrow colour', 'sanctuary' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( "{$root} .eyebrow" => 'color: {{VALUE}};' ),
		) );

		$this->add_control( 'snc_link_color', array(
			'label'     => __( 'Link colour', 'sanctuary' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( "{$root} a:not(.btn)" => 'color: {{VALUE}};' ),
		) );

		$this->end_controls_section();

		/* ---- Border ---- */
		$this->start_controls_section( 'snc_style_border', array(
			'label' => __( 'Border & Shadow', 'sanctuary' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		) );

		$this->add_group_control( Group_Control_Border::get_type(), array(
			'name'     => 'snc_border',
			'selector' => $root,
		) );

		$this->add_responsive_control( 'snc_radius', array(
			'label'      => __( 'Border radius', 'sanctuary' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'  => array( $root => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
			'name'     => 'snc_shadow',
			'selector' => $root,
		) );

		$this->end_controls_section();
	}

	/**
	 * Render an image control's value, or the mockup gradient placeholder when
	 * empty — so unfinished pages read clearly in the editor.
	 *
	 * @param array  $image      Elementor MEDIA control value (id/url).
	 * @param string $gradient   Placeholder gradient class: g1..g5.
	 * @param string $label      Placeholder caption.
	 * @param string $extra      Extra classes for the wrapper.
	 */
	protected function render_media( $image, $gradient = 'g1', $label = '', $extra = '' ) {
		$url = '';
		if ( ! empty( $image['id'] ) ) {
			$url = wp_get_attachment_image_url( (int) $image['id'], 'large' );
		} elseif ( ! empty( $image['url'] ) ) {
			$url = $image['url'];
		}

		if ( $url ) {
			printf(
				'<img src="%1$s" alt="%2$s" loading="lazy" class="%3$s">',
				esc_url( $url ),
				esc_attr( $label ),
				esc_attr( $extra )
			);
			return;
		}

		printf(
			'<div class="ph %1$s %2$s" aria-label="%3$s">%3$s</div>',
			esc_attr( $gradient ),
			esc_attr( $extra ),
			esc_html( $label )
		);
	}

	/**
	 * Render a button from a text + Elementor URL control array.
	 *
	 * @param string $text  Button label.
	 * @param array  $link  Elementor URL control value.
	 * @param string $style btn-primary | btn-amber | btn-ghost.
	 */
	protected function render_button( $text, $link, $style = 'btn-primary' ) {
		if ( empty( $text ) ) {
			return;
		}
		$url        = ! empty( $link['url'] ) ? $link['url'] : '#';
		$target     = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
		$nofollow   = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
		printf(
			'<a href="%1$s"%2$s%3$s class="btn %4$s">%5$s</a>',
			esc_url( $url ),
			$target, // phpcs:ignore
			$nofollow, // phpcs:ignore
			esc_attr( $style ),
			esc_html( $text )
		);
	}

	/**
	 * Standard eyebrow + title + lede heading block.
	 */
	protected function render_heading( $eyebrow, $title, $lede ) {
		if ( $eyebrow ) {
			printf( '<p class="eyebrow">%s</p>', esc_html( $eyebrow ) );
		}
		if ( $title ) {
			printf( '<h2 class="title">%s</h2>', wp_kses_post( $title ) );
		}
		if ( $lede ) {
			printf( '<p class="lede">%s</p>', wp_kses_post( $lede ) );
		}
	}

	/**
	 * Add a reusable eyebrow/title/lede control group to a widget.
	 */
	protected function add_heading_controls( $default_eyebrow = '', $default_title = '', $default_lede = '' ) {
		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => $default_eyebrow,
		) );
		$this->add_control( 'title', array(
			'label'   => __( 'Title', 'sanctuary' ),
			'type'    => Controls_Manager::TEXTAREA,
			'rows'    => 2,
			'default' => $default_title,
		) );
		$this->add_control( 'lede', array(
			'label'   => __( 'Lede', 'sanctuary' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => $default_lede,
		) );
	}
}
