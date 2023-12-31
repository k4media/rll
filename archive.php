<?php
	/**
	 * The template for displaying archive pages
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	 *
	 */

	global $wp, $wp_query;  

	get_header();

	// Swiper.js
	wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js' );
	wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');

	// Select2
	wp_enqueue_style('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.css', null, null, 'all');
	wp_enqueue_script('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.min.js', array("jquery"), null, true );

	if ( ! empty(get_query_var('cat')) ) {
		$term = get_category(get_query_var('cat'));
	} else {
		$pieces  = explode( "/", $wp->request ) ;
		$term    = get_term_by("slug", $pieces[1], 'category');
	}
?>
<section id="insights" class="<?php echo esc_attr($term->slug) ?> archive silo">
	<?php
		/**
		 * Country Navigation
		 */
		//do_action('dfdl_solutions_country_nav');
	?>
	<input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
	<input type="hidden" id="insights_term" name="insights_term" value="<?php echo esc_attr($term->term_id) ?>">
	<input type="hidden" id="insights_all_page" name="insights_all_page" value="1">
    <?php if ( isset($wp_query->query['dfdl_country'])) : ?>
        <input type="hidden" id="insights_country" name="insights_country" value="<?php echo $wp_query->query['dfdl_country'] ?>" />
     <?php else: ?>
        <input type="hidden" id="insights_country" name="insights_country" value="" />
     <?php endif; ?>

	<header class="title">
		<?php the_archive_title( '<h2 class="page-title">', '</h2>' ); ?>
	</header><!-- .page-header -->
	<div id="results_stage" class=""><div>
		<?php
			/**
			 * Swiper Slider
			 */
            do_action("dfdl_insights_swiper", array('category' => $term->term_id) );
        ?>
		<div id="insights-posts" class="posts">
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
							$dateline = get_post_meta( $post->ID, 'dateline', true);
							if ( empty($dateline) ) {
								$dateline = "Past Event" ;
							}
							set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
							set_query_var("dateline", $dateline);
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
		<?php if ( $wp_query->found_posts > $wp_query->post_count ) : ?>
			<div class="see-more">
				<button id="insights-all-see-more" class="button green ghost see-more">See More<span></span></button>
			</div>
		<?php endif; ?>
	</div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.swiper', {
        loop: FontFaceSetLoadEvent,
        preloadImages: false,
        slidesPerView: 1.1,
        navigation: {
            nextEl: '.swiper-next',
            prevEl: '.swiper-prev',
        },
        breakpoints: {
            599: {
                slidesPerView: 1,
            },
            0: {
                slidesPerView: 1.1
            }
        }
    })
})
</script>
<?php get_footer();