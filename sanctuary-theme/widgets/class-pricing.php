<?php
/**
 * Pricing Cards widget — the classes "simple and fair" two-card price block.
 * One card can be highlighted (dark).
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Pricing extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_pricing';
	}

	public function get_title() {
		return __( 'Pricing Cards', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'Prices', 'sanctuary' ),
			__( 'Simple and fair.', 'sanctuary' ),
			__( 'A clear price — and ideally a first-class hook — converts far better than "get in touch".', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'highlight', array( 'label' => __( 'Highlight (dark)', 'sanctuary' ), 'type' => Controls_Manager::SWITCHER ) );
		$rep->add_control( 'amount', array( 'label' => __( 'Amount', 'sanctuary' ), 'description' => __( 'HTML allowed for small suffixes.', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'First class £[X]', 'sanctuary' ) ) );
		$rep->add_control( 'note', array( 'label' => __( 'Note', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'The hook that gets a nervous beginner to commit.', 'sanctuary' ) ) );

		$this->add_control( 'cards', array(
			'label'       => __( 'Cards', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ amount }}}',
			'default'     => array(
				array( 'highlight' => 'yes', 'amount' => 'First class £[X]', 'note' => 'Or "your first class is on us" — the hook that gets a nervous beginner to commit.' ),
				array( 'highlight' => '', 'amount' => '£[X] <span style="font-size:1rem">per class</span>', 'note' => 'Pay as you go.' ),
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
				<div class="snc-pricing">
					<?php foreach ( (array) $s['cards'] as $card ) : ?>
						<div class="snc-price <?php echo ( 'yes' === $card['highlight'] ) ? 'hi' : ''; ?>">
							<div class="amt"><?php echo wp_kses_post( $card['amount'] ); ?></div>
							<span><?php echo wp_kses_post( $card['note'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
