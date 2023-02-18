<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();

global $post;

$term = get_category( get_query_var( 'cat' ) );

?>

<section id="insights" class="<?php echo esc_attr($term->slug) ?> callout silo">

	<?php
		/**
		 * Country Navigation
		 */
		do_action('dfdl_solutions_country_nav');
	?>

	<header>
		<?php the_archive_title( '<h2 class="page-title">', '</h2>' ); ?>
	</header><!-- .page-header -->

	<div class="posts">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php
					set_query_var("story", $post);
					set_query_var("term", $term);
					$file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
					if ( file_exists($file) ) {
						get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
					} else {
						get_template_part( 'includes/template-parts/content/insights', 'news-card' );
					}
				?>
			<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'includes/template-parts/content/content-none' ); ?>
		<?php endif; ?>
	</div>
</section>


<?php
get_footer();
