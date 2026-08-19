<?php
/**
 * Section Heading widget — eyebrow + title + lede.
 * Small helper used standalone atop sections that don't bundle their own heading.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_Heading extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_heading';
	}

	public function get_title() {
		return __( 'Section Heading', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-t-letter';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array(
			'label' => __( 'Heading', 'sanctuary' ),
		) );

		$this->add_heading_controls(
			__( 'Eyebrow', 'sanctuary' ),
			__( 'Section title goes here.', 'sanctuary' ),
			__( 'A short supporting sentence for the section.', 'sanctuary' )
		);

		$this->add_control( 'align', array(
			'label'   => __( 'Alignment', 'sanctuary' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => array(
				'left'   => array( 'title' => __( 'Left', 'sanctuary' ), 'icon' => 'eicon-text-align-left' ),
				'center' => array( 'title' => __( 'Center', 'sanctuary' ), 'icon' => 'eicon-text-align-center' ),
			),
			'default' => 'left',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$style = 'center' === $s['align'] ? ' style="text-align:center"' : '';
		echo '<div class="snc"' . $style . '>'; // phpcs:ignore
		$this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] );
		echo '</div>';
	}
}
