<?php
/**
 * Template part for single Legal and Tax Updates
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */
$authors = get_the_author_meta('display_name');
if (function_exists('coauthors_posts_links')) {
	$authors = coauthors(", ", null, null, null, false);
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-meta">
		<div class="date"><?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?></div>
		<div class="author">Insights by <?php echo $authors; ?></div>
	</div>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">	
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
