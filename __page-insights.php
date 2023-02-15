<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();
?>

<div id="insights" class="">

	<?php
		/**
		 * Country Navigation
		 */
		do_action('dfdl_solutions_country_nav');
	?>

	<p style='text-align: center; margin: 120px 0 120px 0'>featured post slider</p>

	<?php do_action("dfdl_news_callout", array('category' => 'news') ); ?>

</div>


<?php get_footer();