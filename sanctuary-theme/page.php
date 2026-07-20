<?php
/**
 * Default page template. For Elementor-built pages the plugin renders the full
 * content area; this wrapper simply provides the theme header/footer chrome.
 *
 * @package Sanctuary
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Elementor-built pages output their own full-width sections.
	$doc            = class_exists( '\Elementor\Plugin' ) ? \Elementor\Plugin::$instance->documents->get( get_the_ID() ) : null;
	$built_with_elm = $doc && $doc->is_built_with_elementor();

	if ( $built_with_elm ) {
		the_content();
	} else {
		?>
		<div class="wrap snc" style="padding-block:clamp(3rem,6.5vw,5rem)">
			<h1 class="title"><?php the_title(); ?></h1>
			<div class="lede"><?php the_content(); ?></div>
		</div>
		<?php
	}

endwhile;

get_footer();
