<?php
/**
 * FAQ widget — the "first-timer questions" definition list.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Faq extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_faq';
	}

	public function get_title() {
		return __( 'FAQ', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-help-o';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'Good to know', 'sanctuary' ),
			__( 'First-timer questions.', 'sanctuary' ),
			''
		);

		$rep = new Repeater();
		$rep->add_control( 'q', array( 'label' => __( 'Question', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Do I need a partner?', 'sanctuary' ) ) );
		$rep->add_control( 'a', array( 'label' => __( 'Answer', 'sanctuary' ), 'description' => __( 'HTML allowed.', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'No — come on your own, most people do.', 'sanctuary' ) ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Questions', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ q }}}',
			'default'     => array(
				array( 'q' => 'Do I need a partner?', 'a' => 'No. Come on your own — most people do.' ),
				array( 'q' => 'What should I wear?', 'a' => 'Comfy clothes and flat or low shoes.' ),
				array( 'q' => 'How do I pay?', 'a' => 'Book and pay online through the timetable above.' ),
				array( 'q' => 'Is there parking?', 'a' => 'Nearby parking / on-street details to be added.' ),
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
				<dl class="snc-faq">
					<?php foreach ( (array) $s['items'] as $item ) : ?>
						<dt><?php echo esc_html( $item['q'] ); ?></dt>
						<dd><?php echo wp_kses_post( $item['a'] ); ?></dd>
					<?php endforeach; ?>
				</dl>
			</div>
		</section>
		<?php
	}
}
