<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();

$sections = dfdl_get_section();
$category = end($sections);

$term = get_category_by_slug($category);
$url  = get_category_link($term->term_id);

?>



<div id="insights" class="<?php echo $term->slug ?> silo">

	<nav class="country-subnav-stage">
		<ul class="country-nav">
			<li class="back"><a href="<?php echo $url ?>">Back</a></li>
		</ul>
	</nav>

	<div class="single narrow">
		<?php if ( isset($term->name) ) : ?>
			<div class="category"><?php echo $term->name ?></div>
		<?php endif; ?>
		<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'includes/template-parts/content/content-single' );

			endwhile; // End of the loop.
		?>
	</div>
	
</div>

<?php get_footer();
