<?php
/**
 * Style Cards widget — "Find your style" three cards, each with a coloured top
 * bar, a title and a short description.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_StyleCards extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_style_cards';
	}

	public function get_title() {
		return __( 'Style Cards', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-price-list';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'What you can learn', 'sanctuary' ),
			__( 'Find your style.', 'sanctuary' ),
			__( 'Three ways to dance, all taught from beginner level up.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'color', array(
			'label'   => __( 'Top bar colour', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 's1',
			'options' => array( 's1' => __( 'Coral', 'sanctuary' ), 's2' => __( 'Amber', 'sanctuary' ), 's3' => __( 'Teal', 'sanctuary' ) ),
		) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Ballroom', 'sanctuary' ) ) );
		$rep->add_control( 'text', array( 'label' => __( 'Text', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Elegant, classic and easier than it looks — waltz, quickstep and more.', 'sanctuary' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(
				array( 'color' => 's1', 'title' => 'Ballroom', 'text' => 'Elegant, classic and easier than it looks — waltz, quickstep and more.' ),
				array( 'color' => 's2', 'title' => 'Latin', 'text' => 'Lively and fun — cha-cha, jive and rumba to get you moving.' ),
				array( 'color' => 's3', 'title' => 'Line', 'text' => 'No partner required, easy to pick up, and a great first class.' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="snc-section snc">
			<div class="snc-wrap">
				<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
				<div class="snc-styles">
					<?php foreach ( (array) $s['cards'] as $card ) : ?>
						<div class="snc-style <?php echo esc_attr( $card['color'] ); ?>">
							<div class="top"></div>
							<div class="b">
								<h3><?php echo esc_html( $card['title'] ); ?></h3>
								<p><?php echo esc_html( $card['text'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
