<?php
/**
 * Timetable Intro widget — centered hero + a "prices to be added" style
 * callout + a category legend (Ballroom & Latin / Line Dancing / Sequence /
 * Practice), for the top of the adults timetable page.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_TimetableIntro extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_timetable_intro';
	}

	public function get_title() {
		return __( 'Timetable Intro', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-time-line';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'Classes', 'sanctuary' ),
			__( 'Adults Dance Timetable', 'sanctuary' ),
			__( 'Ballroom, Latin and Line Dancing — from absolute beginners to advanced. Find a class, tap Book, and you\'ll go straight to the right booking page.', 'sanctuary' )
		);

		$this->add_control( 'callout', array(
			'label'       => __( 'Callout', 'sanctuary' ),
			'description' => __( 'Optional. HTML allowed (e.g. <b>bold</b> a key word). Leave blank to hide.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( '<b>Prices to be added.</b> The live source page doesn\'t list prices, so the price column is a placeholder throughout — add real prices before publishing.', 'sanctuary' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section( 'legend_sec', array( 'label' => __( 'Legend', 'sanctuary' ) ) );

		$rep = new Repeater();
		$rep->add_control( 'label', array( 'label' => __( 'Label', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Ballroom & Latin', 'sanctuary' ) ) );
		$rep->add_control( 'category', array(
			'label'   => __( 'Colour', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'bl',
			'options' => array(
				'bl' => __( 'Coral (Ballroom & Latin)', 'sanctuary' ),
				'ld' => __( 'Teal (Line Dancing)', 'sanctuary' ),
				'sq' => __( 'Amber (Sequence)', 'sanctuary' ),
				'pr' => __( 'Ink (Practice)', 'sanctuary' ),
			),
		) );

		$this->add_control( 'legend_items', array(
			'label'       => __( 'Legend chips', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => array(
				array( 'label' => 'Ballroom & Latin', 'category' => 'bl' ),
				array( 'label' => 'Line Dancing', 'category' => 'ld' ),
				array( 'label' => 'Sequence', 'category' => 'sq' ),
				array( 'label' => 'Practice', 'category' => 'pr' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="snc-section snc snc-hero snc-hero--center">
			<div class="snc-wrap">
				<?php $this->render_heading( $s['eyebrow'], $s['title'], '' ); ?>
				<?php if ( $s['lede'] ) : ?><p class="sub"><?php echo esc_html( $s['lede'] ); ?></p><?php endif; ?>

				<?php if ( $s['callout'] ) : ?>
					<div class="snc-callout"><?php echo wp_kses_post( $s['callout'] ); ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $s['legend_items'] ) ) : ?>
					<div class="snc-legend">
						<?php foreach ( $s['legend_items'] as $item ) : ?>
							<span class="chip chip-<?php echo esc_attr( $item['category'] ); ?>"><?php echo esc_html( $item['label'] ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
