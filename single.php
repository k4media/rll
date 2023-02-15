<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();
?>
<div id="insights" class="single narrow">

	<?php
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();

		get_template_part( 'includes/template-parts/content/content-single' );

		// If comments are open or there is at least one comment, load up the comment template.
		//if ( comments_open() || get_comments_number() ) {
			//comments_template();
		//}

	endwhile; // End of the loop.
?>

</div>

<?php get_footer();
