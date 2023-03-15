<?php

    /*
    Template Name: Search Page
    */

    get_header();

    /*
    $query_args = array(
        'post_type'      => array('dfdl_countries',),
        'post_status'    => 'publish',
        's'              => $_REQUEST['s'],
        'posts_per_page' => 24,
        'paged'          => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => false,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );

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

    $query = new WP_Query();
    $query->parse_query( $query_args );
    relevanssi_do_query( $query );

    */

?>

<div id="searchpage" class="page page-wrapper silo">

    <div class="searchpage-searchform">
        <?php get_search_form(); ?>
    </div>

    <?php do_action("dfdl_search_solutions") ?>

</div>

<?php get_footer(); ?>