<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

global $wp;  

get_header();

if ( ! empty(get_query_var('cat')) ) {
	$term = get_category(get_query_var('cat'));
} else {
	$pieces  = explode( "/", $wp->request ) ;
	$term    = get_term_by("slug", $pieces[1], 'category');
}

?>
<section id="insights" class="<?php echo esc_attr($term->slug) ?> archive callout silo">
	<?php
		/**
		 * Country Navigation
		 */
		do_action('dfdl_solutions_country_nav');
	?>

	<header class="title">
		<?php the_archive_title( '<h2 class="page-title">', '</h2>' ); ?>
	</header><!-- .page-header -->

	<div id="results_stage"><div>
		<div class="posts">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php
						set_query_var("story", $post);
						set_query_var("term", $term);
						
						if ( "events" === $term->slug ) {
							$startdate = get_post_meta( $post->ID, 'startdate', true);
							if ( isset($startdate) ) {
								$show_date = mysql2date( get_option( 'date_format' ), $startdate );
							}
							set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
							set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
							set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
							set_query_var("show_date", $show_date);
						}
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

		<div class="pagination"><?php echo paginate_links(); ?>	</div>
	</div>
</section>

<?php get_footer();
