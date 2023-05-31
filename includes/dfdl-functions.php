<?php

/**
 * Insights filter pagination links
 */
function dfdl_ajax_pagination_link( int $pagenum, string $url, array $post ): string {
    $solutions  = ( isset($post['iSolutions']) ) ? implode(",",$post['solutions']) : "" ;
    $categories = ( isset($post['iCategories']) ) ? implode(",",$post['categories']) : "" ;
    $section    = ( isset($post['iSection']) ) ? implode(",",$post['section']) : "" ;
    $years      = ( isset($post['iYears']) ) ? implode(",",$post['years']) : "" ;
    return $url . "?iSolutions=" . $solutions . "&iCategories=" . $categories. "&iSection=" . $section . "iYears=" . $years . "&page=" . intval($pagenum+1);
}

/**
 * Insights posts
 */
function dfdl_insights() {

    $debug = false;

    /**
     * Exclude Webinars and Podcasts categories: 717, 839
     */
    $query_args = array(
        'post_type'      => array('post'),
        'post_status'    => array("publish"),
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'cat'            => array(-717,-839),
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => false,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
   );
   
   if ( is_admin() ) {
        $query_args['posts_per_page'] = 4;
    }
   $sections = dfdl_get_section();

    /**
     * Add solution/country query data
     */
    if ( count($sections) > 0 ) {

        $maybe_country = end($sections);

        if ( isset($sections) && "solutions" === $sections[0] && isset($sections[1]) ) {

            /**
             * Solutions
             */
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'dfdl_solutions',
                    'field'    => 'slug',
                    'terms'    => $sections[1],
                )
            );

        } else if( isset($maybe_country) && in_array( $maybe_country, constant('DFDL_COUNTRIES') ) ) {
           
            /**
             * Country
             */
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'dfdl_countries',
                    'field'    => 'slug',
                    'terms'    => $maybe_country,
                )
            );

        } else {
        
            /**
             * Default to news category -- is that right?
             */
            $query_args['tax_query'] = array(
                array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => 'news',
                )
            );
        }

    } else {
        
        /**
         * Default to news category -- is that right?
         */
        $query_args['tax_query'] = array(
            array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'news',
            )
        );
    }

    /**
     * Limit results to 2 years
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

    //echo "<pre>";
    //var_dump($query_args);
    //echo "</pre>";

   $insights = new WP_Query( $query_args );

    /**
     * Debug info
     */
    if ( true === $debug && current_user_can('manage_options') ) {
        echo "<h4>Query Args</h4>";
        var_dump( $query_args );
        echo "<h4>Results Count</h4>";
        var_dump( $insights->found_posts );
        var_dump( count($insights->posts) );
    }

    /**
     * In no results from out main query fetch recent news
     */
    if ( $insights->have_posts() ) {
        return $insights;
    } else {
        return dfdl_recent_news();
    }

}

function dfdl_recent_news() {

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => array("publish"),
        'posts_per_page' => 8,
        'cat'            => 667, // news category
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );

    /**
     * Limit results to 2 years
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

    return new WP_Query( $query_args );

}




/** 
 * DFDL Get Awards.
 *
 * Return html for awards, possibly sorted
 * 
 * @country    string  countries
 * @solutions  array   dfdl solutions
 * @bodies     array   award bodies
 * @years      array   years
 * 
 * @return string
*/
function dfdl_get_awards( array $args=array() ): string {

    $args['country']   = isset($args['country']) ? $args['country'] : "" ;
    $args['solutions'] = isset($args['solutions']) ? $args['solutions'] : "";
    $args['bodies']    = isset($args['bodies']) ? $args['bodies'] : dfdl_get_award_bodies('slug') ;
    $args['years']     = isset($args['years']) ? $args['years'] : dfdl_get_award_years('slug') ;

    rsort($args['years']);
    sort($args['bodies']);

    $types  = array("award", "ranking");
    $output = array() ;

    foreach ($args['years'] as $year ) {
        foreach ($args['bodies'] as $body ) {
            $header_added = false;
            foreach ( $types as $type ) {

                $body = sanitize_text_field($body);
                $year = intval($year);

                $query_args = array(
                    'post_type'              => 'dfdl_awards',
                    'post_status'            => 'publish',
                    'posts_per_page'         => 99,
                    'orderby'                => array( 'post_title' => 'ASC' ),
                    'no_found_rows'          => true,
                    'ignore_sticky_posts'    => true,
                    'update_post_meta_cache' => false, 
                    'update_post_term_cache' => false,
                    'meta_query' => array(
                        array(
                            'key'     => 'type',
                            'value'   =>  $type,
                            'compare' => 'LIKE',
                        ),
                    )
                );

                if ( "" !== $args['country'] && in_array( $args['country'], constant('DFDL_COUNTRIES')) ) {

                    $query_args['tax_query'] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'dfdl_award_bodies',
                            'field' => 'slug',
                            'terms' => array($body)
                        ),
                        array(
                            'taxonomy' => 'dfdl_award_years',
                            'field' => 'slug',
                            'terms' => array($year),
                        ),
                        array(
                            'taxonomy' => 'dfdl_countries',
                            'field' => 'slug',
                            'terms' => array($args['country'])
                        ),
                    );

                } else {

                    $query_args['tax_query'] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'dfdl_award_bodies',
                            'field' => 'slug',
                            'terms' => array($body) 
                        ),
                        array(
                            'taxonomy' => 'dfdl_award_years',
                            'field' => 'slug',
                            'terms' => array($year),
                        ),
                    );

                }

                if ( "" !== $args['solutions'] ) {
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'dfdl_solutions',
                        'field' => 'slug',
                        'terms' => $args['solutions'] 
                    );
                }

                $awards = new WP_Query( $query_args );

                if ( count($awards->posts) > 0 ) {
                    if ( false == $header_added ) {
                        $output[] = '<div class="award-entry">';
                        $output[] = "<h4>" . intval($year) . " " . str_replace("-", " ", esc_attr($body)) . "</h4>";
                        $output[] = "<ul>";
                        $header_added = true;
                        $div_open = true;
                    }
                    foreach ( $awards->posts as $p ) {

                        if ( "award" === $type ) {
                            $output[] = '<li class="award">';
                        } else {
                            $output[] = "<li>";
                        }
                        $pieces = explode("–", $p->post_title);
                        $title = '<div class="entry">';
                                                
                        if ( count($pieces) > 1 ) {
                            
                            if ( isset($pieces[1]) ) {
                                $title .= '<span class="solution">' . $pieces[1] . '</span>';
                            }
                            if ( isset($pieces[2]) ) {
                                $title .= '<span class="country">' . $pieces[2] . '</span>';
                            }
                            if ( isset($pieces[0]) ) {
                                $title .= '<span class="rank">' . $pieces[0] . '</span>';
                            }
                            
    
                        } else {
                            $title .= '<span class="award-title">' . array_shift($pieces) . '</span>';
                        }
                        $output[] = $title;
                        $output[] = "</div></li>";


                        /*
                        // previous formatting
                        if ( "award" === $type ) {
                            $output[] = '<li class="award">';
                        } else {
                            $output[] = "<li>";
                        }
                        $pieces = explode("–", $p->post_title);
                        $title = '<div class="entry">';
                                                
                        if ( count($pieces) > 1 ) {
                            $title .= '<span>' . array_shift($pieces) . ' –</span>';
                            $title .= '<span>' . implode("– ", $pieces) . '</span>';
                        } else {
                            $title .= array_shift($pieces);
                        }
                        $output[] = $title;
                        $output[] = "</div></li>";
                        */
                        
                    }
                }
            }
            if ( isset($div_open) && true === $div_open) {
                $output[] = "</ul></div>";
                $div_open = false;
            }
        }
    }
    
    return implode($output);

}

/** 
 * DFDL Teams Sort.
 * 
 * @return array of psuedo-terms
*/
function dfdl_get_teams_sort(): array {
    
    $output = array();

    $class = new stdClass;
    $class->term_id = 1;
    $class->slug   = "a-z";
    $class->name   = "A–Z";

    $output[] = $class;
    
    $class = new stdClass;
    $class->term_id = 2;
    $class->slug    = "z-a";
    $class->name    = "Z–A";

    $output[] = $class;

    return $output;
}

/** 
 * DFDL Award Types.
 * 
 * ! in use??? might be hardcoded and this is not needed.
 * Only two options: award, ranking
 * 
 * @return array of terms
*/
function dfdl_get_award_types(): array {
    return get_terms(array(
        'taxonomy' => 'dfdl_award_types',
        'hide_empty' => false,
        'orderby'  => 'name',
        'order'    => 'ASC'
    ));
}

/** 
 * DFDL Award Bodies.
 *
 * @return array of terms
*/
function dfdl_get_award_bodies( string $return=""): array {
    $terms = get_terms(array(
        'taxonomy' => 'dfdl_award_bodies',
        'hide_empty' => false,
        'orderby'  => 'name',
        'order'    => 'ASC'
    ));
    if ( "slug" === $return ) {
        $slugs = array();
        foreach( $terms as $t ) {
            $slugs[] = $t->slug;
        }
        return $slugs;
    }
    return $terms;
}

/** 
 * DFDL Insights Years.
 *
 * @return array of terms
 */
function dfdl_get_insights_years() {

    global $wp;

    $current_year = intval(date("Y"));
    $oldest_year  = $current_year - 2;
    $options      = array();

    for ( $i = $current_year ; $i >= $oldest_year ; $i-- ) {
        $obj = (object)[];
        $obj->term_id = $i;
        $obj->name = $i;
        $obj->slug = $i;
        $options[] = $obj;
    }

    return $options;

}

/** 
 * DFDL News Types.
 * 
 * Sub-categories from News and Content Hub 
 * 
 * @return array of terms
 */
function dfdl_get_insights_categories_sort(): array {
    $return  = array();
    // $term_id = array( 842, 667);
    $term_id = array( 47, 667, 668, 717, 744, 839);
    foreach ( $term_id as $id ) {
         $t = get_term_by("ID", $id, 'category') ;
         $return[] = $t;
        /*
        $terms = get_terms(array(
            'taxonomy'   => 'category',
            'child_of'    => $id,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC'
        ));
        foreach ( $terms as $t ) {
            $return[] = $t;
        }
        */
    }
    usort($return,function($first,$second){
        return strtolower($first->name) <=> strtolower($second->name);
    });
    return $return;
}

/** 
 * Insights Events Sort.
 * 
 * Event sub-categories
 * 
 * @return array of terms
 */
function dfdl_get_insights_events_sort(): array {
    $return   = array();
    $term     = get_term_by("slug", "events", "category");
    $children = get_term_children($term->term_id, 'category'); 
    foreach ( $children as $c ) {
        $child = get_term_by("id", $c, "category");
        $return[] = $child;
    }
    //usort($return,function($first,$second){
        //return strtolower($first->name) <=> strtolower($second->name);
    //});
    return $return;
}


/**
 * Insights Categories
 * 
 * array of cats & subcats included in Insights
 */
function dfdl_insights_categories_ids():array {
    /**
     * Content Hub = 842
     * News        = 667
     * Events      = 668
     * Legal & Tax = 47
     */
    $term_ids = array( 842, 667, 668, 47 );
    $children = array();
    foreach ( $term_ids as $id ) {
        $kids = get_term_children($id, 'category'); 
        foreach( $kids as $k ) {
            $children[] = $k;
        }
    }
    $cat_ids = array_merge($term_ids, $children);

    return $cat_ids;

}

/**
 * Content Hub Categories
 * 
 * array of cats & subcats included under Content Hub
 */
function dfdl_insights_content_hub_ids():array {
    /**
     * Content Hub = 842
     */
    $term_ids = array(842);
    $children = array();
    foreach ( $term_ids as $id ) {
        $kids = get_term_children($id, 'category'); 
        foreach( $kids as $k ) {
            $children[] = $k;
        }
    }
    $cat_ids = array_merge($term_ids, $children);
    return $cat_ids;
}

/** 
 * DFDL Award Years.
 *
 * @return array of terms
*/
/*
function dfdl_get_award_years( string $return="" ): array {

    $terms = get_terms(array(
        'taxonomy' => 'dfdl_award_years',
        'hide_empty' => false,
        'orderby'  => 'id',
        'order'    => 'DESC'
    ));

    $sections = dfdl_get_section();
    if ( is_array($sections) && "awards" === $sections[0] ) {
        $slugs = array();
        foreach( $terms as $t ) {
            $slugs[] = $t->slug;
        }
        return $slugs;
    }


    $year = date("Y");
    if ( "slug" === $return ) {
        return array($year);
    }

    return array();

    // return get_term_by("slug", $year, 'dfdl_award_years');
}
*/

function dfdl_get_award_years( string $return="" ): array {
    $terms = get_terms(array(
        'taxonomy' => 'dfdl_award_years',
        'hide_empty' => false,
        'orderby'  => 'id',
        'order'    => 'DESC'
    ));
    if ( "slug" === $return ) {
        $slugs = array();
        foreach( $terms as $t ) {
            $slugs[] = $t->slug;
        }
        return $slugs;
    }
    return $terms;
}


/*
* DFDL Solutions.
*
* @return array of IDs
*/
function dfdl_get_solutions( string $return="" ): array {

    $solutions = get_page_by_path( 'solutions' );
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_status'    => array('publish'),
        'post_parent'    => $solutions->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
        'fields'                 => 'ids'
    );
    $pages = new WP_Query( $args );
    if ( "slug" === $return ) {
        $slugs = array();
        foreach( $pages->posts as $p ) {
            $slugs[] = get_post_field( 'post_name', $p );
        }
        return $slugs;
    }
    return $pages->posts;
}

/*
* DFDL Solutions Tax.
*
* Return dfdl_solutions taxonomy
* 
* @return array of IDs
*/
function dfdl_get_solutions_tax(): array {
    return get_terms(array(
        'taxonomy' => 'dfdl_solutions',
        'hide_empty' => false,
        'orderby'  => 'title',
        'order'    => 'ASC'
    ));
}

/*
* DFDL Desks.
*
* @return array of IDs
*/
function dfdl_get_desks(): array {
    $desks = get_page_by_path("desks");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 24,
        'post_parent'    => $desks->ID,
        'orderby'        => 'post_title',
        'order'          => 'ASC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false,
	    'update_post_term_cache' => false,
        'fields'                 => 'ids'
     );
    $pages = new WP_Query( $args );
    return $pages->posts;
}

/*
* DFDL Countries.
*
* @return array of country IDs
*/
function dfdl_get_countries( array $args = array() ): array {
    $locations = get_page_by_path("locations");
    $query_args = array(
        'post_type'      => 'page',
        'posts_per_page' => 24,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
        //'fields'                 => 'ids'
     );

    if ( empty($args) ) {

        $query_args['fields'] = 'ids';
        $pages = new WP_Query( $query_args );
        return $pages->posts;

    } elseif ( isset($args) && "slug" === $args['return'] ) {

        $pages = new WP_Query( $query_args );
        $slugs = array();
        foreach( $pages->posts as $post ) {
            $slugs[] = $post->post_name;
        }

        return $slugs;

    }
    
    return array();
    
}

/*
 * DFDL Countries Tax.
 *
 * Return dfdl_countries taxonomy
 * 
 * @return array of IDs
 */
function dfdl_get_countries_tax(): array {
    return get_terms(array(
        'taxonomy' => 'dfdl_countries',
        'hide_empty' => false,
        'orderby'  => 'title',
        'order'    => 'ASC'
    ));
}

/**
 * DFDL section
 * 
 * Return string
 * 
 */
function dfdl_section_class() {
    $sections = dfdl_get_section();
    if ( count($sections) > 0 ) {
        echo $sections[0];
    } elseif ( is_front_page() ) {
        echo "front-page";
    }
}

/**
 * DFDL section
 * 
 * Return an array with section info
 * 
 * 1. solutions
 * 2. locations/country/category
 */
function dfdl_get_section(): array {
    
    global $wp;

    if ( is_admin() ) 
        return array("admin");
    
    $return = array();
    $pieces = explode("/", $wp->request ) ; 

    if ( in_array("awards", $pieces) ) {
        $return[0] = "awards";
        if ( isset($pieces[1]) ) {
            $return[1] = $pieces[1];
        }
        return $return;
    }

    if ( in_array($pieces[0], DFDL_SECTONS) ) {
        $return[0] = $pieces[0];
        if ( isset($pieces[1]) ) {
            $return[1] = $pieces[1];
        }
        if ( isset($pieces[2]) ) {
            $return[2] = $pieces[2];
        }
    }

    return $return;

}

/**
 * Solutions "back" link.
 */
function dfdl_solutions_back_link(): string {
    // Try referer first 
    if (isset($_SERVER['HTTP_REFERER'])) {
        return $_SERVER['HTTP_REFERER'];
    }
    return get_home_url("", "/teams/all/");
}

/**
 * Insights "back" link.
 */
function dfdl_insights_back_link(): string {
    
    global $wp_query;

    // Try referer first 
    if (isset($_SERVER['HTTP_REFERER'])) {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Try DFDL Category
     */
    if ( isset($wp_query->query['dfdl_category']) ) {
        $link = get_term_link($wp_query->query['dfdl_category'], "category");
        /**
         * Keep url structure consistent
         * insights/[category]/[country]
         */ 
        $link = str_replace("content-hub/", "", $link);
        //if ( isset($wp_query->query['dfdl_country']) ) {
            //$link .= esc_attr($wp_query->query['dfdl_country']) . "/";
        //}
        return $link;
    }
    
    /**
     * Grab cat from sections
     */
    $sections = dfdl_get_section();

    if ( 2 === count($sections) ) {
        $link = get_home_url(null, "/insights/");
    } elseif ( 3 === count($sections) ) {
        $link = get_term_link($sections[1], "category");
    } else {
        $link = "#";
    }

   
    if ( is_wp_error($link ) ) {
        $link = get_home_url(null, "/insights/");
    } else {
        /**
         * Keep url structure consistent
         * insights/[category]/[country]
        */ 
        $link = str_replace("content-hub/", "", $link);
    }

    //if ( isset($wp_query->query['dfdl_country']) ) {
        //$link .= esc_attr($wp_query->query['dfdl_country']) . "/";
    //}

    return $link;

}

/**
 * DFDL Permalink
 * 
 * Strip "content-hub" from link urls
 */
function dfdl_get_permalink( int $post_id ): string {
    $link = get_permalink($post_id);
    /**
     * Keep url structure consistent
     * insights/[category]/[country]
     */ 
    $link = str_replace("content-hub/", "", $link);
    return $link;
}

/**
 * DFDL Content Hub Category.
 * 
 * dfdl_solution tax term for post
 * 
 */
function get_content_hub_cat( int $post_id ) {
    $solution = wp_get_post_terms($post_id, 'dfdl_solutions');
    if ( isset($solution[0]) ) {
        return $solution[0];
    }
    return dfdl_post_terms($post_id, array("return"=>"term"));
}

/**
 * DFDL Post Solution.
 * 
 * dfdl_solution tax term for post
 * 
 */
function dfdl_post_solution( int $post_id ) {
    $solution = wp_get_post_terms($post_id, 'dfdl_solutions');
    if ( isset($solution[0]) ) {
        return $solution[0];
    }
    return dfdl_post_terms($post_id, array("return"=>"term"));
}

/**
 * DFDL Post Terms.
 * 
 * Return term data for post category and sub-category
 * 
 * @args term, slug
 */
function dfdl_post_terms( int $post_id, array $args=array() ) {

    $hard_return = array("podcasts", "events", "videos", "news");

    $terms = wp_get_post_terms($post_id, 'category');

    $categories = array();

    if ( ! is_wp_error($terms) ) {
        foreach( $terms as $term ) {
            if (in_array($term->slug, $hard_return)) {
                $parent = $term;
            } else {
                $categories[] = $term;
            }
        }
    }

    if ( isset($parent) && isset($args['return']) && "term" === $args['return'] ) {
        return $parent;
    } elseif (isset($parent)) {
        return $parent->name;
    } elseif ( isset($categories) && isset($args['return']) && "term" === $args['return'] ) {
        return $categories[0];
    } elseif (isset($categories)) {
        return $categories[0]->name;
    } else {
        return array();
    }

}

/**
 * Content Hub Subcategory.
 * 
 * Return post cat name
 * @int post_id
 * 
 */
function dfdl_content_hub_category( int $post_id ) {
    $post_terms = wp_get_post_terms($post_id, 'category');
    $content = array("articles", "podcasts", "publications", "web-classes");
    if ( count($post_terms) === 1 ) {
        if (isset($post_terms[0]->parent) && $post_terms[0]->parent !== 0 ) {
            if ( in_array($post_terms[0]->slug, $content) ) {
                return $post_terms[0];
            }
            return get_term_by("ID", $post_terms[0]->parent , 'category');
        }
        return $post_terms[0];
    } else {
        foreach( $post_terms as $p ) {
            if ( in_array($p->slug, $content) ) {
                return $p;
            }
            if ( $p->parent ) {
                return get_term_by("ID", $p->parent , 'category');
            }
        }
    }
}

/**
 * Event Category.
 * 
 * Return events category name
 * @int post_id
 * 
 */
function dfdl_event_category($post_id): string {

    // events posts category id = 668
    $event_terms = get_term_children(668);
    $event_names = array();
    foreach( $event_terms as $et ) {
        $event_names[] = $et->name;
    }

    $post_terms = wp_get_post_terms($post_id, 'category', array( 'fields' => 'names'));
    foreach( $post_terms as $pt ) {
        if ( in_array($pt, $event_names) ) {
            return $pt;
        }
    }

    return "Events";

}

/**
 * Get Block Data
 * Get block data from page
 */
function get_block_data($post, $block_name = 'core/heading', $field_name = "" ): string{
	$content = "";
	if ( has_blocks( $post->post_content ) && !empty($field_name )) {
	    $blocks = parse_blocks( $post->post_content );
	    foreach($blocks as $block){
		    if ( $block['blockName'] === $block_name ) {
		    	if(isset($block["attrs"]["data"][$field_name ])){
                   $content = $block["attrs"]["data"][$field_name ];
		    	}
		    }	    	
	    }  
	}
	return $content;
}

/* 
 * short bio removed everywhere.
 * maybe not true. on contact page for now.
*/

function dfdl_one_liner( string $string ): string {
    return dfdl_short_bio($string);
}
function dfdl_short_bio( string $bio, int $length = 3 ): string {
    $posx = strposX($bio, ".", $length);
    if ( isset($posx) ) {
        return substr($bio, 0, $posx+1);
    }
    return "";
}


/**
 * Helper: Find nth occurrence of $needle
 * Used to insert author bio in tax & lelag updates
 */
function strposX($haystack, $needle, $number = 0) {
    if ( strpos($haystack, $needle) ) {
        return strpos($haystack, $needle,
            $number > 1 ?
            strposX($haystack, $needle, $number - 1) + strlen($needle) : 0
        );
    }
}