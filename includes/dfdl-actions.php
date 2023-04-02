<?php

// WIP -- update tax counts
// add_action('save_post', 'dfdl_update_custom_taxonomy_counts', 10, 3);
//function dfdl_update_custom_taxonomy_counts($post_id, $post_after, $post_before) {
    //$countries = dfdl_get_countries();
    // var_dump($countries);
    //$count = wp_update_term_count( $countries, 'dfdl_countries' );
    // var_dump($count);
//}

/**
 * Search Insights
 */
add_action( 'dfdl_search', 'dfdl_search' );
function dfdl_search() {

    $solutions = dfdl_search_solutions();
    $teams     = dfdl_search_teams();
    $insights  = dfdl_search_insights() ;

    $results = $solutions . $teams . $insights;

    if ( ! empty($results) ) {

        if ( ! empty($solutions) ) {
            echo $solutions;
        }
        if ( ! empty($teams) ) {
            echo $teams;
        }
        if ( ! empty($insights) ) {
            echo $insights;
        }     

    } else {

        echo '<p class="no-insights not-found">Nothing found.</p>';

    }

}

/**
 * Search Insights
 */
add_action( 'dfdl_search_insights', 'dfdl_search_insights' );
function dfdl_search_insights() {

    $search_term = esc_attr($_REQUEST['q']) ;

    $query_args = array(
        'post_type'      => array('post'),
        'post_status'    => 'publish',
        's'              =>  $search_term,
        'posts_per_page' => 16,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

    /**
     * Date query: limit results to last 2 years
     */
    /*
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
    */

    if ( function_exists('relevanssi_do_query') ) {
        $query = new WP_Query();
        $query->parse_query( $query_args );
        relevanssi_do_query( $query );
    } else {
        $query = new WP_Query($query_args);
    }

    if ( ! empty( $query->posts ) ) {

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $query->posts as $post ) {

            $term = dfdl_post_terms($post->ID);
            $term = get_term_by("name", $term, "category");

            if ( isset($term) && false !==  $term && "events" === $term->slug ) {
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

        ob_start();
            echo  '<section id="dfdl-in-the-news" class="xtra callout">';
            echo '<header><h2 class="title">Insights</h2></header>';
            echo '<div class="posts">' . $news . '</div>';
            echo '</section>';
        $return = ob_get_clean();

        return $return ;

    }
}

/**
 * Search Teams
 */
add_action( 'dfdl_search_teams', 'dfdl_search_teams' );
function dfdl_search_teams() {

    $return      = array();
    $search_term = esc_attr($_REQUEST['q']);

    /**
     * Build User Query
     */
    $query_args = array(
        'number'                 => 8,
        'role__in '              => array('dfdl_member'),
        'role__not_in'           => array('Administrator'),
        'orderby'                => array( 'dfdl_rank' => 'DESC', 'last_name' => 'ASC' ),
        'search_columns'         => array( 'user_email', 'user_url', 'user_nicename', 'display_name' ),
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false
    );
    if ( isset($search_term) && ! empty($search_term) ) {
        $query_args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key'     => 'first_name',
                'value'   => $search_term,
                'compare' => 'LIKE'
            ),
            array(
                'key'     => 'last_name',
                'value'   => $search_term,
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'description',
                'value' => $search_term ,
                'compare' => 'LIKE'
            ),
            array(
                'key'     => '_dfdl_user_solutions_search_terms',
                'value'   => $search_term,
                'compare' => 'LIKE'
            ),
        ); 
    }

    $query = new WP_User_Query($query_args);

    if ( ! empty( $query->get_results() ) ) {
        ob_start();
        foreach( $query->get_results() as $user ) {
             set_query_var("user", $user);
             get_template_part( 'includes/template-parts/content/member' );
        }
        $users = ob_get_clean();

        ob_start();
            get_template_part( 'includes/template-parts/content/swiper', 'team-callout' );
        $template = ob_get_clean();
        $template = str_replace("{posts}", $users, $template);

        $return[] = '<section id="team-grid-swiper" class="search-teams"><header><h2 class="title">Team Members</h2></header>';
        //$return[] = '<div id="results_stage">';
        //$return[] = '<div id="team-grid-swiper" class="s">';
        $return[] = $template;
        $return[] = '</section>';

        return implode($return);

    }
}

/**
 * Search solutions
 */
add_action( 'dfdl_search_solutions', 'dfdl_search_solutions' );
function dfdl_search_solutions() {

    $return = array();

    global $post;

    $solutions  = dfdl_get_solutions();
    $query_args = array(
        'post_type'      => array('page'),
        'post_status'    => 'publish',
        'posts_per_page' => 16,
        'post__in'       => $solutions,
        's'              => esc_attr($_REQUEST['q']),
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'          => false,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );

    /**
     * Don't user relevannsi. Results are too broad
     */ 
    remove_filter( 'posts_request', 'relevanssi_prevent_default_request' );
    remove_filter( 'the_posts', 'relevanssi_query', 99 );
    $query = new WP_Query($query_args);

    // output buffer
    $solutions = array();

    if ( $query->have_posts() ) { 
        while ( $query->have_posts() ) { 
            $query->the_post();
            $image   = get_block_data($post, 'acf/dfdl-page-hero', 'image');
            $image   = wp_get_attachment_image_url($image, 'medium');
            $overlay = get_block_data($post, 'acf/dfdl-page-hero', 'overlay');
            $solution = '<a href="' . get_permalink($post->ID) . '">'  ;
            $solution .= '<div class="solution ' . sanitize_title($post->post_title). ' ">';
                    // image thumbnail
                    $solution .= '<div class="thumbnail" style="background-image:url(' . $image . ')">';
                    $solution .= '<div class="overlay" style="background-color:' . $overlay . '"></div>';
                    $solution .= '</div>';
            $solution .= "<h3>" . esc_attr($post->post_title) . "</h3>";
            $solution .= '</div>';
            $solution .= '</a>';
            $solutions[] = $solution;
        }

        $return[] = '<section id="search-solutions" class="solutions-grid solutions-grid-stage">';
        $return[] = '<header><h2 class="title">Solutions</h2></header>';
        $return[] = '<div class="solutions stage">';
        $return[] = implode($solutions);
        $return[] = '</div>';
        $return[] = '</section>';

        return implode($return);

    }

}

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
        'author__not_in' => $author_id,
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => false,
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

    if ( function_exists('relevanssi_do_query') ) {
        $query = new WP_Query();
        $query->parse_query( $query_args );
        relevanssi_do_query( $query );
    } else {
        $query = new WP_Query( $query_args );
    }

    if ( ! empty( $query->posts ) ) {

        /**
         * "View All" link
         */
        $archive_link = get_home_url(null, "/s=" . $author->display_name);

        /**
         * Queue up news cards
         */
        ob_start();

        foreach ( $query->posts as $post ) {

            $term = dfdl_post_terms($post->ID);
            $term = get_term_by("name", $term, "category");

            set_query_var("story", $post);

            if ( isset($term) && false !== $term && ! is_wp_error($term) ) {

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
                
                set_query_var("term", $term);
    
                $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
                if ( file_exists($file) ) {
                    get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
                } else {
                    get_template_part( 'includes/template-parts/content/insights', 'news-card' );
                }

            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }
            

        }
        $news = ob_get_clean();

        echo '<section id="dfdl-in-the-news" class="xtra callout silo">';
        echo '<header><h2 class="title">In the News</h2><!--<a href="' . $archive_link . '">View All</a>--></header>';
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
            'after' => $limit
        )
    );

    $query = new WP_Query( $query_args );

    if ( ! empty( $query->posts ) ) {

        /**
         * Strip 'content-hub' from url
         * This keeps url structure consistent
         * insights/[category]/[country]
         */ 
        //$archive_link = str_replace("content-hub/", "", $archive_link);

        /**
         * Queue up news cards
         */
        ob_start();
        foreach ( $query->posts as $post ) {

            $term = dfdl_post_terms($post->ID);
            $term = get_term_by("slug", $term, "category");

            set_query_var("story", $post);

            if ( isset($term) && false !== $term && ! is_wp_error($term) ) {

                if ("events" === $term->slug ) {
                    $startdate = get_post_meta( $post->ID, 'startdate', true);
                    if ( isset($startdate) ) {
                        $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                        set_query_var("show_date", $show_date);
                    }
                    set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                    set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                    set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
                }

                set_query_var("term", $term);

                $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
                if ( file_exists($file) ) {
                    get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
                } else {
                    get_template_part( 'includes/template-parts/content/insights', 'news-card' );
                }

            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }

        }
        $news = ob_get_clean();
        
        echo '<section id="dfdl-written-by" class="xtra callout silo">';
        echo '<header><h2 class="title">Written by ' . esc_attr($author->display_name) . '</h2></header>';
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

    global $wp_query;

    if ( ! isset($args) ) {
        return;
    }

    if ( "insights" === $args['category'] ) {
        /**
         * Insight categories
         * News = 667
         * Events = 668
         * Content Hub = 842
         * Legal & Tax = 47
         * 
         */
        $categories = array(667, 667, 47);
    } elseif ( "content-hub" === $args['category'] )  {
        $categories = array(668);
    } elseif ( isset($args['category']) ) {
        $categories = $args['category'];
    } else {
        //default to news
        $categories = array(667);
    }

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => false,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => $categories
            ),
        ),
    );

    /**
     * Add category
     */
    if ( isset($wp_query->query['dfdl_category']) && "content-hub" !== $wp_query->query['dfdl_category'] ) {
        $query_args['category_name'] = $wp_query->query['dfdl_category'];
    }

    /**
     * Add country
     */
    if ( isset($wp_query->query['dfdl_country']) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => $wp_query->query['dfdl_country'],
            ),
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
     * 
     * Article          670
     * DFDL Energy      not yet
     * Podcast          839
     * Publication      744
     * Web Class        717
     */
    $query_args['cat'] = array(670,839,744,717);

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
            $post_term = dfdl_post_terms($post->ID, array("return"=>"term"));
            $hub_cat = dfdl_content_hub_category($post->ID);
            set_query_var("story", $post);
            set_query_var("term", $hub_cat);
            //set_query_var("categories", dfdl_post_terms($post->ID));
            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $post_term->slug . '-card.php';
            if ( file_exists($file ) ) {
                get_template_part( 'includes/template-parts/content/insights', $post_term->slug . "-card" );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'content-hub-card' );
            }
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
                $dateline = get_post_meta( $post->ID, 'dateline', true);
                if ( empty($dateline) ) {
                    $dateline = "Past Event";
                }
                set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                set_query_var("dateline", $dateline);
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
 * Insights more ajax button function
 */
add_action('wp_ajax_insights_more', 'dfdl_ajax_insights_more');
add_action('wp_ajax_nopriv_insights_more', 'dfdl_ajax_insights_more');
function dfdl_ajax_insights_more(): void {

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

    /**
    * Response 
    */
    $response = array();

    /**
    * Validate nonce
    */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_insights_see_more" )) {
        $response["status"]	= "Failed security check. Please reload the page and try again.";
        echo json_encode($response);
        exit;
    }

    /**
     * Sanitized vars
     */
    $clean = array();

    /**
    * Valid input values
    */
    $valid = array();
    $valid['solutions'] = dfdl_get_solutions('slug');

    /**
    * Validate $_POST vars
    */
    if ( isset($_POST['iSolutions']) && "" !== $_POST['iSolutions'] && "undefined" !== $_POST['iSolutions'] ) {
        $post_solutions  = explode(',', $_POST['iSolutions']);
        foreach( $post_solutions as $p ) {
            $term = get_term_by("id", $p, 'dfdl_solutions');
            if ( ! is_wp_error($term) ) {
                $clean['solutions'][] = $term->term_id;
                $filters[] = $term->name;
            }
        }
    }
    if ( isset($_POST['iCategories']) && "" !== $_POST['iCategories'] && "undefined" !== $_POST['iCategories'] ) {
        $post_categories  = explode(',', $_POST['iCategories']);
        if ( ! empty($post_categories) ) {
            foreach( $post_categories as $p ) {
                $term = get_term_by("id", $p, 'category');
                if ( ! is_wp_error($term) ) {
                    $clean['categories'][] = $term->term_id;
                    $filters[] = $term->name;
                }
            }
        }
    } elseif ( isset($_POST['iSection']) && "" !== $_POST['iSection'] && "undefined" !== $_POST['iSection'] ) {
        $term = get_term_by("slug", sanitize_text_field($_POST['iSection']), 'category');
        if ( ! is_wp_error($term) ) {
            $clean['categories'][] = $term->term_id;
            $clean['section'] = $term->term_id;
            $filters[] = $term->name;
        }
    } 
    if ( isset($_POST['iYears']) && "" !== $_POST['iYears'] && "undefined" !== $_POST['iYears'] ) {
        $post_years  = explode(',', $_POST['iYears']);
        foreach( $post_years as $p ) {
            $clean['years'][] = intval($p);
            $filters[] = intval($p);
        }
    }
    if ( isset($_POST['iCountry']) && "" !== $_POST['iCountry'] && "undefined" !== $_POST['iCountry'] ) {
        $clean['country'] = sanitize_text_field($_POST['iCountry']);
    }
    if ( isset($_POST['iTerm']) && "" !== $_POST['iTerm'] && "undefined" !== $_POST['iTerm'] ) {
        $clean['term'] = intval($_POST['iTerm']);
    }
    $clean['page'] = 1;
    if ( isset($_POST['page']) && "" !== $_POST['page'] && "undefined" !== $_POST['page'] ) {
        $clean['page'] = intval($_POST['page']);
    }
    $posts_per_page = get_option('posts_per_page');
    $offset = $posts_per_page * ($clean['page'] - 1) ;

    /**
     * Insights query args
     */
    $args = array(
        'post_type'   => array('post'),
        'post_status' => array('publish'),
        'paged'       => $clean['page'],
        'number'      => get_option('posts_per_page'),
        'offset'      => $offset,
        'count_total' => true
    );

    // country
    if ( isset($clean['country']) && $clean['country'] !== "") {
        $args['tax_query'][] = array(
            array (
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => $clean['country'],
                'compare'  => 'IN'
            ),
        );
    }

    // add solutions
    if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
        $args['tax_query']['relation'] = 'AND';
        $args['tax_query'][] = array(
            array (
                'taxonomy' => 'dfdl_solutions',
                'field' => 'id',
                'terms' => $clean['solutions'],
                'compare' => 'IN'
            ),
        );
    }

    // add categories
    if ( isset($clean['categories']) && count($clean['categories']) > 0 ) {

        $args['tax_query']['relation'] = 'AND';
        $args['tax_query'][] = array(
            array (
                'taxonomy'   => 'category',
                'field' => 'id',
                'terms' => $clean['categories']
            ),
        );

    } elseif ( isset($_POST['iContentHub']) && "content_hub" === $_POST['iContentHub'] ) {

        // check for content hub
        $args['tax_query'][] = array(
            array (
                'taxonomy'   => 'category',
                'field' => 'id',
                'terms' => dfdl_insights_content_hub_ids()
            ),
        );

    } else {


        if ( isset($clean['term']) ) {

            $args['cat'] = array($clean['term']);

        } else {

            // default solutions
            if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
                $args['tax_query']['relation'] = 'AND';
            }
            $args['tax_query'][] = array(
                array (
                    'taxonomy'   => 'category',
                    'field' => 'id',
                    'terms' => dfdl_insights_categories_ids()
                ),
            );
        }

    }

    // add years
    if ( isset($clean['years']) && count($clean['years']) > 0 ) {

        asort($clean['years']);
        $sort_years = array();
        foreach ( $clean['years'] as $y ) {
            $sort_years[] = array("year" => $y);
        }
        $args['date_query'] = array();
        $args['date_query']['relation'] = 'OR';
        $args['date_query'][] = $sort_years;

    } else {

        /**
         * Date query: limit results to last 2 years
         */
        $limit = array(
            'year'  => date("Y") - 2,
            'month' => date("m"),
            'day'   => date("d")
        );
        $args['date_query'] = array(
            array(
                'after' => $limit
            )
        );

    }

    // limit members in admin
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        $args['number'] = 4;
    }

    /**
     * User query
     */
    $insights  = new WP_Query($args);
    $output    = array();

    if ( $insights->have_posts() ) {

        //$search_string = "<span>Showing " . $insights->post_count . " of " . $insights->found_posts . " posts.</span> ";

        foreach ( $insights->posts as $post ) {


            if ( isset($clean['categories']) && count($clean['categories']) === 1 ) {
                $term = get_term_by("ID", $clean['categories'][0], 'category');
            } elseif( isset($clean['term']) ) {
                $term = get_term_by("ID", $clean['term'], 'category');
            } elseif( "content_hub" === $_POST['iContentHub'] ) {
                $term = get_content_hub_cat($post->ID);
            } else {
                $term = dfdl_post_solution($post->ID, array("return" => "term"));
            }
            

            set_query_var("story", $post);
            set_query_var("term", $term);
            
            if ( "events" === $term->slug ) {
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
            }
            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';

            ob_start();
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }
            $output[] = ob_get_clean();

            /*
            $term = dfdl_post_solution($p->ID, array("return" => "term"));
            $cats = dfdl_post_terms($p->ID);
            set_query_var("story", $p);
            set_query_var("term", $term);
            set_query_var("cats", $cats);

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';

            ob_start();
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }
            $output[] = ob_get_clean();
            */

        }
        
    }

    /**
    * Validate response
    */
    if ( count($output) > 0 ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['count']  = $insights->post_count;
        $response['found']  = $insights->found_posts;
        //$response['debug']  = $args;
        //$response['vars']   = $insights->query_vars;
        //$response['query']   = $insights->query;
        //$response['request']  = $insights->request;
        
    } else {
        $response['code']    = 400;
        $response['message'] = 'empty result set';
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

    global $wp_query;

    /**
     * Response 
     */
    $response = array();

    /**
    * Validate nonce
    */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_insights" )) {
        $response["status"]	= "Failed security check. Please reload the page and try again.";
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
    * Show text value of filters
    */
    $filters = array();

    /**
    * Validate $_POST vars
    */
    if ( isset($_POST['iSolutions']) && "" !== $_POST['iSolutions'] && "undefined" !== $_POST['iSolutions'] ) {
        $post_solutions  = explode(',', $_POST['iSolutions']);
        foreach( $post_solutions as $p ) {
            $term = get_term_by("id", $p, 'dfdl_solutions');
            if ( ! is_wp_error($term) ) {
                $clean['solutions'][] = $term->term_id;
                $filters[] = $term->name;
            }
        }
    }
    if ( isset($_POST['iCategories']) && "" !== $_POST['iCategories'] && "undefined" !== $_POST['iCategories'] ) {
        $post_categories  = explode(',', $_POST['iCategories']);
        if ( ! empty($post_categories) ) {
            foreach( $post_categories as $p ) {
                $term = get_term_by("id", $p, 'category');
                if ( ! is_wp_error($term) ) {
                    $clean['categories'][] = $term->term_id;
                    $filters[] = $term->name;
                }
            }
        }
    } elseif ( isset($_POST['iSection']) && "" !== $_POST['iSection'] && "undefined" !== $_POST['iSection'] ) {
        $term = get_term_by("slug", sanitize_text_field($_POST['iSection']), 'category');
        if ( ! is_wp_error($term) ) {
            $clean['categories'][] = $term->term_id;
            $clean['section'] = $term->term_id;
            $filters[] = $term->name;
        }
    } 
    if ( isset($_POST['iYears']) && "" !== $_POST['iYears'] && "undefined" !== $_POST['iYears'] ) {
        $post_years  = explode(',', $_POST['iYears']);
        foreach( $post_years as $p ) {
            $clean['years'][] = intval($p);
            $filters[] = intval($p);
        }
    }
    if ( isset($_POST['iCountry']) && "" !== $_POST['iCountry'] && "undefined" !== $_POST['iCountry'] ) {
        $clean['country'] = sanitize_text_field($_POST['iCountry']);
    }
    if ( isset($_POST['iTerm']) && "" !== $_POST['iTerm'] && "undefined" !== $_POST['iTerm'] ) {
        $clean['term'] = intval($_POST['iTerm']);
    }

    /**
     * Build query
     */
    $paged = ( isset($wp_query->query['page']) ) ? $wp_query->query['page'] : 1;

    $args                = array();
    $args['post_type']   = array('post');
    $args['post_status'] = array('publish'); 
    $args['paged']       = $paged; 
    $args['number']      = 24; 
    $args['count_total'] = true;

    // country
    if ( isset($clean['country']) && $clean['country'] !== "") {
        $args['tax_query'][] = array(
            array (
                'taxonomy' => 'dfdl_countries',
                'field'    => 'slug',
                'terms'    => $clean['country'],
                'compare'  => 'IN'
            ),
        );
    }

    // add solutions
    if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
        $args['tax_query'][] = array(
            array (
                'taxonomy' => 'dfdl_solutions',
                'field' => 'id',
                'terms' => $clean['solutions'],
                'compare' => 'IN'
            ),
        );
    }

    // add categories
    if ( isset($clean['categories']) && count($clean['categories']) > 0 ) {

        if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
            $args['tax_query']['relation'] = 'AND';
        }
        $args['tax_query'][] = array(
            array (
                'taxonomy'   => 'category',
                'field' => 'id',
                'terms' => $clean['categories']
            ),
        );

    } elseif ( isset($_POST['iContentHub']) && "content_hub" === $_POST['iContentHub'] ) {
        // check for content hub
        $args['tax_query'][] = array(
            array (
                'taxonomy'   => 'category',
                'field' => 'id',
                'terms' => dfdl_insights_content_hub_ids()
            ),
        );

    } else {

        /*
        if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
            $args['tax_query']['relation'] = 'AND';
        }
        $args['tax_query'][] = array(
            array (
                'taxonomy'   => 'category',
                'field' => 'id',
                'terms' => dfdl_insights_categories_ids()
            ),
        );*/

        if ( isset($clean['term']) ) {

            $args['cat'] = array($clean['term']);

        } else {

            // default solutions
            if ( isset($clean['solutions']) && count($clean['solutions']) > 0 ) {
                $args['tax_query']['relation'] = 'AND';
            }
            $args['tax_query'][] = array(
                array (
                    'taxonomy'   => 'category',
                    'field' => 'id',
                    'terms' => dfdl_insights_categories_ids()
                ),
            );
        }
    }

    // add years
    if ( isset($clean['years']) && count($clean['years']) > 0 ) {

        asort($clean['years']);
        $sort_years = array();
        foreach ( $clean['years'] as $y ) {
            $sort_years[] = array("year" => $y);
        }
        $args['date_query'] = array();
        $args['date_query']['relation'] = 'OR';
        $args['date_query'][] = $sort_years;

    } else {

        /**
         * Date query: limit results to last 2 years
         */
        $limit = array(
            'year'  => date("Y") - 2,
            'month' => date("m"),
            'day'   => date("d")
        );
        $args['date_query'] = array(
            array(
                'after' => $limit
            )
        );

    }

    // limit members in admin
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        $args['number'] = 4;
    }

    /**
     * User query
     */
    $insights  = new WP_Query($args);
    
    if ( $insights->have_posts() ) {

        $search_string = "<span>Showing <span id='search-count'>" . $insights->post_count . "</span> of <span class='search-found'>" . $insights->found_posts . "</span> posts.</span> ";
        $filters = implode(", ", $filters);
        if ( ! empty($filters) ) {
            $search_string .= "<span>Filters: " . $filters . "</span>";
        }
        
        ob_start();
        foreach ( $insights->posts as $p ) {

            if ( isset($clean['categories']) && count($clean['categories']) === 1  ) {
                $term = get_term_by("ID", $clean['categories'][0], 'category');
            } elseif ( isset($clean['term']) ) {
                $term = get_term_by("ID", $clean['term'], 'category');
            } else {
                $term = dfdl_post_solution($p->ID, array("return" => "term"));
            }
            $cats = dfdl_post_terms($p->ID);

            // $content_hub_cat = get_content_hub_cat($p->ID);
            
            set_query_var("story", $p);
            set_query_var("term", $term);
            set_query_var("cats", $cats);
            set_query_var("search_string", $search_string);

            $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';
            if ( file_exists($file) ) {
                get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
            } else {
                get_template_part( 'includes/template-parts/content/insights', 'news-card' );
            }

        }
        $cards = ob_get_clean();
        
        /**
         * Insert cards into template
         */
        //ob_start();
            //get_template_part( 'includes/template-parts/content/insights', 'search-results' );
        //$template = ob_get_clean();
        //$output   = str_replace("{posts}", $cards, $template);

        $output = '<section class="filter-results">';
        $output .= '<p>' . $search_string . '</p>';
        $output .= '<div id="insights-posts" class="posts">' . $cards . '</div>';
        $output .= '</section>';

        if ( $insights->found_posts  > $insights->post_count ) {
            $output .= '<div class="see-more"><button id="insights-all-see-more" data-source="filter" class="button green ghost see-more">See More<span></span></button></div>';
        }

    }

    /**
    * Validate response
    */
    if ( isset($output) && "" !== $output ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['found']  = $insights->found_posts;
        $response['count']  = $insights->post_count;
        //$response['vars']   = $insights->query_vars;
        //$response['query']  = $insights->query;
        //$response['request']  = $insights->request;
        //$response['debug']  = $args;
    } else {
        $response['code']    = 400;
        $response['message'] = 'empty result set';
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
    $posts_per_page = get_option('posts_per_page');
    $args = array(
        'number'   =>  $posts_per_page ,
        'role__in' => array('dfdl_member'),
        'count_total'            => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );

    if ( "a-z" == $clean_sort ) {
        $args['orderby']     = array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' );
    } else {
        $args['orderby']     = array( 'dfdl_rank' => 'ASC', 'last_name' => 'DESC' );
    }

    /**
     * Sort keys
     */
    $args['meta_query'] = array(
        'relation' => 'AND',
        'dfdl_rank' => array(
            'key'   => '_dfdl_member_rank',
            'compare' => 'EXISTS'
        ),
        'last_name' => array(
            'key'   => 'last_name',
            'compare' => 'EXISTS'
        ),
    );

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
    if ( "undefined" !== $clean_country && "" !== $clean_country) {
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
    $users = new WP_User_Query($args);
    $found = $users->get_total();

    if ( ! empty( $users->get_results() ) ) {
        ob_start();
        foreach ( $users->get_results() as $user ) {
            set_query_var("user", $user);
            get_template_part( 'includes/template-parts/content/member' );
        }
        $slides = ob_get_clean();
    }

    ob_start();
        get_template_part( 'includes/template-parts/content/swiper', 'team-callout' );
    $template = ob_get_clean();
    $temp = str_replace("{posts}", $slides, $template);
    $output = '<div id="team-grid-swiper">' . $temp ; 

    if ( $found  > $posts_per_page ) {
        $output .= '<div class="see-more"><button id="teams-all-see-more" class="button green ghost see-more">See More<span></span></button></div>';
    }
    
    $output .= '</div>';

    /**
    * Validate response
    */
    if ( isset($output) && "" !== $output ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['count']  = $users->results;
        $response['found']  = $found ;
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
 * Teams more ajax
 */
add_action('wp_ajax_teams_more', 'dfdl_ajax_teams_more');
add_action('wp_ajax_nopriv_teams_more', 'dfdl_ajax_teams_more');
function dfdl_ajax_teams_more(): void {

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

    /**
    * Response 
    */
    $response = array();

    /**
    * Validate nonce
    */
    if ( ! wp_verify_nonce( $_POST['nonce'], "dfdl_teams_see_more" )) {
        $response["status"]	= "Failed security check. Please reload the page and try again.";
        echo json_encode($response);
        exit;
    }

    $clean = array();

    /**
    * Valid input values
    */
    $valid = array();
    $valid['solutions'] = dfdl_get_solutions('slug');

    /**
    * Validate $_POST vars
    */
    $post_solutions  = explode(',', $_POST['solutions']);
    foreach( $post_solutions as $p ) {
        if ( in_array($p, $valid['solutions'])) {
            $term = get_term_by("slug", $p, 'dfdl_solutions');
            $clean['solutions'][] = $term->term_id;
        }
    }
    if ( isset($_POST['page']) && "" !== $_POST['page'] && "undefined" !== $_POST['page'] ) {
        $clean['page'] = intval($_POST['page']);
    } else {
        $clean['page'] = 1;
    }  
    $posts_per_page = get_option('posts_per_page');
    $offset = $posts_per_page * ($clean['page'] - 1) ;

    /**
     * Team Members query args
     */
    $args = array(
        'number'      => get_option('posts_per_page'),
        'offset'      => $offset,
        'paged'       => $clean['page'],
        'count_total' => true,
        'role'        => array('dfdl_member'),
        'no_found_rows'          => false,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );
   
    /**
     * Sort keys
     */
    $clean['sort']   = sanitize_text_field($_POST['sort']);
    if ( "a-z" == $clean['sort'] ) {
        $args['orderby']     = array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' );
    } else {
        $args['orderby']     = array( 'dfdl_rank' => 'ASC', 'last_name' => 'DESC' );
    }
    $args['meta_query'] = array(
        'relation' => 'AND',
        'dfdl_rank' => array(
            'key'   => '_dfdl_member_rank',
            'compare' => 'EXISTS'
        ),
        'last_name' => array(
            'key'   => 'last_name',
            'compare' => 'EXISTS'
        ),
    );
    
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
    $clean['country'] = sanitize_text_field($_POST['country']);
    if ( "undefined" !== $clean['country'] && "" !== $clean['country'] ) {
        $term = get_term_by('slug', $clean['country'], 'dfdl_countries');
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
    $users = new WP_User_Query($args);

    /*
    if ( ! empty( $users->get_results() ) ) {
        ob_start();
        foreach ( $users->get_results() as $user ) {
            set_query_var("user", $user);
            get_template_part( 'includes/template-parts/content/member' );
        }
        $slides = ob_get_clean();
    } */
    
    // return an array of user html
    $output = array();
    if ( ! empty( $users->get_results() )) {
        $found = $users->get_total();
        foreach( $users->get_results() as $user ) {
            ob_start();
                set_query_var("user", $user);
                get_template_part( 'includes/template-parts/content/member' );
            $output[] = ob_get_clean();
        }
   }

    /**
    * Validate response
    */
    if ( count($output) > 0 ) {
        $response['code']   = 200;
        $response['status'] = 'success';
        $response['html']   = $output;
        $response['count']  = count($output);
        $response['found']  = $found;
        //$response['debug']  = count($args);
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
        case "podcasts_solutions":
            $options = dfdl_get_solutions_tax();
            break;
        case "award_years":
            $options = dfdl_get_award_years();
            break;
        case "insights_years":
        case "podcasts_years":
            $options = dfdl_get_insights_years();
            break;
        case "teams_sort":
            $options = dfdl_get_teams_sort();
            break;
        case "insights_categories":
            $options = dfdl_get_insights_categories_sort();
            break;
        case "insights_events":
            $options = dfdl_get_insights_events_sort();
            break;
        default:
            // no default
    }

    $select   = array();
    if ( "teams_sort" === $filter ) {
        $select[] = '<select style="width: 100%" id="' . $filter . '" name="' . $filter . '">';
    } else {
        $select[] = '<select style="width: 100%" multiple="multiple" id="' . $filter . '" name="' . $filter . '">';
    }
    if ( isset($options) ) {
        foreach( $options as $option ) {
            if ( "teams_sort" === $filter && 1 === $option->term_id) {
                $selected = 'selected="selected"';
            } else {
                $selected = "";
            }
            if( "insights_categories" === $filter || "insights_solutions" === $filter || "insights_events" === $filter ) {
                $select[] = '<option ' . $selected . ' data-id="' . $option->term_id . '" name="' . $option->slug. '" value="' . $option->term_id . '">' .  $option->name . '</option>'; 
            } else {
                $select[] = '<option ' . $selected . ' data-id="' . $option->term_id . '" name="' . $option->slug. '" value="' . $option->slug . '">' .  $option->name . '</option>'; 
            }
            
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

    if ( "insights" === $section ) {
        $dfdl_sections = dfdl_get_section();
        /**
         * All button
         */
        if ( "insights" === end($dfdl_sections) ) {
            $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . get_home_url('', 'insights/'). '">All</a></li>' ;
        } elseif ( "news" === end($dfdl_sections) ) {
            $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . get_home_url('', 'insights/news/'). '">All</a></li>' ;
        } elseif ( "content-hub" === end($dfdl_sections) ) {
            $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . get_home_url('', 'insights/content-hub/'). '">All</a></li>' ;
        } else {
            $nav[] = '<li class="swiper-slide"><a  href="' . get_home_url('', 'insights/'). '">All</a></li>' ;
        }
    }

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
                        $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    } else {
                        $nav[] = '<li class="swiper-slide"><a href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
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
                            $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        } else {
                            $nav[] = '<li class="swiper-slide"><a href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        }

                    } else {

                        /**
                         * if $pieces[1] is a category, include country
                         * 
                         * new url: /insights/[category]/[country]/
                         */
                        if ( in_array(strtolower($page->post_name), $pieces)  ) {
                            $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        } else {
                            $nav[] = '<li class="swiper-slide"><a href="' . $home_url . '/' . $pieces[0] . '/' . $pieces[1] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                        }

                    }

                } else {

                    /**
                     * url: /insights/
                     */
                    if ( in_array(strtolower($page->post_name), $pieces)  ) {
                        $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
                    } else {
                        $nav[] = '<li class="swiper-slide"><a href="' . $home_url . '/' . $pieces[0] . '/' . $page->post_name . '/">' . $page->post_title . '</a></li>' ;
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
                    $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
                } else {
                    $nav[] = '<li class="swiper-slide"><a href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
                }

            }
            
        }
    }

    /**
     * Add filter button
     */
    if ( "contact-us" !== $section ) {
        /**
         * Add search icon to teams
         */
        if ( "teams" === $section ) {
            $nav[] = '<li class="member-search swiper-slide"><img id="search-toggle" src="'. get_stylesheet_directory_uri() . '/assets/media/icon-search.svg"></li>';
            // $nav[] = '<li class="swiper-slide member-search-stage"><div class="member-search">'. get_search_form( array( 'echo' => false ) ) . '</div></li><li class="filter-button swiper-slide"><button id="filters-toggle" class="button filter ' . $section . '">Filter</button></li>';
        }
        $nav[] = '<li class="filter-button swiper-slide"><button id="filters-toggle" class="button filter ' . $section . '">Filter</button></li>';
    }

    

    /**
     * Prepare html output
     */
    $class  = is_admin() ? "admin" : "" ;
    $output = array();

    $output[] = '<nav id="country-subnav" class="country-subnav-stage"><div class="subnav-swiper"><ul class="' . $class . ' ' . $section . '-country-nav country-nav swiper-wrapper">';
    
    // mobile filter button
    $output[] = '<li class="swiper-slide mobile-filter-button" data-id="' . $section . '"><button id="mobile-filters-toggle" class="button mobile-filter insights-filter"></button></li>';

    if ( ( "all" === end($pieces) || "teams" === end($pieces) ) && count($pieces) < 3) {

        $output[] = '<li class="swiper-slide a"><a class="current-menu-item" href="' . $home_url . '/' . $section . '/all/">All</a></li>';

    } else {

        if( "awards" === $section ) {
            if ( count($pieces) == 1 ) {
                $output[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/' . $section . '/">All</a></li>';
            } else {
                $output[] = '<li class="swiper-slide"><a href="' . $home_url . '/' . $section . '/">All</a></li>';
            }
        } elseif ( "contact-us" === end($pieces) ) {
            if ( count($pieces) == 1 ) {
                $output[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . $home_url . '/contact-us/">Regional</a></li>';
            } else {
                $output[] = '<li class="swiper-slide"><a href="' . $home_url . '/contact-us/">Regional</a></li>';
            }
        } else {

            if ( "insights" !== $section ) {
                $output[] = '<li  class="swiper-slide"><a href="' . $home_url . '/' . $section . '/all/">All</a></li>';
            }
            
        }
    }

    $output[] = implode("", $nav);
    $output[] = '</ul></div><!-- /swiper wrapper --></nav>';

    /**
     * Section Filters & Sorts
     */

    $filters = array();

    // Swiper.js
    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js' );
    wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');

    // Enqueue filter scripts
    if ( "teams" === $section || "awards" === $section || "insights" === $section ) {
        wp_enqueue_style('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.css', null, null, 'all');
		wp_enqueue_script('select2', get_stylesheet_directory_uri() . '/assets/js/select2/select2.min.js', array("jquery"), null, true );
    }
    // Teams filter
    if ( "teams" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "teams");
            echo '<div id="search-stage" class="search-members filters-stage silo"><div class="stage">' . get_search_form( array( 'echo' => false ) ) . '</div></div>';
        $filters[] = ob_get_clean();
    }
    // Awards filter
    if ( "awards" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "awards");
        $filters[] = ob_get_clean();
    }
    // Insights filter
    if ( "insights" === $section ) {
        ob_start();
        if ( count($dfdl_sections) >= 3 ) {
            $pageslug = sanitize_text_field($dfdl_sections[1]);
            $filter_file = get_stylesheet_directory() . "/includes/template-parts/filters/filter-" .  $pageslug . ".php";
        } else {
            $pageslug = sanitize_text_field(end($dfdl_sections));
            $filter_file = get_stylesheet_directory() . "/includes/template-parts/filters/filter-" . $pageslug . ".php";
        }
        if ( file_exists($filter_file) ) {
            $maybe_section_term = get_term_by("slug", $pageslug, 'category');
            if ( false !== $maybe_section_term && ! is_wp_error($maybe_section_term) ) {
                $omit = array("news");
                if ( ! in_array($pageslug, $omit) ) {
                    echo '<input type="hidden" name="insights_section[]" id="insights_section" value="' . $pageslug . '">';
                }
            }
            get_template_part("includes/template-parts/filters/filter", $pageslug );
        } else {
            get_template_part("includes/template-parts/filters/filter", "insights");
        }
        $filters[] = ob_get_clean();
    }



    $output[] = implode($filters);

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
 * Mobile body class
 */
add_filter( 'body_class', 'dfdl_mobile_body_class' );
function dfdl_mobile_body_class( $classes ) {
    if ( wp_is_mobile() ) {
        $classes[] = 'is-mobile';
    }
    return $classes;
}

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


add_action( 'wp_enqueueu_scripts', function() {
    wp_dequeue_style( 'relevanssi-live-search' );
},99 );


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

add_action( 'login_enqueue_scripts', 'dfdl_login_style' );
function dfdl_login_style() {
    wp_enqueue_style( 'dfdl-login', get_stylesheet_directory_uri() . '/login.css' );
}