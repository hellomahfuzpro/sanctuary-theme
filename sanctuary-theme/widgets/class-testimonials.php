<?php
/**
 * Testimonials widget — three-up quote cards with an optional occasion tag.
 * Used on every page.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Testimonials extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_testimonials';
	}

	public function get_title() {
		return __( 'Testimonials', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'People love it here', 'sanctuary' ),
			__( "Don't just take our word for it.", 'sanctuary' ),
			__( 'Real testimonials carry the most weight — ideally one per audience.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'tag', array( 'label' => __( 'Tag (optional)', 'sanctuary' ), 'type' => Controls_Manager::TEXT ) );
		$rep->add_control( 'quote', array( 'label' => __( 'Quote', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'A short, warm quote about a first class or a social night.', 'sanctuary' ) ) );
		$rep->add_control( 'by', array( 'label' => __( 'Attribution', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( '— [Name], [class / social]', 'sanctuary' ) ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Quotes', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ by }}}',
			'default'     => array(
				array( 'quote' => 'A short, warm quote about a first class or a social night.', 'by' => '— [Name], [class / social]' ),
				array( 'quote' => 'A short quote about a wedding or event held here.', 'by' => '— [Name], [event]' ),
				array( 'quote' => 'A short quote about the welcome / the people.', 'by' => '— [Name]' ),
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
				<div class="snc-testi">
					<?php foreach ( (array) $s['items'] as $item ) : ?>
						<div class="snc-quote">
							<?php if ( ! empty( $item['tag'] ) ) : ?><span class="tag"><?php echo esc_html( $item['tag'] ); ?></span><?php endif; ?>
							<p><?php echo esc_html( $item['quote'] ); ?></p>
							<div class="by"><?php echo esc_html( $item['by'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
