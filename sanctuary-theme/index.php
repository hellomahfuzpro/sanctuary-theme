<?php
/**
 * Fallback template. Elementor takes over the body on built pages; this handles
 * blog/archive output when no more specific template applies.
 *
 * @package Sanctuary
 */

get_header();
?>
<div class="wrap snc" style="padding-block:clamp(3rem,6.5vw,5rem)">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?> style="margin-bottom:2.5rem">
				<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="lede"><?php the_excerpt(); ?></div>
			</article>
			<?php
		endwhile;

		the_posts_pagination();
	else :
		echo '<p class="lede">' . esc_html__( 'Nothing here yet.', 'sanctuary' ) . '</p>';
	endif;
	?>
</div>
<?php
get_footer();
