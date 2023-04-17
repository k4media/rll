<?php
/**
 * Template part for single Events
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

/**
 * Handle Co-Authors
 * https://github.com/Automattic/Co-Authors-Plus
 * 
 * Only show authors on Legal and Tax Updates (cat_id = 47)
 */
if (has_category(47)) {
	$authors = get_the_author_meta('display_name');
	if (function_exists('coauthors_posts_links')) {
		// $authors = coauthors_posts_links(", ", null, null, null, false);
		$authors = coauthors(", ", null, null, null, false);
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<div class="entry-meta">
		<div class="date"><?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?></div>
		<?php if (has_category(47)) : ?>
			<div class="author">Written by <?php echo $authors; ?></div>
		<?php endif; ?>
	</div>
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">	
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->