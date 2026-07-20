<?php
/**
 * Front page. Delegates to page.php behaviour so the homepage can be an
 * Elementor-built page selected under Settings → Reading.
 *
 * @package Sanctuary
 */

get_header();

while ( have_posts() ) :
	the_post();
	the_content();
endwhile;

get_footer();
