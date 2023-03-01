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
        <?php //do_action("dfdl_insights_swiper", array('category' => 'insights') ); ?>
        <?php //do_action("dfdl_insights_callout", array('category' => 'news') ); ?>
        <?php //do_action("dfdl_insights_callout", array('category' => 'legal-and-tax-updates') ); ?>
        <?php //do_action("dfdl_insights_callout", array('category' => 'events') ); ?>
        <?php // do_action("dfdl_content_hub_callout"); ?>
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