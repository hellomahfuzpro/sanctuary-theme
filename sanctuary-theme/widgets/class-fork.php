<?php
/**
 * Promo Cards (Fork) widget — the homepage "Two ways in" pair. Heading + a
 * repeater of image cards with title, text and a text link.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Fork extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_fork';
	}

	public function get_title() {
		return __( 'Promo Cards (Fork)', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'Two ways in', 'sanctuary' ),
			__( "Whether you're learning or celebrating.", 'sanctuary' ),
			__( 'Most people come for one of two things. Pick your path — or do both.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'image', array( 'label' => __( 'Image', 'sanctuary' ), 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'gradient', array(
			'label'   => __( 'Placeholder gradient', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'g1',
			'options' => array( 'g1' => 'Coral→Amber', 'g2' => 'Teal→Amber', 'g3' => 'Coral', 'g4' => 'Teal', 'g5' => 'Amber→Coral' ),
		) );
		$rep->add_control( 'image_label', array( 'label' => __( 'Placeholder caption', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'A lively class in motion', 'sanctuary' ) ) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Learn to dance & nights out', 'sanctuary' ) ) );
		$rep->add_control( 'text', array( 'label' => __( 'Text', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Friendly classes for every level, plus social nights with a full bar.', 'sanctuary' ) ) );
		$rep->add_control( 'link_text', array( 'label' => __( 'Link text', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( "See classes & what's on →", 'sanctuary' ) ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#classes' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(
				array( 'gradient' => 'g1', 'title' => 'Learn to dance & nights out', 'text' => 'Friendly classes for every level, plus social nights with a full bar. Come on your own or bring friends.', 'link_text' => "See classes & what's on →", 'link' => array( 'url' => '#classes' ), 'image_label' => 'A lively class in motion' ),
				array( 'gradient' => 'g4', 'title' => 'Celebrate & hire the venue', 'text' => 'A bright, versatile space for weddings, parties and work events — with a sprung floor, a bar and room to make a day of it.', 'link_text' => 'Explore venue hire →', 'link' => array( 'url' => '#hire' ), 'image_label' => 'An event set up in the space' ),
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
				<div class="snc-fork-grid">
					<?php foreach ( (array) $s['cards'] as $card ) : ?>
						<div class="snc-fork">
							<?php $this->render_media( $card['image'], $card['gradient'], $card['image_label'] ); ?>
							<div class="snc-fork-body">
								<h3><?php echo esc_html( $card['title'] ); ?></h3>
								<p><?php echo esc_html( $card['text'] ); ?></p>
								<?php if ( $card['link_text'] ) : ?>
									<a class="go" href="<?php echo esc_url( ! empty( $card['link']['url'] ) ? $card['link']['url'] : '#' ); ?>"><?php echo esc_html( $card['link_text'] ); ?></a>
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
