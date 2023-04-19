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
    
    <div id="beacon"></div>
    <div id="subnav-stage"><?php do_action('dfdl_solutions_country_nav'); ?></div>

    <input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
	<input type="hidden" id="insights_term" name="insights_term" value="667">
	<input type="hidden" id="insights_all_page" name="insights_all_page" value="1">
    <?php if ( isset($wp_query->query['dfdl_country'])) : ?>
        <input type="hidden" id="insights_country" name="insights_country" value="<?php echo $wp_query->query['dfdl_country'] ?>" />
     <?php else: ?>
        <input type="hidden" id="insights_country" name="insights_country" value="" />
     <?php endif; ?>
 
    <div id="results_stage">
        <div>
            <?php do_action("dfdl_insights_swiper", array('category' => 'insights') ); ?>
            <?php do_action("dfdl_insights_callout", array('category' => 'news') ); ?>
            <?php do_action("dfdl_insights_callout", array('category' => 'legal-and-tax-updates') ); ?>
            <?php do_action("dfdl_insights_callout", array('category' => 'events') ); ?>
            <?php do_action("dfdl_content_hub_callout"); ?>
        </div>
    </div>
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