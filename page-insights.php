<?php
/**
 * Template for Insights landing page
 *
 */
get_header();
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
        
        <p style='text-align: center; margin: 120px 0 120px 0'>featured post slider</p>

        <?php do_action("dfdl_insights_callout", array('category' => 'news') ); ?>

        <?php do_action("dfdl_insights_callout", array('category' => 'legal-and-tax') ); ?>

        <?php do_action("dfdl_insights_callout", array('category' => 'events') ); ?>

        <?php do_action("dfdl_insights_callout", array('category' => 'content-hub') ); ?>

    </div></div>
</section>


<?php get_footer();