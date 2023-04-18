<?php
/**
 * Template part for single Events
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

// array for event details
$details = array();
$details['sponsor']   = get_post_meta( $post->ID, 'sponsor', true);
$details['dateline']  = get_post_meta( $post->ID, 'dateline', true);
$details['timeline']  = get_post_meta( $post->ID, 'timeline', true);
$details['location']  = get_post_meta( $post->ID, 'location', true);
$details['startdate'] = get_post_meta( $post->ID, 'startdate', true);
if ( isset($details['startdate']) ) {
	$show_date = mysql2date( get_option( 'date_format' ), $details['startdate'] );
}

// remove empty values
$details = array_filter($details);

$authors = get_the_author_meta('display_name');
if (function_exists('coauthors_posts_links')) {
	$authors = coauthors(", ", null, null, null, false);
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	
	<?php if ( count($details) > 0 ) : ?>
		<div class="event-details">
			<?php if ( ! empty($details['sponsor']) ) : ?>
				<div class="sponsor">Sponsored by <?php echo esc_attr($details['sponsor']) ?></div>
			<?php endif; ?>
			<?php if ( ! empty($details['dateline']) ) : ?>
				<div class="dateline"><?php echo esc_attr($details['dateline']) ?></div>
			<?php endif; ?>
			<?php if ( ! empty($details['timeline']) ) : ?>
				<div class="timeline"><?php echo esc_attr($details['timeline']) ?></div>
			<?php endif; ?>
			<?php if ( ! empty($details['location']) ) : ?>
				<div class="location"><?php echo esc_attr($details['location']) ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->