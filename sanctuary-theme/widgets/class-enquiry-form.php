<?php
/**
 * Enquiry Form widget.
 * Renders the styled enquiry section (peach band + white form card) and embeds
 * the actual form. Paste a form shortcode — an Elementor Pro form saved as a
 * shortcode, or a Fluent Forms / CF7 shortcode. Until one is set, a non-functional
 * static preview of the mockup fields is shown so the layout reads in the editor.
 *
 * @package Sanctuary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Sanctuary_Widget_EnquiryForm extends Sanctuary_Base_Widget {

	public function get_name() {
		return 'sanctuary_enquiry_form';
	}

	public function get_title() {
		return __( 'Enquiry Form', 'sanctuary' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	protected function register_content_controls() {
		$this->start_controls_section( 'content', array( 'label' => __( 'Content', 'sanctuary' ) ) );

		$this->add_control( 'eyebrow', array( 'label' => __( 'Eyebrow', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Enquire', 'sanctuary' ) ) );
		$this->add_control( 'title', array( 'label' => __( 'Title', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Tell us about your event.', 'sanctuary' ) ) );
		$this->add_control( 'lede', array( 'label' => __( 'Lede', 'sanctuary' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( "We'll get back to you with availability and a quote.", 'sanctuary' ) ) );
		$this->add_control( 'anchor', array( 'label' => __( 'Anchor ID', 'sanctuary' ), 'type' => Controls_Manager::TEXT, 'default' => 'enquire' ) );

		$this->add_control( 'shortcode', array(
			'label'       => __( 'Form shortcode', 'sanctuary' ),
			'description' => __( 'Elementor Pro form (saved as shortcode), Fluent Forms or CF7 shortcode. Leave blank to show the static preview.', 'sanctuary' ),
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 3,
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$anchor = $s['anchor'] ? ' id="' . esc_attr( $s['anchor'] ) . '"' : '';
		?>
		<section class="snc-section snc snc-enq"<?php echo $anchor; // phpcs:ignore ?>>
			<div class="snc-wrap">
				<?php if ( $s['eyebrow'] ) : ?><p class="eyebrow" style="text-align:center"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
				<?php if ( $s['title'] ) : ?><h2 class="title" style="text-align:center"><?php echo esc_html( $s['title'] ); ?></h2><?php endif; ?>
				<?php if ( $s['lede'] ) : ?><p class="lede" style="text-align:center;margin-inline:auto"><?php echo esc_html( $s['lede'] ); ?></p><?php endif; ?>

				<div class="snc-form">
					<?php if ( trim( (string) $s['shortcode'] ) ) : ?>
						<?php echo do_shortcode( $s['shortcode'] ); // phpcs:ignore ?>
					<?php else : ?>
						<?php $this->static_preview(); ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Non-functional mockup fields — replaced by the real form shortcode at launch.
	 */
	private function static_preview() {
		?>
		<div class="grid2">
			<div><label><?php esc_html_e( 'Name', 'sanctuary' ); ?> <span class="req">*</span></label><input type="text" placeholder="<?php esc_attr_e( 'Your name', 'sanctuary' ); ?>" disabled></div>
			<div><label><?php esc_html_e( 'Phone', 'sanctuary' ); ?></label><input type="tel" placeholder="<?php esc_attr_e( 'Best number', 'sanctuary' ); ?>" disabled></div>
		</div>
		<label><?php esc_html_e( 'Email', 'sanctuary' ); ?> <span class="req">*</span></label><input type="email" placeholder="you@email.com" disabled>
		<div class="grid2">
			<div><label><?php esc_html_e( 'Event type', 'sanctuary' ); ?> <span class="req">*</span></label>
				<select disabled><option><?php esc_html_e( 'Wedding', 'sanctuary' ); ?></option><option><?php esc_html_e( 'Party / celebration', 'sanctuary' ); ?></option><option><?php esc_html_e( 'Corporate', 'sanctuary' ); ?></option><option><?php esc_html_e( 'Christening', 'sanctuary' ); ?></option><option><?php esc_html_e( 'Other', 'sanctuary' ); ?></option></select>
			</div>
			<div><label><?php esc_html_e( 'Preferred date(s)', 'sanctuary' ); ?> <span class="req">*</span></label><input type="date" disabled></div>
		</div>
		<label><?php esc_html_e( 'Approx. guests', 'sanctuary' ); ?></label><input type="number" placeholder="e.g. 80" disabled>
		<label><?php esc_html_e( 'Anything else', 'sanctuary' ); ?></label><textarea rows="4" placeholder="<?php esc_attr_e( "Tell us a bit about what you're planning", 'sanctuary' ); ?>" disabled></textarea>
		<p style="margin-top:1.3rem"><button type="button" class="btn btn-primary" disabled><?php esc_html_e( 'Send enquiry', 'sanctuary' ); ?></button></p>
		<p style="font-size:.82rem;color:var(--muted);margin:.6rem 0 0"><?php esc_html_e( 'Preview only — add your Elementor Pro form shortcode to make this live.', 'sanctuary' ); ?></p>
		<?php
	}
}
