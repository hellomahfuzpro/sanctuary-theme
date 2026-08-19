<?php
/**
 * Closing CTA Band widget — the full-width coloured call-to-action that ends
 * every page. Coral or teal variants, one or two buttons.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_ClosingCta extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_closing_cta';
	}

	public function get_title() {
		return __( 'Closing CTA Band', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'color', array(
			'label'   => __( 'Colour', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'coral',
			'options' => array(
				'coral' => __( 'Coral', 'sanctuary' ),
				'teal'  => __( 'Teal', 'sanctuary' ),
			),
		) );
		$this->add_control( 'heading', array(
			'label'   => __( 'Heading', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Come along this week.', 'sanctuary' ),
		) );
		$this->add_control( 'text', array(
			'label'   => __( 'Text', 'sanctuary' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( "Book a beginners' class, or ask us about hiring the space — we'd love to have you.", 'sanctuary' ),
		) );

		$this->add_control( 'btn1_text', array( 'label' => __( 'Primary button', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Book a class', 'sanctuary' ) ) );
		$this->add_control( 'btn1_link', array( 'label' => __( 'Primary link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$this->add_control( 'btn2_text', array( 'label' => __( 'Secondary button', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'btn2_link', array( 'label' => __( 'Secondary link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="snc-section snc snc-closing snc-closing--<?php echo esc_attr( $s['color'] ); ?>">
			<div class="snc-wrap">
				<?php if ( $s['heading'] ) : ?><h2><?php echo esc_html( $s['heading'] ); ?></h2><?php endif; ?>
				<?php if ( $s['text'] ) : ?><p><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
				<div class="snc-cta-row">
					<?php
					$this->render_button( $s['btn1_text'], $s['btn1_link'], 'btn-primary' );
					$this->render_button( $s['btn2_text'], $s['btn2_link'], 'btn-ghost' );
					?>
				</div>
			</div>
		</section>
		<?php
	}
}
