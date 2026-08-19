<?php
/**
 * Two-Column Feature widget (image + text).
 * The workhorse: covers the classes intro (chips + offer), the dark venue-hire
 * teaser (use-case tags + specs line), and the weddings/corporate/parties
 * occasion blocks (pill + ticks list + cross-sell). Every extra block is optional.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Feature extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_feature';
	}

	public function get_title() {
		return __( 'Two-Column Feature', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-columns';
	}

	protected function register_content_controls() {

		/* ---- Layout ---- */
		$this->start_controls_section( 'layout_sec', array( 'label' => __( 'Layout', 'sanctuary' ) ) );
		$this->add_control( 'bg', array(
			'label'   => __( 'Background', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'cream',
			'options' => array(
				'cream'  => __( 'Cream', 'sanctuary' ),
				'cream2' => __( 'Cream (deeper)', 'sanctuary' ),
				'peach'  => __( 'Peach', 'sanctuary' ),
				'ink'    => __( 'Ink (dark)', 'sanctuary' ),
			),
		) );
		$this->add_control( 'flip', array(
			'label'   => __( 'Media on left', 'sanctuary' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		) );
		$this->add_control( 'show_media', array(
			'label'       => __( 'Show media column', 'sanctuary' ),
			'description' => __( 'Turn off for a full-width text band (e.g. the Private lessons block).', 'sanctuary' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
		) );
		$this->add_control( 'anchor', array(
			'label'       => __( 'Anchor ID', 'sanctuary' ),
			'description' => __( 'Optional, e.g. "weddings" for jump-nav links.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXT,
		) );
		$this->end_controls_section();

		/* ---- Content ---- */
		$this->start_controls_section( 'content_sec', array( 'label' => __( 'Content', 'sanctuary' ) ) );
		$this->add_control( 'pill', array(
			'label' => __( 'Pill label (optional)', 'sanctuary' ),
			'type'  => Controls_Manager::TEXT,
		) );
		$this->add_control( 'pill_color', array(
			'label'     => __( 'Pill colour', 'sanctuary' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'teal',
			'options'   => array(
				'teal'  => __( 'Teal', 'sanctuary' ),
				'ink'   => __( 'Ink', 'sanctuary' ),
				'coral' => __( 'Coral', 'sanctuary' ),
			),
			'condition' => array( 'pill!' => '' ),
		) );
		$this->add_heading_controls(
			__( 'Classes', 'sanctuary' ),
			__( "New to dancing? You'll fit right in.", 'sanctuary' ),
			''
		);
		$this->add_control( 'body', array(
			'label'   => __( 'Body text', 'sanctuary' ),
			'type'    => Controls_Manager::WYSIWYG,
			'default' => __( 'No experience, no partner, no problem. Our beginner classes are warm, easy and a genuinely good night.', 'sanctuary' ),
		) );
		$this->add_control( 'btn_text', array(
			'label'   => __( 'Button text', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'See the timetable & book', 'sanctuary' ),
		) );
		$this->add_control( 'btn_link', array(
			'label'   => __( 'Button link', 'sanctuary' ),
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#' ),
		) );
		$this->add_control( 'btn_style', array(
			'label'   => __( 'Button style', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'btn-primary',
			'options' => array(
				'btn-primary' => __( 'Primary (coral)', 'sanctuary' ),
				'btn-amber'   => __( 'Amber', 'sanctuary' ),
				'btn-ghost'   => __( 'Ghost', 'sanctuary' ),
			),
		) );
		$this->end_controls_section();

		/* ---- Chips ---- */
		$this->start_controls_section( 'chips_sec', array( 'label' => __( 'Chips (optional)', 'sanctuary' ) ) );
		$chip = new Repeater();
		$chip->add_control( 'label', array( 'label' => __( 'Chip', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Ballroom', 'sanctuary' ) ) );
		$this->add_control( 'chips', array(
			'label'       => __( 'Chips', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $chip->get_controls(),
			'default'     => array(),
			'title_field' => '{{{ label }}}',
		) );
		$this->end_controls_section();

		/* ---- Ticks / bullet list ---- */
		$this->start_controls_section( 'ticks_sec', array( 'label' => __( 'Bullet list (optional)', 'sanctuary' ) ) );
		$tick = new Repeater();
		$tick->add_control( 'text', array(
			'label'       => __( 'Item', 'sanctuary' ),
			'description' => __( 'HTML allowed (e.g. <b>Sprung floor</b> — kind on feet).', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => __( '<b>Sprung floor</b> — perfect for the first dance', 'sanctuary' ),
		) );
		$this->add_control( 'ticks', array(
			'label'   => __( 'List items', 'sanctuary' ),
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $tick->get_controls(),
			'default' => array(),
		) );
		$this->end_controls_section();

		/* ---- Use-case tags (for ink teaser) ---- */
		$this->start_controls_section( 'uc_sec', array( 'label' => __( 'Use-case tags (optional)', 'sanctuary' ) ) );
		$uc = new Repeater();
		$uc->add_control( 'label', array( 'label' => __( 'Tag', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Weddings', 'sanctuary' ) ) );
		$this->add_control( 'uc_tags', array(
			'label'       => __( 'Tags', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $uc->get_controls(),
			'default'     => array(),
			'title_field' => '{{{ label }}}',
		) );
		$this->end_controls_section();

		/* ---- Callouts ---- */
		$this->start_controls_section( 'callout_sec', array( 'label' => __( 'Callouts (optional)', 'sanctuary' ) ) );
		$this->add_control( 'offer', array(
			'label'       => __( 'Offer callout', 'sanctuary' ),
			'description' => __( 'Peach highlight box. HTML allowed. Leave blank to hide.', 'sanctuary' ),
			'type'        => Controls_Manager::WYSIWYG,
			'default'     => '',
		) );
		$this->add_control( 'xsell', array(
			'label'       => __( 'Cross-sell callout', 'sanctuary' ),
			'description' => __( 'White bordered box. HTML allowed. Leave blank to hide.', 'sanctuary' ),
			'type'        => Controls_Manager::WYSIWYG,
			'default'     => '',
		) );
		$this->add_control( 'specs', array(
			'label'       => __( 'Specs line', 'sanctuary' ),
			'description' => __( 'Small line under the button (e.g. Capacity · Rates). HTML allowed.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
		) );
		$this->end_controls_section();

		/* ---- Media ---- */
		$this->start_controls_section( 'media_sec', array( 'label' => __( 'Media', 'sanctuary' ) ) );
		$this->add_control( 'image', array( 'label' => __( 'Image', 'sanctuary' ), 'type' => Controls_Manager::MEDIA ) );
		$this->add_control( 'image_label', array(
			'label'   => __( 'Placeholder caption', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'A lively class in motion', 'sanctuary' ),
		) );
		$this->add_control( 'gradient', array(
			'label'   => __( 'Placeholder gradient', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'g5',
			'options' => array( 'g1' => 'Coral→Amber', 'g2' => 'Teal→Amber', 'g3' => 'Coral', 'g4' => 'Teal', 'g5' => 'Amber→Coral' ),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$show_media = 'yes' === $s['show_media'];
		$classes    = array( 'snc-section', 'snc', 'snc-feature', 'snc-feature--' . $s['bg'] );
		if ( ! $show_media ) {
			$classes[] = 'snc-feature--solo';
		}
		if ( 'yes' === $s['flip'] ) {
			$classes[] = 'snc-feature--flip';
		}
		$anchor = $s['anchor'] ? ' id="' . esc_attr( $s['anchor'] ) . '"' : '';
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $anchor; // phpcs:ignore ?>>
			<div class="snc-wrap">
				<div>
					<?php if ( $s['pill'] ) : ?>
						<span class="snc-pill snc-pill--<?php echo esc_attr( $s['pill_color'] ); ?>"><?php echo esc_html( $s['pill'] ); ?></span>
					<?php endif; ?>
					<?php if ( $s['eyebrow'] ) : ?><p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
					<?php if ( $s['title'] ) : ?><h2 class="title"><?php echo wp_kses_post( $s['title'] ); ?></h2><?php endif; ?>
					<?php if ( $s['lede'] ) : ?><p class="lede"><?php echo wp_kses_post( $s['lede'] ); ?></p><?php endif; ?>
					<?php if ( $s['body'] ) : ?><div><?php echo wp_kses_post( $s['body'] ); ?></div><?php endif; ?>

					<?php if ( ! empty( $s['chips'] ) ) : ?>
						<div class="snc-chips">
							<?php foreach ( $s['chips'] as $c ) : ?>
								<span class="snc-chip"><?php echo esc_html( $c['label'] ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $s['ticks'] ) ) : ?>
						<ul class="snc-ticks">
							<?php foreach ( $s['ticks'] as $t ) : ?>
								<li><?php echo wp_kses_post( $t['text'] ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $s['uc_tags'] ) ) : ?>
						<div class="snc-use-cases">
							<?php foreach ( $s['uc_tags'] as $u ) : ?>
								<span class="uc-tag"><?php echo esc_html( $u['label'] ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( $s['offer'] ) : ?>
						<div class="snc-offer"><?php echo wp_kses_post( $s['offer'] ); ?></div>
					<?php endif; ?>
					<?php if ( $s['xsell'] ) : ?>
						<div class="snc-xsell"><?php echo wp_kses_post( $s['xsell'] ); ?></div>
					<?php endif; ?>

					<?php $this->render_button( $s['btn_text'], $s['btn_link'], $s['btn_style'] ); ?>

					<?php if ( $s['specs'] ) : ?>
						<p class="snc-hire-specs"><?php echo wp_kses_post( $s['specs'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $show_media ) : ?>
					<div class="snc-feature-media">
						<?php $this->render_media( $s['image'], $s['gradient'], $s['image_label'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
