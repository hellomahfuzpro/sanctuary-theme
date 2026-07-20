<?php
/**
 * Booking Embed widget (Acuity Scheduling).
 * Wraps the Acuity scheduler — the single source of truth for the timetable and
 * booking. Paste the Acuity iframe embed; falls back to the site-wide embed set
 * in Customizer → Integrations. Shows a labelled placeholder until configured.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_BookingEmbed extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_booking_embed';
	}

	public function get_title() {
		return __( 'Booking Embed (Acuity)', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'Timetable & booking', 'sanctuary' ),
			__( 'Pick a class, book your spot.', 'sanctuary' ),
			__( 'Live times and booking below.', 'sanctuary' )
		);

		$this->add_control( 'embed', array(
			'label'       => __( 'Acuity embed code', 'sanctuary' ),
			'description' => __( 'Paste the Acuity <iframe> embed. Leave blank to use the site-wide embed from Customizer → Integrations.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 6,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$embed = trim( (string) $s['embed'] );
		if ( '' === $embed ) {
			$embed = trim( (string) get_theme_mod( 'sanctuary_acuity_embed', '' ) );
		}
		?>
		<section class="snc-section snc snc-timetable">
			<div class="snc-wrap">
				<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
				<div class="snc-embed">
					<?php if ( $embed ) : ?>
						<?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted admin-entered embed (iframe/script). ?>
					<?php else : ?>
						<div class="k"><?php esc_html_e( 'Acuity scheduler embeds here', 'sanctuary' ); ?></div>
						<h3><?php esc_html_e( 'Live timetable & booking', 'sanctuary' ); ?></h3>
						<p><?php esc_html_e( 'The class timetable and booking come straight from Acuity — the single source of truth. Payment is taken by Acuity too. Paste the embed in this widget or under Customizer → Integrations.', 'sanctuary' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
