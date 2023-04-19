<?php
/**
 * The template for displaying Insight Archive pages
 */

global $wp, $wp_query;  

// Swiper.js
wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js' );
wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');

/**
 * Get country, category
 */
$country    = "";
$category   = "";
$page_title = "";

/**
 * Build custom query
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

if ( isset($wp_query->query['dfdl_country']) ) {
    $country  = get_term_by("slug", $wp_query->query['dfdl_country'], "dfdl_countries");
    $page_title .= $country->name;
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'dfdl_countries',
            'field'    => 'slug',
            'terms'    => $wp_query->query['dfdl_country'],
        )
    );
}
if ( isset($wp_query->query['dfdl_category']) ) {
    $category = get_term_by("slug", $wp_query->query['dfdl_category'], 'category');
    $page_title .= " " . $category->name;
    $query_args['category_name'] = $wp_query->query['dfdl_category'];
}

/**
 * Filter html title
 */ 
add_filter( 'pre_get_document_title', 'dfdl_filter_archive_insights_title' );


/*
if ( isset($wp_query->query['dfdl_category']) ) {
    $query_args['category_name'] = $wp_query->query['dfdl_category'];
}
if ( isset($wp_query->query['dfdl_country']) ) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'dfdl_countries',
            'field'    => 'slug',
            'terms'    => $wp_query->query['dfdl_country'],
        )
    );
}
*/

/**
 * Date query: limit results to last 2 years
 */
$limit = array(
    'year'  => date("Y") - 2,
    'month' => date("m"),
    'day'   => date("d")
);
$query_args['date_query'] = array(
    array(
        'after' => $limit
    )
);

$the_query  = new WP_Query( $query_args );
$post_class = ( $the_query->have_posts() ) ? "" : "no-results" ;

get_header();

?>
<section id="insights" class="<?php echo esc_attr($category->slug) ?> archive-insights callout silo">
	
    <div id="beacon"></div>
    <div id="subnav-stage"><?php do_action('dfdl_solutions_country_nav'); ?>

    <input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
    <input type="hidden" id="insights_term" name="insights_term" value="<?php echo esc_attr($category->term_id) ?>">
	<input type="hidden" id="insights_all_page" name="insights_all_page" value="1">
    <?php if ( isset($wp_query->query['dfdl_country'])) : ?>
        <input type="hidden" id="insights_country" name="insights_country" value="<?php echo $wp_query->query['dfdl_country'] ?>" />
     <?php else: ?>
        <input type="hidden" id="insights_country" name="insights_country" value="" />
     <?php endif; ?>

    <header class="title">
        <h2 class="page-title"><?php echo $page_title ?></h2>
    </header><!-- .page-header -->

    <div id="results_stage"><div>
        <?php
            /**
			 * Swiper Slider
			 */
            do_action("dfdl_insights_swiper", array('category' => 'insights') );
        ?>
        <div id="insights-posts"  class="posts <?php echo $post_class ?>" >
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
                        $dateline = get_post_meta( $post->ID, 'dateline', true);
                        if ( empty($dateline) ) {
                            $dateline = "Past Event" ;
                        }
                        set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                        set_query_var("dateline", $dateline);
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
		<?php if ( $the_query->found_posts > $the_query->post_count ) : ?>
			<div class="see-more">
				<button id="insights-all-see-more" class="button green ghost see-more">See More<span></span></button>
			</div>
		<?php endif; ?>
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
