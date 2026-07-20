<?php
/**
 * Find Us / Map widget — map embed alongside a details list (where, hours,
 * contact) and a directions button. Map falls back to Customizer → Integrations.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_FindUs extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_find_us';
	}

	public function get_title() {
		return __( 'Find Us / Map', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-google-maps';
	}

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'map_embed', array(
			'label'       => __( 'Map embed', 'sanctuary' ),
			'description' => __( 'Google Maps <iframe> embed. Leave blank to use Customizer → Integrations.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 4,
		) );

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Find us', 'sanctuary' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'In the heart of Stone.', 'sanctuary' ) ) );

		$rep = new Repeater();
		$rep->add_control( 'term', array( 'label' => __( 'Label', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Where', 'sanctuary' ) ) );
		$rep->add_control( 'desc', array( 'label' => __( 'Detail', 'sanctuary' ), 'description' => __( 'HTML allowed.', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( '65 High Street, Stone, Staffordshire', 'sanctuary' ) ) );

		$this->add_control( 'rows', array(
			'label'       => __( 'Details', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ term }}}',
			'default'     => array(
				array( 'term' => 'Where', 'desc' => '65 High Street, Stone, Staffordshire' ),
				array( 'term' => 'Opening hours', 'desc' => 'To be confirmed' ),
				array( 'term' => 'Get in touch', 'desc' => 'Phone / email' ),
			),
		) );

		$this->add_control( 'btn_text', array( 'label' => __( 'Button text', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Get directions', 'sanctuary' ) ) );
		$this->add_control( 'btn_link', array( 'label' => __( 'Button link', 'sanctuary' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->end_controls_section();
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$map = trim( (string) $s['map_embed'] );
		if ( '' === $map ) {
			$map = trim( (string) get_theme_mod( 'sanctuary_map_embed', '' ) );
		}
		?>
		<section class="snc-section snc snc-find" id="find">
			<div class="snc-wrap snc-find-grid">
				<div>
					<?php if ( $map ) : ?>
						<?php echo $map; // phpcs:ignore WordPress.Security.EscapeOutput -- trusted admin-entered iframe. ?>
					<?php else : ?>
						<div class="ph g4" aria-label="Map placeholder"><?php esc_html_e( 'Map embed — Google Maps', 'sanctuary' ); ?></div>
					<?php endif; ?>
				</div>
				<div>
					<?php if ( $s['eyebrow'] ) : ?><p class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
					<?php if ( $s['title'] ) : ?><h2 class="title"><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
					<dl>
						<?php foreach ( (array) $s['rows'] as $row ) : ?>
							<dt><?php echo esc_html( $row['term'] ); ?></dt>
							<dd><?php echo wp_kses_post( $row['desc'] ); ?></dd>
						<?php endforeach; ?>
					</dl>
					<?php if ( $s['btn_text'] ) : ?>
						<p style="margin-top:1.4rem"><?php $this->render_button( $s['btn_text'], $s['btn_link'], 'btn-ghost' ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
