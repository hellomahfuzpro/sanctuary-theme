<?php
/**
 * 404 template.
 *
 * @package Sanctuary
 */

get_header();
?>
<section class="snc-section snc snc-closing snc-closing--coral">
	<div class="snc-wrap">
		<h2><?php esc_html_e( 'Lost the beat?', 'sanctuary' ); ?></h2>
		<p><?php esc_html_e( "That page doesn't exist — but there's always something on.", 'sanctuary' ); ?></p>
		<div class="snc-cta-row">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Back home', 'sanctuary' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
