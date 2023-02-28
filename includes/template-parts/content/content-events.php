<?php
/**
 * Template part for single Events
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

             
 $sponsor = get_post_meta( $post->ID, 'sponsor', true);
 $dateline = get_post_meta( $post->ID, 'dateline', true);
 $timeline = get_post_meta( $post->ID, 'timeline', true);
 $location = get_post_meta( $post->ID, 'location', true);
 $startdate = get_post_meta( $post->ID, 'startdate', true);
 if ( isset($startdate) ) {
	 $show_date = mysql2date( get_option( 'date_format' ), $startdate );
 }

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="event-details">
		<?php if ( isset($sponsor) ) : ?>
			<div class="sponsor">Sponsored by <?php echo esc_attr($sponsor) ?></div>
		<?php endif; ?>
		<?php if ( isset($dateline) ) : ?>
			<div class="dateline"><?php echo esc_attr($dateline) ?></div>
		<?php endif; ?>
		<?php if ( isset($timeline) ) : ?>
			<div class="timeline"><?php echo esc_attr($timeline) ?></div>
		<?php endif; ?>
		<?php if ( isset($location) ) : ?>
			<div class="location"><?php echo esc_attr($location) ?></div>
		<?php endif; ?>
	</div>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">	
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
