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

<section id="insights" class="">

    <?php
        /**
         * Country Navigation
         */
        do_action('dfdl_solutions_country_nav');
    ?>
    <div id="results_stage"><div>
        <?php // do_action("dfdl_insights_swiper", array('category' => 'articles') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'podcasts') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'videos') ); ?>
        <?php //do_action("dfdl_insights_callout", array('category' => 'web-classes') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'publications') ); ?>
    </div></div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var myswiper = new Swiper('.swiper', {
        loop: true,
        preloadImages: false,
        lazy: true,
        watchSlidesVisibility: true,
        navigation: {
            nextEl: '.swiper-next',
            prevEl: '.swiper-prev',
        }
    });
});
</script>

<?php get_footer();