<?php
/**
 * Instagram Feed widget.
 * Wraps a live feed plugin (Spotlight / Smash Balloon) via its shortcode so the
 * feed auto-pulls with no manual updating. Shows the mockup placeholder row until
 * a shortcode is set.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_Instagram extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_instagram';
	}

	public function get_title() {
		return __( 'Instagram Feed', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-instagram-gallery';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'label', array(
			'label'   => __( 'Label', 'sanctuary' ),
			'type'    => Controls_Manager::TEXT,
			'default' => __( 'From our Instagram', 'sanctuary' ),
		) );
		$this->add_control( 'shortcode', array(
			'label'       => __( 'Feed shortcode', 'sanctuary' ),
			'description' => __( 'Shortcode from your Instagram feed plugin (e.g. Spotlight / Smash Balloon). Leave blank to show placeholders.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 2,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<div class="snc">
			<?php if ( $s['label'] ) : ?>
				<p class="snc-ig-label"><?php echo esc_html( $s['label'] ); ?> — <span class="note"><?php esc_html_e( 'live feed, auto-pulled (no manual updating)', 'sanctuary' ); ?></span></p>
			<?php endif; ?>
			<?php if ( trim( (string) $s['shortcode'] ) ) : ?>
				<?php echo do_shortcode( $s['shortcode'] ); // phpcs:ignore ?>
			<?php else : ?>
				<div class="snc-ig-row">
					<?php foreach ( array( 'g1', 'g4', 'g5', 'g2', 'g3' ) as $g ) : ?>
						<div class="ph <?php echo esc_attr( $g ); ?>" aria-label="Instagram placeholder">IG</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
