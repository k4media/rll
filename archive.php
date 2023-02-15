<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();

?>
<div id="insights" class="">
	<section id="<?php echo esc_attr($term->slug)  ?>" class="<?php echo esc_attr($term->slug) ?> callout silo">
		<div class="posts">{posts}
			<?php if ( have_posts() ) : ?>

				<header class="page-header alignwide">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				</header><!-- .page-header -->

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'includes/template-parts/content/insights', 'news-card' ); ?>
				<?php endwhile; ?>

				

			<?php else : ?>
				<?php get_template_part( 'includes/template-parts/content/content-none' ); ?>
			<?php endif; ?>
		</div>
	</section>
</div>

<?php
get_footer();
