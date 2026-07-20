<?php
/**
 * Stat / Reassurance Strip widget.
 * Covers the dark credibility strip, the dark "space" strip, and the light
 * card-style reassurance row. Repeater of title + subtitle items.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_StatStrip extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_stat_strip';
	}

	public function get_title() {
		return __( 'Stat / Reassurance Strip', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Strip', 'sanctuary' ) ) );

		$this->add_control( 'style', array(
			'label'   => __( 'Style', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'dark',
			'options' => array(
				'dark'  => __( 'Dark bar (stats)', 'sanctuary' ),
				'cards' => __( 'Light cards (reassurance)', 'sanctuary' ),
			),
		) );

		$this->add_control( 'columns', array(
			'label'   => __( 'Columns', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '0',
			'options' => array(
				'0' => __( 'Auto (match item count)', 'sanctuary' ),
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		) );

		$rep = new Repeater();
		$rep->add_control( 'big', array(
			'label'   => __( 'Title', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( '3,000 sq ft', 'sanctuary' ),
		) );
		$rep->add_control( 'sub', array(
			'label'   => __( 'Subtitle', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'versatile venue', 'sanctuary' ),
		) );

		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ big }}}',
			'default'     => array(
				array( 'big' => '3,000 sq ft', 'sub' => 'venue · 2,000 sq ft sprung floor' ),
				array( 'big' => 'Café-cocktail bar', 'sub' => 'open before & after' ),
				array( 'big' => 'All welcome', 'sub' => 'beginners & no partner needed' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$cards = 'cards' === $s['style'];
		$cls   = $cards ? 'snc-strip--cards snc-section' : 'snc-strip--dark';
		$cols  = ( isset( $s['columns'] ) && (int) $s['columns'] > 0 )
			? (int) $s['columns']
			: max( 1, count( (array) $s['items'] ) );
		?>
		<section class="snc <?php echo esc_attr( $cls ); ?>" style="--cols:<?php echo (int) $cols; ?>">
			<div class="snc-wrap">
				<?php foreach ( (array) $s['items'] as $item ) : ?>
					<?php if ( $cards ) : ?>
						<div class="snc-strip-item">
							<b><?php echo esc_html( $item['big'] ); ?></b>
							<span><?php echo wp_kses_post( $item['sub'] ); ?></span>
						</div>
					<?php else : ?>
						<div class="snc-strip-item">
							<div class="big"><?php echo esc_html( $item['big'] ); ?><span><?php echo wp_kses_post( $item['sub'] ); ?></span></div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
