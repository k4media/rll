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

<?php 
    /**
     * Show page header from cms
     */
    if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
        <?php get_template_part( 'includes/template-parts/content/content-page' ); ?>
	<?php endwhile; ?>
<?php endif; ?>

<section id="insights" class="page-insights silo">

    <!--
    <nav class="subnav-stage silo">
        <ul>
            <li class="back"><a href="<?php echo get_home_url(null, '/solutions/') ?>">Back</a></li>
        </ul>
    </nav>
    -->

    <?php

        /**
         * Country Navigation
         */
        do_action('dfdl_solutions_country_nav');

        /**
         * Add hidden field for country
         */
        if ( isset($wp_query->query['dfdl_country']) ) {
            echo '<input type="hidden" name="insights_country" id="insights_country" value="' . $wp_query->query['dfdl_country'] . '">';
        }
        
    ?>
 
    <div id="results_stage"><div>
        <?php do_action("dfdl_insights_swiper", array('category' => 'insights') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'news') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'legal-and-tax-updates') ); ?>
        <?php do_action("dfdl_insights_callout", array('category' => 'events') ); ?>
        <?php do_action("dfdl_content_hub_callout"); ?>
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
})
</script>
<?php get_footer();