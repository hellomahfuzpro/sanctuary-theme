<?php
/**
 * Timetable widget — one day's class listing (time / class name / category
 * chip / price / booking link), styled as a coloured card. Stack one instance
 * per day on the timetable page, same pattern as Event Cards / FAQ.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Timetable extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_timetable';
	}

	public function get_title() {
		return __( 'Timetable Day', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'day_label', array(
			'label'   => __( 'Day', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Monday', 'sanctuary' ),
		) );

		$this->add_control( 'accent', array(
			'label'   => __( 'Accent colour', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'coral',
			'options' => array(
				'coral'      => __( 'Coral', 'sanctuary' ),
				'teal'       => __( 'Teal', 'sanctuary' ),
				'amber'      => __( 'Amber', 'sanctuary' ),
				'coral-deep' => __( 'Coral Deep', 'sanctuary' ),
			),
		) );

		$rep = new Repeater();
		$rep->add_control( 'time', array( 'label' => __( 'Time', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( '6:00–6:45pm', 'sanctuary' ) ) );
		$rep->add_control( 'name', array( 'label' => __( 'Class name', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Absolute Beginners Ballroom & Latin', 'sanctuary' ) ) );
		$rep->add_control( 'category', array(
			'label'   => __( 'Category', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'bl',
			'options' => array(
				'bl' => __( 'Ballroom & Latin', 'sanctuary' ),
				'ld' => __( 'Line Dancing', 'sanctuary' ),
				'sq' => __( 'Sequence', 'sanctuary' ),
				'pr' => __( 'Practice', 'sanctuary' ),
			),
		) );
		$rep->add_control( 'price', array(
			'label'       => __( 'Price', 'sanctuary' ),
			'description' => __( 'Free text — use £[X] as a placeholder until real prices are confirmed.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( '£[X]', 'sanctuary' ),
		) );
		$rep->add_control( 'book_label', array( 'label' => __( 'Button label', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Book', 'sanctuary' ) ) );
		$rep->add_control( 'book_link', array( 'label' => __( 'Booking link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#', 'is_external' => true ) ) );

		$this->add_control( 'classes', array(
			'label'       => __( 'Classes', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ time }}} — {{{ name }}}',
			'default'     => array(
				array( 'time' => '6:00–6:45pm', 'name' => 'Absolute Beginners Ballroom & Latin', 'category' => 'bl', 'price' => '£[X]', 'book_label' => 'Book', 'book_link' => array( 'url' => '#', 'is_external' => true ) ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$category_labels = array(
			'bl' => __( 'Ballroom & Latin', 'sanctuary' ),
			'ld' => __( 'Line Dancing', 'sanctuary' ),
			'sq' => __( 'Sequence', 'sanctuary' ),
			'pr' => __( 'Practice', 'sanctuary' ),
		);
		?>
		<section class="snc-section snc snc-timetable-day">
			<div class="snc-wrap">
				<div class="day">
					<div class="day-head accent-<?php echo esc_attr( $s['accent'] ); ?>"><?php echo esc_html( $s['day_label'] ); ?></div>
					<div class="tt-head">
						<div><?php esc_html_e( 'Time', 'sanctuary' ); ?></div>
						<div><?php esc_html_e( 'Class', 'sanctuary' ); ?></div>
						<div><?php esc_html_e( 'Price', 'sanctuary' ); ?></div>
						<div><?php esc_html_e( 'Book', 'sanctuary' ); ?></div>
					</div>
					<?php foreach ( (array) $s['classes'] as $row ) : ?>
						<div class="tt-row">
							<div class="tt-time"><?php echo esc_html( $row['time'] ); ?></div>
							<div class="tt-class">
								<?php echo esc_html( $row['name'] ); ?>
								<span class="sub">
									<span class="chip chip-<?php echo esc_attr( $row['category'] ); ?>">
										<?php echo esc_html( isset( $category_labels[ $row['category'] ] ) ? $category_labels[ $row['category'] ] : '' ); ?>
									</span>
								</span>
							</div>
							<div class="tt-priceline">
								<div class="tt-price"><span class="ph-price"><?php echo esc_html( $row['price'] ); ?></span></div>
								<?php $this->render_button( $row['book_label'], $row['book_link'], 'btn-primary' ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
