<?php
/**
 * Included List widget — "what's included" two-column bullet list with a heading
 * and optional lede note.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Sanctuary_Widget_IncludedList extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_included_list';
	}

	public function get_title() {
		return __( 'Included List', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'bg', array(
			'label'   => __( 'Background', 'sanctuary' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'cream2',
			'options' => array( 'cream' => __( 'Cream', 'sanctuary' ), 'cream2' => __( 'Cream (deeper)', 'sanctuary' ) ),
		) );

		$this->add_heading_controls(
			__( "What's included", 'sanctuary' ),
			__( 'No surprises.', 'sanctuary' ),
			__( 'Fill with the real inclusions — this removes friction and pre-empts back-and-forth emails.', 'sanctuary' )
		);

		$rep = new Repeater();
		$rep->add_control( 'text', array( 'label' => __( 'Item', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Exclusive use of the space', 'sanctuary' ) ) );
		$this->add_control( 'items', array(
			'label'       => __( 'Items', 'sanctuary' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => array(
				array( 'text' => 'Exclusive use of the space / hours of hire' ),
				array( 'text' => 'Tables & chairs, setup & clear-down' ),
				array( 'text' => 'Staffed bar' ),
				array( 'text' => 'Sound system / mic / AV' ),
				array( 'text' => 'Changing room access' ),
				array( 'text' => 'Catering options or kitchen access' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		?>
		<section class="snc-section snc snc-incl--<?php echo esc_attr( $s['bg'] ); ?>">
			<div class="snc-wrap">
				<?php $this->render_heading( $s['eyebrow'], $s['title'], $s['lede'] ); ?>
				<ul class="snc-incl-list">
					<?php foreach ( (array) $s['items'] as $item ) : ?>
						<li><?php echo esc_html( $item['text'] ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php
	}
}
