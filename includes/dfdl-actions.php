<?php

// WIP -- update tax counts
// add_action('save_post', 'dfdl_update_custom_taxonomy_counts', 10, 3);
function dfdl_update_custom_taxonomy_counts($post_id, $post_after, $post_before) {
    //$countries = dfdl_get_countries();
    // var_dump($countries);
    //$count = wp_update_term_count( $countries, 'dfdl_countries' );
    // var_dump($count);
}

/**
 * Filter archive query for country endpoints
 * 
 * Called by archive.php
 */
// add_action( 'pre_get_posts', 'dfdl_archive_query');
/*
function dfdl_archive_query($query) {

    global $wp;  

    // var_dump($query->pagename);

	if ( ! is_admin() && $query->is_main_query() && "dfdl_insights" === $query->query['pagename'] ) {

        var_dump($query->query['pagename']);
        var_dump($query->query['dfdl_country']);
        var_dump($query->query['dfdl_category']);

        $query->set( 'tax_query', $tax_query );

        if ( isset($query->query['dfdl_country']) ) {
            $tax_query = array(
                array(
                    'taxonomy' => 'dfdl_countries',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($query->query['dfdl_country']),
                )
            );
            $query->set( 'tax_query', $tax_query );
        }
		if( isset($query->query['dfdl_category']) ) {
            $query->set( 'cat', sanitize_text_field($query->query['dfdl_category']) );
        }


	}

}
*/

/**
 * Archive date query filter
 */
add_action( 'dfdl_in_the_news', 'dfdl_in_the_news' );
function dfdl_in_the_news() {

    global $wp;

    $pieces    = explode("/", $wp->request ) ;
    $author_id = intval(end($pieces));
    $author    = get_user_by("id", $author_id);

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        's'              => $author->display_name,
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => false,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

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

    $posts = new WP_Query( $query_args );

    if ( ! empty( $posts->posts ) ) {

        /**
         * "View All" link
         */
        $archive_link = get_home_url(null, "/s=" . $author->display_name);

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $posts->posts as $post ) {

            $term = dfdl_post_terms($post->ID);
            $term = get_term_by("name", $term[0], "category");

            if ( "events" === $term->slug ) {
                $startdate = get_post_meta( $post->ID, 'startdate', true);
                if ( isset($startdate) ) {
                    $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                    set_query_var("show_date", $show_date);
                }
                set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
            }
            set_query_var("story", $post);
            set_query_var("term", $term);

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }

        }
        $news = ob_get_clean();

        
        echo '<section id="dfdl-in-the-news" class="xtra callout silo">';
        echo '<header><h2 class="title">In the News</h2><a href="' . $archive_link . '">View All</a></header>';
        echo '<div class="posts">' . $news . '</div>';
        echo '</section>';

    }
}


/**
 * Archive date query filter
 */
add_action( 'dfdl_written_by', 'dfdl_written_by' );
function dfdl_written_by() {

    global $wp;

    $pieces    = explode("/", $wp->request ) ;
    $author_id = intval(end($pieces));
    $author    = get_user_by("id", $author_id);

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'author'         => $author_id,
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => false,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

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
            // 'after' => $limit
        )
    );

    $posts = new WP_Query( $query_args );

    if ( ! empty( $posts->posts ) ) {


        /**
         * Strip 'content-hub' from url
         * This keeps url structure consistent
         * insights/[category]/[country]
         */ 
        $archive_link = str_replace("content-hub/", "", $archive_link);

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $posts->posts as $post ) {

            $term = dfdl_post_terms($post->ID);
            $term = get_term_by("name", $term[0], "category");
            
            if ( "events" === $term->slug ) {
                $startdate = get_post_meta( $post->ID, 'startdate', true);
                if ( isset($startdate) ) {
                    $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                    set_query_var("show_date", $show_date);
                }
                set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
            }
            set_query_var("story", $post);
            set_query_var("term", $term);

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }

        }
        $news = ob_get_clean();
        
        echo '<section id="dfdl-written-by" class="xtra callout silo">';
        echo '<header><h2 class="title">Written by ' . esc_attr($author->display_name) . '</h2><a href="' . $archive_link . '">View All</a></header>';
        echo '<div class="posts">' . $news . '</div>';
        echo '</section>';

    }
}

/**
 * Archive date query filter
 */
add_action( 'pre_get_posts', 'exclude_single_posts_home' );
function exclude_single_posts_home($query) {

	if ( ! is_admin() && is_archive() && $query->is_main_query() ) {

        /**
         * Date query: limit results to last 2 years
         */
        $limit = array(
            'year'  => date("Y") - 2,
            'month' => date("m"),
            'day'   => date("d")
        );
        $query->set( 'date_query', array(
            array(
                'after' => $limit
            )
        ));

	}
}


/**
 * Insights Swiper
 */
add_action('dfdl_insights_swiper', 'dfdl_insights_swiper');
function dfdl_insights_swiper( array $args ): void {

    if ( ! isset($args) ) {
        return;
    }

    if ( "insights" === $args['category']) {
        /**
         * Insight categories
         * News = 89
         * Legal and Tax Updates (old) = 109
         * Legal and Tax (new) = 91
         * Events = 96; but do not include!
         * 
         */
        $categories = array(89);
    } else {
        $categories = array($args['category']);
    }
    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'cat'            => $categories,
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => false,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

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

    $posts = new WP_Query( $query_args );
    
    if ( ! empty( $posts->posts ) ) {

        /**
         * Load swiper template part
         */
        ob_start();
        foreach( $posts->posts as $p ) {
            $categories = dfdl_post_terms($p->ID);
            set_query_var("categories", $categories);
            set_query_var("story", $p);
            get_template_part( 'includes/template-parts/content/swiper', 'slide' );
        }
        $slides = ob_get_clean();

        /**
         * Load swiper-callout template part
         */
        ob_start();
            get_template_part( 'includes/template-parts/content/swiper', 'callout' );
        $template = ob_get_clean();

        $template = str_replace("{posts}", $slides, $template);

        echo $template;

    }

}

/**
 * DFDL Content Hub Callout
 * 
 * Includes multiple categories
 */
add_action('dfdl_content_hub_callout', 'dfdl_content_hub_callout');
function dfdl_content_hub_callout() {

    $query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

    /**
     * Podcasts        = 839
     * Publications    = 744
     * Brochures       = 761
     * Invest Guides   = 705
     * Tax Guides      = 686
     * Videos          = 717
     */
    $query_args['cat'] = array(839,744,761,705,686,717);

    /**
     * Set query country
     */
    $sections = dfdl_get_section();
    if( isset($sections[1]) && in_array( $sections[1], constant('DFDL_COUNTRIES') ) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => $sections[1],
            )
        );
    }

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

    $posts = new WP_Query( $query_args );

    if ( ! empty( $posts->posts ) ) {

        /**
         * "View All" link
         */
        $term = get_term_by("slug", "content-hub", "category");
        $archive_link = get_term_link($term);
        if ( is_wp_error($archive_link) ) {
            $archive_link = "#";
        } elseif ( isset($sections[1]) && in_array( $sections[1], constant('DFDL_COUNTRIES') ) ) {
             $archive_link .= $sections[1] . "/";
        }

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $posts->posts as $post ) {

            /**
             * will need terms in future
             */
             // $slug = dfdl_content_hub_category($post->ID);

            set_query_var("story", $post);
            set_query_var("categories", dfdl_post_terms($post->ID));
            get_template_part( 'includes/template-parts/content/insights', 'content-hub-card' );

        }
        $cards = ob_get_clean();

        /**
         * Insert cards into template
         */
        ob_start();
            set_query_var("term", $term);
            get_template_part( 'includes/template-parts/content/insights', 'callout' );
        $template = ob_get_clean();
        $output   = str_replace("{posts}", $cards, $template);
        $output   = str_replace("{archive_link}", $archive_link, $output);

        echo $output;

    }

}

/**
 * DFDL Insights Callouts
 * 
 * News, Legal and Tax Updates, Events
 */
add_action('dfdl_insights_callout', 'dfdl_insights_callout');
function dfdl_insights_callout( array $args ): void {

    global $wp_query;

    if ( ! isset($args['category']) ) {
        echo "<p>A category slug is required.</p>";
        return;
    }

    /**
     * Use tax_query to get child category posts, too
     */
    $query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $args['category']
            )
         )
    );

    if ( isset($wp_query->query['dfdl_country']) ) {
        $query_args['tax_query'][] = array(
            
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => sanitize_text_field($wp_query->query['dfdl_country'])
            
        );
    }

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

    $posts = new WP_Query( $query_args );

    if ( ! empty( $posts->posts ) ) {

        /**
         * "View All" link
         */
        if ("content-hub" === $args['category'] ) {
            $term = get_term_by("slug", "content-hub", "category");
            $link_cat = $term->slug;
        } else {
            $link_cat = $args['category'];
        }

        $term = get_term_by("slug", $link_cat, "category");
        $archive_link = get_term_link($term);
        
        /**
         * Strip 'content-hub' from url
         * This keeps url structure consistent
         * insights/[category]/[country]
         */ 
        $archive_link = str_replace("content-hub/", "", $archive_link);

        /**
         * Add dfdl_country if needed
         */
        if( isset($wp_query->query['dfdl_country']) ) {
             $archive_link .= $wp_query->query['dfdl_country'] . "/";
        }

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $posts->posts as $post ) {

            if ( "events" === $term->slug ) {
                $startdate = get_post_meta( $post->ID, 'startdate', true);
                if ( isset($startdate) ) {
                    $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                    set_query_var("show_date", $show_date);
                }
                set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
            }
            set_query_var("story", $post);
            set_query_var("term", $term);

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }

        }
        $news = ob_get_clean();

        /**
         * Insert cards into template
         */
        ob_start();
            get_template_part( 'includes/template-parts/content/insights', 'callout' );
        $template = ob_get_clean();
        $output   = str_replace("{posts}", $news, $template);
        $output   = str_replace("{archive_link}", $archive_link, $output);

        echo $output;

    }
 
}

/**
 * Contact Form
 */
add_action('dfdl_contact_form', 'dfdl_contact_form');
function dfdl_contact_form(): void {
    get_template_part( 'includes/template-parts/forms/form', 'contact' );
}


/**
 * DFDL Insights Filter Ajax
 * 
 * Return HTML results
 * 
 * ... @insights_solutions
 * ... @insights_ytype
 * ... @insights_ysort
 * 
 * @return string
 * 
 */
add_action('wp_ajax_filter_insights', 'dfdl_ajax_teams_insights');
add_action('wp_ajax_nopriv_filter_insights', 'dfdl_ajax_teams_insights');
function dfdl_ajax_teams_insights(): array {

    /**
    * Response 
    */
    $response = array();
    $response['code']    = 0;
    $response['message'] = '';
    $response['status']  = '';
    $response['html']    = '';
    $response['count']   = 0;

    /**
    * Validate nonce
    */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_insights" )) {
        $response["status"]	= "invalid nonce";
        echo json_encode($response);
        exit;
    }

    /**
    * Valid input values
    */
    $valid = array();
    $valid['solutions'] = dfdl_get_solutions('slug');

    /**
    * Buffer for clean post inputs
    */
    $clean = array();

    /**
    * Validate $_POST vars
    */
    $post_solutions  = explode(',', $_POST['iSolutions']);
    foreach( $post_solutions as $p ) {
        if ( in_array($p, $valid['solutions'])) {
            $term = get_term_by("slug", $p, 'dfdl_solutions');
            $clean['solutions'][] = $term->term_id;
        }
    }

    $post_categories  = explode(',', $_POST['iCategories']);
    foreach( $post_categories as $p ) {
        if ( in_array($p, $valid['solutions'])) {
            //$term = get_term_by("slug", $p, 'dfdl_solutions');
            //$clean['solutions'][] = $term->term_id;
        }
    }

    $post_years  = explode(',', $_POST['iyears']);
    foreach( $post_years as $p ) {
        //$term = get_term_by("slug", $p, 'dfdl_solutions');
        //$clean['solutions'][] = $term->term_id;
    }



    // add solutions
    if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array (
                'key' => '_dfdl_user_solutions',
                'value' => $clean['solutions'],
                'compare' => 'IN'
            ),
        );
    }

    // add country
    if ( "undefined" !== $clean_country ) {
        $term = get_term_by('slug', $clean_country, 'dfdl_countries');
        $args['meta_query'][] = array(
            'relation' => 'AND',
            array(
                'key' => '_dfdl_user_country',
                'value' => $term->term_id,
            ),
        );
    }

    // add years

    // add categories

    // limit members in admin
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        $args['number'] = 4;
    }

    /**
     * User query
     */
    $users  = new WP_User_Query($args);
    if ( ! empty( $users->get_results() ) ) {
        ob_start();
        foreach ( $users->get_results() as $user ) {
            set_query_var("user", $user);
            get_template_part( 'includes/template-parts/content/insights', 'news-card' );
        }
        $output = ob_get_clean();
    }
    
    /**
    * Validate response
    */
    if ( isset($output) && "" !== $output ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['count']  = $users->results;
    } else {
        $response['code']   = 400;
        $response['status'] = 'empty result set';
    }

   /**
    * Debug info
    */
    $response['debug']  = $args;

   /**
    * Send response
    */
    echo json_encode($response);

   /**
    * Exit
    */
    exit;
    
}


/**
 * DFDL Teams Filter Ajax
 * 
 * Return HTML results
 * 
 * @teams_solutions
 * @teams_ysort
 * 
 * @return string
 * 
 */
add_action('wp_ajax_filter_teams', 'dfdl_ajax_teams_filter');
add_action('wp_ajax_nopriv_filter_teams', 'dfdl_ajax_teams_filter');
function dfdl_ajax_teams_filter(): array {

    /**
    * Response 
    */
    $response = array();
    $response['code']    = 0;
    $response['message'] = '';
    $response['status']  = '';
    $response['html']    = '';

    /**
    * Validate nonce
    */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_teams" )) {
        $response["status"]	= "invalid nonce";
        echo json_encode($response);
        exit;
    }

    /**
    * Valid input values
    */
    $valid = array();
    $valid['solutions'] = dfdl_get_solutions('slug');

    /**
    * Buffer for clean post inputs
    */
    $clean = array();

    /**
    * Validate $_POST vars
    */
    $post_solutions  = explode(',', $_POST['tSolutions']);
    foreach( $post_solutions as $p ) {
        if ( in_array($p, $valid['solutions'])) {
            $term = get_term_by("slug", $p, 'dfdl_solutions');
            $clean['solutions'][] = $term->term_id;
        }
    }
    $clean_country = sanitize_text_field($_POST['tCountry']);
    $clean_sort    = sanitize_text_field($_POST['tSort']);

    /**
     * Team Members query args
    */
    $args                = array();
    $args['number']      = 16;
    $args['count_total'] = true;
    $args['meta_key']    = '_dfdl_member_rank';

    if ( "a-z" == $clean_sort ) {
        $args['orderby']     = array( '_dfdl_member_rank' => 'ASC', 'user_nicename' => 'ASC' );
    } else {
        $args['orderby']     = array( '_dfdl_member_rank' => 'ASC', 'user_nicename' => 'DESC' );
    }

    // add solutions
    if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array (
                'key' => '_dfdl_user_solutions',
                'value' => $clean['solutions'],
                'compare' => 'IN'
            ),
        );
    }

    // add country
    if ( "undefined" !== $clean_country ) {
        $term = get_term_by('slug', $clean_country, 'dfdl_countries');
        $args['meta_query'][] = array(
            'relation' => 'AND',
            array(
                'key' => '_dfdl_user_country',
                'value' => $term->term_id,
            ),
        );
    }

    // limit members in admin
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        $args['number'] = 4;
    }

    /**
     * User query
     */
    $users  = new WP_User_Query($args);
    if ( ! empty( $users->get_results() ) ) {
        ob_start();
        foreach ( $users->get_results() as $user ) {
            set_query_var("user", $user);
            get_template_part( 'includes/template-parts/content/member' );
        }
        $output = ob_get_clean();
    }
    
    /**
    * Validate response
    */
    if ( isset($output) && "" !== $output ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['count']  = $users->results;
    } else {
        $response['code']   = 400;
        $response['status'] = 'empty result set';
    }

   /**
    * Debug info
    */
    $response['debug']  = $args;

   /**
    * Send response
    */
    echo json_encode($response);

   /**
    * Exit
    */
    exit;
    
}

/**
 * DFDL Award Filter Ajax
 * 
 * Return HTML results
 * 
 * @filter_types
 * @filter_solutions
 * @filter_years
 * 
 * @return string
 * 
 */
 add_action('wp_ajax_filter_awards', 'dfdl_ajax_awards_filter');
 add_action('wp_ajax_nopriv_filter_awards', 'dfdl_ajax_awards_filter');
 function dfdl_ajax_awards_filter() {

    /**
     * Response 
    */
    $response = array();
    $response['code']    = 0;
    $response['message'] = '';
    $response['status']  = '';
    $response['html']    = '';

    /**
     * Validate nonce
     */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_awards" )) {
        $response["status"]	= "invalid nonce";
        echo json_encode($response);
        exit;
    }

    /**
     * Valid input values
     */
    $valid = array();
    $valid['bodies']    = dfdl_get_award_bodies('slug');
    $valid['solutions'] = dfdl_get_solutions('slug');
    $valid['years']     = dfdl_get_award_years('slug');

    /**
     * Buffer for clean post inputs
     */
    $clean = array();

    /**
     * Validate $_POST vars
     */
    $post_bodies  = explode(',', $_POST['fTypes']);
    foreach( $post_bodies as $p ) {
        if ( in_array($p, $valid['bodies'])) {
            $clean['bodies'][] = $p;
        }
    }
    $post_solutions  = explode(',', $_POST['fSolutions']);
    foreach( $post_solutions as $p ) {
        if ( in_array($p, $valid['solutions'])) {
            $clean['solutions'][] = $p;
        }
    }
    $post_years  = explode(',', $_POST['fYears']);
    foreach( $post_years as $p ) {
        if ( in_array($p, $valid['years'])) {
            $clean['years'][] = $p;
        }
    }
    $clean_country  = sanitize_text_field($_POST['fCountry']);

    /**
     * Get Awards.
     */
    $args = array(
        'country'   => $clean_country,
        'bodies'    => $clean['bodies'],
        'solutions' => $clean['solutions'],
        'years'     => $clean['years'],
    );
    $awards = dfdl_get_awards($args);

    /**
     * Validate response
     */
    if ( ! empty($awards) ) {
        $response['code']    = 200;
        $response['status']  = 'success';
        $response['html']  = $awards;
    } else {
        $response['code']   = 400;
        $response['status'] = 'empty result set';
    }

    /**
     * Debug info
     */
    $response['debug']  = $args;

    /**
     * Send response
     */
    echo json_encode($response);

    /**
     * Exit
     */
    exit;
     
 }

/**
 * DFDL Award Filter
 * 
 * Return HTML select2 for filter facet
 * 
 * @type Filter facet: type, solution, year
 * 
 * @return string
 * 
 */
add_action('dfdl_filter', 'dfdl_filter', 1);
function dfdl_filter( string $filter ): void {
    switch($filter) {
        case "award_bodies":
            $options = dfdl_get_award_bodies();
            break;
        case "award_solutions":
        case "teams_solutions":
        case "insights_solutions":
            $options = dfdl_get_solutions_tax();
            break;
        case "award_years":
            $options = dfdl_get_award_years();
            break;
        case "insights_years":
            $options = dfdl_get_insights_years();
            break;
        case "teams_sort":
                $options = dfdl_get_teams_sort();
                break;
        default:
            // no default
    }

    $select   = array();
    if ( "teams_sort" === $filter ) {
        $select[] = '<select id="' . $filter . '" name="' . $filter . '">';
    } else {
        $select[] = '<select multiple="multiple" id="' . $filter . '" name="' . $filter . '">';
    }
    if ( isset($options) ) {
        foreach( $options as $option ) {
            if ( "teams_sort" === $filter && 1 === $option->term_id) {
                $selected = 'selected="selected"';
            } else {
                $selected = "";
            }
            $select[] = '<option ' . $selected . ' data-id="' . $option->term_id . '" name="' . $option->slug. '" value="' . $option->slug . '">' .  $option->name . '</option>'; 
        }
    }
    $select[] = '</select>';
    echo implode($select);
    
}

/**
 * Country Nav
 */
add_action('dfdl_solutions_country_nav', 'dfdl_solutions_country_nav');
function dfdl_solutions_country_nav() {

    global $wp;

    $pieces     = explode("/", $wp->request ) ;
    $sections   = array("teams", "awards", "contact-us", "insights");
    $section    = array_values(array_intersect( $pieces, $sections ));

    if ( isset($section[0]) ) {
        $section = $section[0];
    } else {
        /**
         * Set fallback for is_admin()
         */
        if ( is_admin() ) {
            echo '<nav class="country-subnav-stage"><ul><li>[country navigation may depending on section]</li></ul></nav>';
        }
        return;
    }
    
    /**
     * Locations, as determined from subpages
     */
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 12,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
     );
    $pages = new WP_Query( $args );

    $nav      = array();
    $home_url = get_home_url(NULL);

    /**
     * Make navigation links
     * 
     * Insight post links are different to others
     * /insights/[category]/[country]
     * 
     * Other pages are:
     * /locations/[country]/(.*)
     * 
     * Full details in dfdl-rewrites.php
     * 
     */

    // var_dump($pieces);

    foreach($pages->posts as $page) {

        if ( is_admin() ) {

            $nav[] = '<li><a class="current-menu-item" href="#">' . $page->post_title . '</a></li>' ;

        } else {

            /** 
             * insights urls are a different format
             * ex: /insights/[category]/[country]/
             */
            if ( "insights" === $section ) {

                /**
                 * 3 pieces means we have a category and country
                 * build the link url accordingly
                 * 
                 * url: /insights/[category]/[country]/
                 * ex: /insights/news/cambodia/
                 */
                if ( count($pieces) >= 3 ) {

                    /**
                    * url: /insights/[category]/[country]/
                    */
                    if ( in_array(strtolower($page->post_name), $pieces)  ) {
                        $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    } else {
                        $nav[] = '<li><a href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    }

                } elseif ( count($pieces) === 2 ) {

                    /**
                     * 2 pieces means $pieces[1] is a [category] OR [country]
                     * 
                     * ex: /insights/cambodia/
                     * ex: /insights/news/
                     * 
                     */

                    if ( in_array($pieces[1], constant('DFDL_COUNTRIES')) ) {

                        /**
                         * if $pieces[1] is a country, leave it out of the new url
                         * we don't want two countries in the 
                         * 
                         * new url: /insights/[country]/
                         */
                        if ( in_array(strtolower($page->post_name), $pieces)  ) {
                            $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        } else {
                            $nav[] = '<li><a href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        }

                    } else {

                        /**
                         * if $pieces[1] is a category, include country
                         * 
                         * new url: /insights/[category]/[country]/
                         */
                        if ( in_array(strtolower($page->post_name), $pieces)  ) {
                            $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        } else {
                            $nav[] = '<li><a href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        }

                    }

                } else {

                    /**
                     * url: /insights/
                     */
                    if ( in_array(strtolower($page->post_name), $pieces)  ) {
                        $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    } else {
                        $nav[] = '<li><a href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    }
                }
                
            } else {

                /**
                 * Standard link building
                 * 
                 * /locations/[country]/(.*)
                 * ex /locations/cambodia/[teams|awards|etc]
                 */
                if ( in_array(strtolower($page->post_name), $pieces)  ) {
                    $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
                } else {
                    $nav[] = '<li><a href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
                }

            }
            
        }
    }

    /**
     * Section Filters & Sorts
     */

    // Enqueue filter scripts
    if ( "teams" === $section || "awards" === $section || "insights" === $section ) {
        wp_enqueue_style('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.css', null, null, 'all');
		wp_enqueue_script('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.min.js', array("jquery"), null, true );
    }
    // Teams filter
    if ( "teams" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "teams");
        $nav[] = ob_get_clean();
    }
    // Awards filter
    if ( "awards" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "awards");
        $nav[] = ob_get_clean();
    }
    // Insights filter
    if ( "insights" === $section ) {
        ob_start();
            if ( isset($pieces[1]) ) {
                get_template_part("includes/template-parts/filters/filter", $pieces[1]);
            } else {
                get_template_part("includes/template-parts/filters/filter", "insights");
            }
        $nav[] = ob_get_clean();
    }

    /**
     * Prepare html output
     */
    $class  = is_admin() ? "admin" : "" ;
    $output = array();

    $output[] = '<nav class="country-subnav-stage silo"><ul class="' . $class . ' ' . $section . '-country-nav country-nav">';
    if ( "all" === end($pieces) ) {
        $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $section . '/all/">All</a></li>';
    } else {

        if( "awards" === $section ) {
            if ( count($pieces) == 1 ) {
                $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $section . '/">All</a></li>';
            } else {
                $output[] = '<li><a href="' . $home_url . '/' . $section . '/">All</a></li>';
            }
        } elseif ( "contact-us" === end($pieces) ) {
            if ( count($pieces) == 1 ) {
                $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/contact-us/">Regional</a></li>';
            } else {
                $output[] = '<li><a href="' . $home_url . '/contact-us/">Regional</a></li>';
            }
        } elseif ( "insights" === $section ) {

            if ( count($pieces) > 1 ) {
                $output[] = '<li class="back"><a href="' . dfdl_insights_back_link() . '">Back</a></li>';

            }
            //$output[] = '<li class="back"><a href="' . $home_url . '/insights/">Back</a></li>';
            //} else {
                // $output[] = '<li class="back"><a href="' . dfdl_insights_back_link() . '">Back</a></li>';
            //}
            //$output[] = '<li class="back"><a href="' . dfdl_insights_back_link() . '">Back</a></li>';

        } else {

             $output[] = '<li><a href="' . $home_url . '/' . $section . '/all/">All</a></li>';
            
        }
    }

    $output[] = implode("", $nav);
    $output[] = '</ul>';
    $output[] = '</nav>';

    // output
    echo implode("", $output);

}

/**
 * DFDL Related Posts
 */
add_action('dfdl_related_stories', 'dfdl_related_stories');
function dfdl_related_stories(): void {

    $title = "Related Articles";

    $sections = dfdl_get_section();
    if ( in_array("events", $sections) ) {
        $section = "events";
    } else if( 3 === count($sections) ) {
        $section = $sections[1];
        if ( "content-hub" === $section ) {
            $section = $sections[2];
        }
    }

    $term = get_term_by("slug", $section, "category");

    $categories = wp_get_post_categories(get_the_ID());
    $query_args = array(
        'post_type'      => 'post',
        'category__in'   => $categories,
        'post__not_in'   => array(get_the_ID()),
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

    if ( "events" === $section) {
        $query_args['meta_key']  = 'startdate';
        $query_args['meta_type'] = 'date';
        $query_args['orderby']   = 'meta_value_num';
        $query_args['order']     = 'DESC';
    }

    $dfdl_country = get_the_terms(get_the_ID(), "dfdl_countries");

    if (isset($dfdl_country) && false !== $dfdl_country ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => $dfdl_country[0]->slug
            )
        );
    }
    
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

    $posts = new WP_Query( $query_args );

    /**
     * Load related posts template part
     */
     if ( $posts->post_count > 0 ) {

        ob_start();
        foreach( $posts->posts as $p ) {

            /**
             * Events fields
             */
            $startdate = get_post_meta( $p->ID, 'startdate', true);
            if ( isset($startdate) ) {
                $show_date = mysql2date( get_option( 'date_format' ), $startdate );
            }
            set_query_var("sponsor", get_post_meta( $p->ID, 'sponsor', true));
            set_query_var("dateline", get_post_meta( $p->ID, 'dateline', true));
            set_query_var("timeline", get_post_meta( $p->ID, 'timeline', true));
            set_query_var("show_date", $show_date);

            set_query_var("story", $p);
            set_query_var("term", $term);
            set_query_var("category", dfdl_post_terms($p->ID, array('type'=>'subcategory')));
            set_query_var("class", "related");

            /*
            if ( "events" === $section ) {
                get_template_part( 'includes/template-parts/content/insights', 'events-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }
            */
            

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }
            
        }

        $cards = ob_get_clean();

        /**
         * Load related posts template part
         */
        ob_start();
            set_query_var("title", $title);
            get_template_part( 'includes/template-parts/content/single', 'related-content' );
        $template = ob_get_clean();

        $template = str_replace("{posts}", $cards, $template);

        echo $template;

     }
    

}


/**
 * Get resuable block by ID
 */
add_action( 'dfdl_reusable_block', 'dfdl_reusable_block', 10, 1);
function dfdl_reusable_block( int $id ): void {
    if ( ! isset($id) ) 
        return;
    $block = get_post( $id );
    echo apply_filters( 'the_content', $block->post_content);
}

/**
 * ACF Color Palette
 *
 * Add default color palatte to ACF color picker for branding
 * Match these colors to colors in /functions.php & /assets/scss/partials/base/variables.scss
 *
*/
add_action( 'acf/input/admin_footer', 'wd_acf_color_palette' );
function wd_acf_color_palette() { ?>
<script type="text/javascript">
(function($) {
     acf.add_filter('color_picker_args', function( args, $field ){
          // add the hexadecimal codes here for the colors you want to appear as swatches
          args.palettes = ['#000000', '131313', '#7D7D7D', '#F6F6F6', '#004C45', '#10B565', '#66000', '#FFBD5C', '#ffffff']
          // return colors
          return args;
     });
})(jQuery);
</script>
<?php }

/**
 * DFDL Logo
 */
add_action('dfdl_logo', 'dfdl_logo');
function dfdl_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    /*
    if ( is_front_page() ) {
        echo '<div class="site-name"><img src="' . $image[0]. '"></div>';
    } else {
        echo '<div class="site-name"><a href="' . get_home_url(). '"><img src="' . $image[0] . '"></a></div>';
    }
    */
    echo '<div class="site-name"><a href="' . get_home_url(). '"><img src="' . $image[0] . '"></a></div>';
}

/**
 * Footer actions
 */
add_action('footer_logo', 'footer_logo');
function footer_logo() {
    $mods = get_theme_mods();
    echo '<div class="site-name"><img src="' . $mods['dfdl_logo_reversed'] . '"></div>';
}

add_action('footer_text', 'footer_text');
function footer_text() {
    if( function_exists('get_field') ) {
        $text = get_field('ftext', 'option');
        if ( isset($text) ) {
            echo '<div class="footer-text">' . str_replace('[year]', date('Y'), $text) . '</div>';
        }
    }
}

add_action('footer_nav', 'footer_nav');
function footer_nav() {
    echo '<div class="footer-nav">';
    wp_nav_menu( array(
        'theme_location'	=> 'footer',
        'fallback_cb'       => '',
        'container'         => 'nav'
    ) );
    echo '</div>';
}

add_action('newsletter_signup', 'newsletter_signup');
function newsletter_signup() {
    get_template_part( 'includes/template-parts/footer/newsletter', 'signup' );
}

add_action('copyright_notice', 'copyright_notice');
function copyright_notice() {
    if( function_exists('get_field') ) {
        $notice = get_field('cnotice', 'option');
        if ( isset($notice) ) {
            echo '<div class="copyright-notice">' . str_replace('[year]', date('Y'), $notice) . '</div>';
        }
    }
}

add_action('social_links', 'social_links');
function social_links() {
    $mods = get_theme_mods();
    echo '<div class="social-links">';
    if ( isset($mods['dfdl_linkedin']) ) {
        echo '<a href="' . $mods['dfdl_linkedin'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-linkedin.svg"></a>';
    }
    if ( isset($mods['dfdl_facebook']) ) {
        echo '<a href="' . $mods['dfdl_facebook'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-facebook.svg"></a>';
    }
    if ( isset($mods['dfdl_twitter']) ) {
        echo '<a href="' . $mods['dfdl_twitter'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-twitter.svg"></a>';
    }  
    if ( isset($mods['dfdl_youtube']) ) {
        echo '<a href="' . $mods['dfdl_youtube'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-youtube.svg"></a>';
    }
    echo '</div>';
}

add_action('legal_nav', 'legal_nav');
function legal_nav() {
    echo '<div class="legal-nav">';
    wp_nav_menu( array(
        'theme_location'	=> 'legal',
        'fallback_cb'       => '',
        'container'         => 'nav'
    ) );
    echo '</div>';
}