<?php
/**
 * Mosaic Gallery widget — the venue-hire "see the space" layout. First image
 * spans two rows; the rest fill the mosaic.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_Gallery extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_gallery';
	}

	public function get_title() {
		return __( 'Mosaic Gallery', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-gallery-masonry';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_heading_controls(
			__( 'A look inside', 'sanctuary' ),
			__( 'See the space.', 'sanctuary' ),
			__( 'Real event photos work best here — a hire decision is visual.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'image', array( 'label' => __( 'Image', 'sanctuary' ), 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'gradient', array(
			'label'   => __( 'Placeholder gradient', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'g1',
			'options' => array( 'g1' => 'Coral→Amber', 'g2' => 'Teal→Amber', 'g3' => 'Coral', 'g4' => 'Teal', 'g5' => 'Amber→Coral' ),
		) );
		$rep->add_control( 'label', array( 'label' => __( 'Placeholder caption', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Gallery', 'sanctuary' ) ) );
		$rep->add_control( 'big', array( 'label' => __( 'Large (span 2 rows)', 'sanctuary' ), 'type' => Controls_Manager::SWITCHER ) );

		$this->add_control( 'items', array(
			'label'       => __( 'Images', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => array(
				array( 'gradient' => 'g1', 'label' => 'Wide shot of a dressed event', 'big' => 'yes' ),
				array( 'gradient' => 'g4', 'label' => 'Detail' ),
				array( 'gradient' => 'g5', 'label' => 'Guests / floor' ),
				array( 'gradient' => 'g2', 'label' => 'Bar' ),
				array( 'gradient' => 'g1', 'label' => 'Setup' ),
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
				<div class="snc-gallery">
					<?php foreach ( (array) $s['items'] as $item ) : ?>
						<?php $extra = ( 'yes' === $item['big'] ) ? 'big' : ''; ?>
						<?php $this->render_media( $item['image'], $item['gradient'], $item['label'], $extra ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
