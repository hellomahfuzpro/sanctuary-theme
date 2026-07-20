<?php
/**
 * Use-Case Grid widget — the venue-hire "what people hire it for" 4-up image
 * cards, each with a title and a link.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_UseCases extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_use_cases';
	}

	public function get_title() {
		return __( 'Use-Case Grid', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-gallery-justified';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'What people hire it for', 'sanctuary' ),
			__( 'Built for the occasion.', 'sanctuary' ),
			''
		);

		$rep = new Repeater();
		$rep->add_control( 'image', array( 'label' => __( 'Image', 'sanctuary' ), 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'gradient', array(
			'label'   => __( 'Placeholder gradient', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'g1',
			'options' => array( 'g1' => 'Coral→Amber', 'g2' => 'Teal→Amber', 'g3' => 'Coral', 'g4' => 'Teal', 'g5' => 'Amber→Coral' ),
		) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Weddings', 'sanctuary' ) ) );
		$rep->add_control( 'link_text', array( 'label' => __( 'Link text', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'See weddings →', 'sanctuary' ) ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(
				array( 'gradient' => 'g1', 'title' => 'Weddings', 'link_text' => 'See weddings →' ),
				array( 'gradient' => 'g5', 'title' => 'Parties', 'link_text' => 'See parties →' ),
				array( 'gradient' => 'g4', 'title' => 'Corporate', 'link_text' => 'See corporate →' ),
				array( 'gradient' => 'g2', 'title' => 'Christenings & more', 'link_text' => 'Enquire →' ),
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
				<div class="snc-uc-grid" style="margin-top:1.5rem">
					<?php foreach ( (array) $s['cards'] as $card ) : ?>
						<div class="snc-uc">
							<?php $this->render_media( $card['image'], $card['gradient'], $card['title'] ); ?>
							<div class="snc-uc-b">
								<h3><?php echo esc_html( $card['title'] ); ?></h3>
								<?php if ( $card['link_text'] ) : ?>
									<a href="<?php echo esc_url( ! empty( $card['link']['url'] ) ? $card['link']['url'] : '#' ); ?>"><?php echo esc_html( $card['link_text'] ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
