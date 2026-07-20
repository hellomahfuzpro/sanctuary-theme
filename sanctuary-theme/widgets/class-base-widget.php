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

abstract class Sanctuary_Base_Widget extends Widget_Base {

	public function get_categories() {
		return array( 'sanctuary' );
	}

	public function get_keywords() {
		return array( 'sanctuary', 'section' );
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
