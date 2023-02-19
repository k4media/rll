<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();

//$sections = dfdl_get_section();
//$category = end($sections);
//$term = get_category_by_slug($category);
//$url  = get_category_link($term->term_id);

global $post;
$terms = wp_get_post_terms($post->ID, 'category');
$classes = array();
foreach( $terms as $term ) {
	$classes[] = esc_attr($term->slug);
}
?>

<div id="insights" class="<?php echo implode(" ", $classes) ?> silo">
	<nav class="country-subnav-stage">
		<ul class="country-nav">
			<li class="back"><a href="javascript:history.back()">Back</a></li>
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
		<?php get_template_part( 'includes/template-parts/content/single-social-share' ); ?>
	</div>
	<?php do_action("dfdl_related_stories"); ?>
</div>
<?php get_footer();