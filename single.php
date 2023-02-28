<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();

/**
 * Back URL
 */
//$sections = dfdl_get_section();
//$category = end($sections);
//$term = get_category_by_slug($category);
//$url  = get_category_link($term);



/**
 * post classes
 */
global $post;
$terms = wp_get_post_terms($post->ID, 'category');

$classes = array();
$primary_categories = array(
	"News",
	"Events",
	"Legal and Tax",
	"Legal and Tax Updates",
);

foreach( $terms as $term ) {

	/**
	 * Set main category 
	 */
	if ( in_array($term->name, $primary_categories ) ) {
		$single_category = $term->name;
		$template_slug = str_replace(" ", "-", strtolower($single_category));
	}

	/**
	 * Set subcategory
	 */
	if ($term->parent) {
		$single_subcategory = $term->name;
	}

	$classes[] = esc_attr($term->slug);

}

?>

<div id="insights" class="<?php echo implode(" ", $classes) ?> silo">
	<!--
		<nav class="country-subnav-stage">
		<ul class="country-nav">
			<li class="back"><a href="javascript:history.back()">Back</a></li>
		</ul>
	</nav-->
	<div class="single narrow">
		<?php if ( isset($single_category) ) : ?>
			<div class="taxonomy">
				<span class="category"><?php echo $single_category ?></span>
				<?php if ( isset($single_subcategory) ) : ?>
					<span class="separator">|</span>
					<span class="subcategory"><?php echo $single_subcategory ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				if ( isset($template_slug) ) {
					get_template_part( 'includes/template-parts/content/content', $template_slug );
				} else {
					get_template_part( 'includes/template-parts/content/content-single' );
				}
				

			endwhile; // End of the loop.
		?>
		<?php get_template_part( 'includes/template-parts/content/single-social-share' ); ?>
	</div>
	<?php do_action("dfdl_related_stories"); ?>
</div>
<?php get_footer();