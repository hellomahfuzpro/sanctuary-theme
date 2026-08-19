<?php
/**
 * Hero widget. Variants cover all four page heroes:
 *  - split (text + media), the homepage & hire heroes
 *  - centered (classes hero)
 *  - optional floating "on this week" card (homepage)
 *  - optional credit line (homepage)
 *  - optional jump-nav pills (private hire)
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Hero extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_hero';
	}

	public function get_title() {
		return __( 'Hero', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-header';
	}

	protected function register_content_controls() {

		/* ---- Layout ---- */
		$this->start_controls_section( 'layout_sec', array( 'label' => __( 'Layout', 'sanctuary' ) ) );

		$this->add_control( 'layout', array(
			'label'   => __( 'Layout', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'split',
			'options' => array(
				'split'  => __( 'Split (text + media)', 'sanctuary' ),
				'center' => __( 'Centered (no media)', 'sanctuary' ),
			),
		) );

		$this->add_control( 'bg', array(
			'label'   => __( 'Background', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'glow',
			'options' => array(
				'glow' => __( 'Warm glow (homepage)', 'sanctuary' ),
				'teal' => __( 'Teal wash (hire pages)', 'sanctuary' ),
				'flat' => __( 'Flat cream', 'sanctuary' ),
			),
		) );

		$this->end_controls_section();

		/* ---- Content ---- */
		$this->start_controls_section( 'content_sec', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'eyebrow', array(
			'label'   => __( 'Eyebrow', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Dance · drinks · good company · Stone', 'sanctuary' ),
		) );
		$this->add_control( 'heading', array(
			'label'       => __( 'Heading', 'sanctuary' ),
			'description' => __( 'Wrap a word in <em>…</em> to highlight it in coral.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 2,
			'default'     => __( 'Dance, drinks and <em>good company</em>.', 'sanctuary' ),
		) );
		$this->add_control( 'sub', array(
			'label'   => __( 'Sub-text', 'sanctuary' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => __( 'Classes for absolute beginners, friendly social nights, and a 3,000 sq ft venue for your big day — right in the heart of Stone.', 'sanctuary' ),
		) );

		$this->add_control( 'btn1_text', array(
			'label'   => __( 'Primary button', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Book a class', 'sanctuary' ),
		) );
		$this->add_control( 'btn1_link', array(
			'label'   => __( 'Primary link', 'sanctuary' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#' ),
		) );
		$this->add_control( 'btn2_text', array(
			'label'   => __( 'Secondary button', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Hire the venue', 'sanctuary' ),
		) );
		$this->add_control( 'btn2_link', array(
			'label'   => __( 'Secondary link', 'sanctuary' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#' ),
		) );

		$this->add_control( 'credit', array(
			'label'       => __( 'Credit line', 'sanctuary' ),
			'description' => __( 'Optional. HTML allowed (e.g. Led by <b>Oliver Hand</b>).', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
		) );

		$this->end_controls_section();

		/* ---- Media ---- */
		$this->start_controls_section( 'media_sec', array(
			'label'     => __( 'Media', 'sanctuary' ),
			'condition' => array( 'layout' => 'split' ),
		) );

		$this->add_control( 'image', array(
			'label' => __( 'Hero image', 'sanctuary' ),
			'type'  => Controls_Manager::MEDIA,
		) );
		$this->add_control( 'image_label', array(
			'label'   => __( 'Placeholder caption', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'A full class mid-laugh, or a busy social night', 'sanctuary' ),
		) );

		$this->add_control( 'show_card', array(
			'label'        => __( 'Show floating card', 'sanctuary' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
		) );
		$this->add_control( 'card_k', array(
			'label'     => __( 'Card — kicker', 'sanctuary' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => __( 'On this week', 'sanctuary' ),
			'condition' => array( 'show_card' => 'yes' ),
		) );
		$this->add_control( 'card_t', array(
			'label'     => __( 'Card — title', 'sanctuary' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => __( 'Beginners welcome every Tuesday', 'sanctuary' ),
			'condition' => array( 'show_card' => 'yes' ),
		) );
		$this->add_control( 'card_m', array(
			'label'     => __( 'Card — meta', 'sanctuary' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => __( '6:15pm · no partner needed', 'sanctuary' ),
			'condition' => array( 'show_card' => 'yes' ),
		) );

		$this->end_controls_section();

		/* ---- Jump nav ---- */
		$this->start_controls_section( 'jump_sec', array( 'label' => __( 'Jump nav (optional)', 'sanctuary' ) ) );

		$rep = new Repeater();
		$rep->add_control( 'label', array(
			'label'   => __( 'Label', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'Weddings', 'sanctuary' ),
		) );
		$rep->add_control( 'link', array(
			'label'   => __( 'Anchor / link', 'sanctuary' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#weddings' ),
		) );

		$this->add_control( 'jump_items', array(
			'label'       => __( 'Jump links', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'default'     => array(),
			'title_field' => '{{{ label }}}',
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$bg_class = 'snc-hero';
		if ( 'center' === $s['layout'] ) {
			$bg_class .= ' snc-hero--center';
		} elseif ( 'teal' === $s['bg'] ) {
			$bg_class .= ' snc-hero--teal';
		}
		?>
		<section class="snc-section snc <?php echo esc_attr( $bg_class ); ?>">
			<div class="snc-wrap <?php echo 'split' === $s['layout'] ? 'snc-hero-grid' : ''; ?>">
				<div>
					<?php if ( $s['eyebrow'] ) : ?>
						<p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p>
					<?php endif; ?>
					<h1><?php echo wp_kses_post( $s['heading'] ); ?></h1>
					<?php if ( $s['sub'] ) : ?>
						<p class="sub"><?php echo esc_html( $s['sub'] ); ?></p>
					<?php endif; ?>

					<div class="snc-hero-cta">
						<?php
						$this->render_button( $s['btn1_text'], $s['btn1_link'], 'btn-primary' );
						$this->render_button( $s['btn2_text'], $s['btn2_link'], 'btn-ghost' );
						?>
					</div>

					<?php if ( $s['credit'] ) : ?>
						<p class="snc-cred-line"><?php echo wp_kses_post( $s['credit'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $s['jump_items'] ) ) : ?>
						<div class="snc-jump">
							<?php foreach ( $s['jump_items'] as $item ) : ?>
								<a href="<?php echo esc_url( ! empty( $item['link']['url'] ) ? $item['link']['url'] : '#' ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( 'split' === $s['layout'] ) : ?>
					<div class="snc-hero-media">
						<?php $this->render_media( $s['image'], 'g3', $s['image_label'] ); ?>
						<?php if ( 'yes' === $s['show_card'] && ( $s['card_t'] || $s['card_k'] ) ) : ?>
							<div class="snc-float-card">
								<?php if ( $s['card_k'] ) : ?><div class="k"><?php echo esc_html( $s['card_k'] ); ?></div><?php endif; ?>
								<?php if ( $s['card_t'] ) : ?><div class="t"><?php echo esc_html( $s['card_t'] ); ?></div><?php endif; ?>
								<?php if ( $s['card_m'] ) : ?><div class="m"><?php echo esc_html( $s['card_m'] ); ?></div><?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
