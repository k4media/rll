<?php
/**
 * Template for Insights landing page
 *
 */
get_header();

// Swiper.js
wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js' );
wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');

?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
        <?php get_template_part( 'includes/template-parts/content/content-page' ); ?>
	<?php endwhile; ?>
<?php endif; ?>

<section id="insights" class="archive content-hub silo">
    <input type="hidden" name="content_hub" id="content_hub" value="content_hub">
	<input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
	<input type="hidden" id="insights_term" name="insights_term" value="">
	<input type="hidden" id="insights_all_page" name="insights_all_page" value="1">
    <?php if ( isset($wp_query->query['dfdl_country'])) : ?>
        <input type="hidden" id="insights_country" name="insights_country" value="<?php echo $wp_query->query['dfdl_country'] ?>" />
     <?php else: ?>
        <input type="hidden" id="insights_country" name="insights_country" value="" />
     <?php endif; ?>
    <?php
        /**
         * Country Navigation
         */
        do_action('dfdl_solutions_country_nav');
    ?>
    <div id="results_stage"><div>
        <?php do_action("dfdl_insights_swiper", array('category' => 'articles') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'podcasts') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'web-classes') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'publications') ); ?>
    </div></div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.swiper', {
        loop: false,
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
});
</script>

<?php get_footer();