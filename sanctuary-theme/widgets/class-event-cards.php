<?php
/**
 * Event Cards ("What's on") widget — heading + a repeater of cards, each with a
 * coloured top bar (kind + day), a title, up to three meta lines and a link.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_EventCards extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_event_cards';
	}

	public function get_title() {
		return __( "Event Cards (What's on)", 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( "What's on", 'sanctuary' ),
			__( "There's always something on.", 'sanctuary' ),
			__( 'Drop into a class, come for a social, or just have a drink at the bar.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'color', array(
			'label'   => __( 'Top bar colour', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'coral',
			'options' => array( 'coral' => __( 'Coral', 'sanctuary' ), 'amber' => __( 'Amber', 'sanctuary' ), 'teal' => __( 'Teal', 'sanctuary' ) ),
		) );
		$rep->add_control( 'kind', array( 'label' => __( 'Kind', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Social', 'sanctuary' ) ) );
		$rep->add_control( 'day', array( 'label' => __( 'Day', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Saturdays', 'sanctuary' ) ) );
		$rep->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Saturday Socials', 'sanctuary' ) ) );
		$rep->add_control( 'meta1', array( 'label' => __( 'Meta line 1', 'sanctuary' ), 'description' => __( 'HTML allowed.', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( '<b>8:00pm</b> till late', 'sanctuary' ) ) );
		$rep->add_control( 'meta2', array( 'label' => __( 'Meta line 2', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Big floor, full bar', 'sanctuary' ) ) );
		$rep->add_control( 'meta3', array( 'label' => __( 'Meta line 3', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Everyone welcome', 'sanctuary' ) ) );
		$rep->add_control( 'link_text', array( 'label' => __( 'Link text', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Come along →', 'sanctuary' ) ) );
		$rep->add_control( 'link', array( 'label' => __( 'Link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ title }}}',
			'default'     => array(
				array( 'color' => 'coral', 'kind' => 'Social', 'day' => 'Saturdays', 'title' => 'Saturday Socials', 'meta1' => '<b>8:00pm</b> till late', 'meta2' => 'Big floor, full bar', 'meta3' => 'Everyone welcome', 'link_text' => 'Come along →' ),
				array( 'color' => 'amber', 'kind' => 'Class', 'day' => 'Tuesdays', 'title' => 'Absolute Beginners', 'meta1' => '<b>6:15pm</b>, weekly', 'meta2' => 'No partner needed', 'meta3' => 'No experience needed', 'link_text' => 'Book a spot →' ),
				array( 'color' => 'teal', 'kind' => 'Social', 'day' => 'Fridays', 'title' => 'Latin Night', 'meta1' => '<b>7:30pm</b>', 'meta2' => 'Beginner-friendly', 'meta3' => 'Stay for drinks', 'link_text' => 'See details →' ),
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
				<div class="snc-whatson-grid">
					<?php foreach ( (array) $s['cards'] as $card ) : ?>
						<div class="snc-card">
							<div class="snc-card-top <?php echo esc_attr( $card['color'] ); ?>">
								<span><?php echo esc_html( $card['kind'] ); ?></span><span><?php echo esc_html( $card['day'] ); ?></span>
							</div>
							<div class="snc-card-body">
								<h3 class="snc-card-title"><?php echo esc_html( $card['title'] ); ?></h3>
								<div class="snc-card-meta">
									<?php foreach ( array( 'meta1', 'meta2', 'meta3' ) as $m ) : ?>
										<?php if ( ! empty( $card[ $m ] ) ) : ?><span><?php echo wp_kses_post( $card[ $m ] ); ?></span><?php endif; ?>
									<?php endforeach; ?>
								</div>
								<?php if ( $card['link_text'] ) : ?>
									<a class="snc-card-link" href="<?php echo esc_url( ! empty( $card['link']['url'] ) ? $card['link']['url'] : '#' ); ?>"><?php echo esc_html( $card['link_text'] ); ?></a>
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
