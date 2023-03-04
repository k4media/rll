<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

global $wp, $wp_query;  

//var_dump($wp_query->query['dfdl_category']);
//var_dump($wp_query->query['dfdl_country']);
//var_dump($wp_query->query['page'] );

/**
 * Get category and country from URL
 */
$page_title = "";
$country    = "";
$category   = "";

if ( isset($wp_query->query['dfdl_country']) ) {
    $country  = get_term_by("slug", $wp_query->query['dfdl_country'], "dfdl_countries");
    $page_title .= $country->name;
}
if ( isset($wp_query->query['dfdl_category']) ) {
    $category = get_term_by("slug", $wp_query->query['dfdl_category'], 'category');
    $page_title .= " " . $category->name;
}

// filter page title
add_filter( 'pre_get_document_title', 'dfdl_filter_archive_insights_title' );

/**
 * Set up custom query args
 */
$paged = ( isset($wp_query->query['page']) ) ? $wp_query->query['page'] : 1;
$query_args = array(
    'post_type'      => 'post',
    'post_status'    => array("publish"),
    'paged'          => $paged,
    'posts_per_page' => get_option( 'posts_per_page' ),
    'orderby'        => 'date',
    'order'          => 'DESC'
);
if ( isset($wp_query->query['dfdl_category']) ) {
    $query_args['category_name'] = sanitize_text_field($wp_query->query['dfdl_category']);
}
if ( isset($wp_query->query['dfdl_country']) ) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'dfdl_countries',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($wp_query->query['dfdl_country']),
        )
    );
}

$the_query = new WP_Query( $query_args );

get_header();

?>
<section id="insights" class="<?php echo esc_attr($category->slug) ?> archive callout silo">

	<?php
		/**
		 * Country Navigation
		 */
		do_action('dfdl_solutions_country_nav');
	?>

	<header>
		<h2 class="page-title"><?php echo $page_title ?></h2>
	</header><!-- .page-header -->

	<div class="posts">
		<?php if ( $the_query->have_posts() ) : ?>
			<?php while ( $the_query->have_posts() ) : ?>
				<?php

                    global $post;

                    // setup the post
                    $the_query->the_post();

                    // pass data to template
					set_query_var("story", $post );
					set_query_var("term", $category);
                    set_query_var("slug", dfdl_content_hub_category($post->ID));

                    $startdate = get_post_meta( $post->ID, 'startdate', true);
                    if ( isset($startdate) ) {
                        $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                    }
                    set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                    set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                    set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
                    set_query_var("show_date", $show_date);

                    // define template
					$file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $category->slug . '-card.php';

					if ( file_exists($file) ) {
						get_template_part( 'includes/template-parts/content/insights', $category->slug . '-card' );
					} else {
						get_template_part( 'includes/template-parts/content/insights', 'news-card' );
					}

				?>
			<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'includes/template-parts/content/content-none' ); ?>
		<?php endif; ?>
	</div>

	<div class="pagination">
    <?php 

        // wp_reset_postdata();

        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $the_query->max_num_pages,
            'current'      => max( 1, $paged )
        ) );
    ?>
    </div>
</section>

<?php get_footer();
