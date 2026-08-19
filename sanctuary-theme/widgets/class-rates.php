<?php
/**
 * Rates Band widget — the dark, centred "from £X" figure used on both hire pages.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_Rates extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_rates';
	}

	public function get_title() {
		return __( 'Rates Band', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-price-tag';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Rates', 'sanctuary' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Straightforward pricing.', 'sanctuary' ) ) );
		$this->add_control( 'figure', array( 'label' => __( 'Figure', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'From £[XXX]', 'sanctuary' ) ) );
		$this->add_control( 'note', array( 'label' => __( 'Note', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'A "from" figure filters serious enquiries from time-wasters.', 'sanctuary' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="snc-section snc snc-rates">
			<div class="snc-wrap">
				<?php if ( $s['eyebrow'] ) : ?><p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( $s['title'] ) : ?><h2 class="title"><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
				<?php if ( $s['figure'] ) : ?><div class="figure"><?php echo esc_html( $s['figure'] ); ?></div><?php endif; ?>
				<?php if ( $s['note'] ) : ?><p><?php echo wp_kses_post( $s['note'] ); ?></p><?php endif; ?>
			</div>
		</section>
		<?php
	}
}
