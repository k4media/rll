<?php

/** 
 * DFDL Get Awards.
 *
 * Return html for awards, possibly sorted
 * 
 * @country  string  countries
 * @bodies   array   award bodies
 * @years    array   years
 * 
 * @return string
*/
function dfdl_get_awards( array $args=array() ): string {

    $args['country']   = isset($args['country']) ? $args['country'] : "" ;
    $args['solutions'] = isset($args['solutions']) ? $args['solutions'] : "";
    $args['bodies']    = isset($args['bodies']) ? $args['bodies'] : dfdl_get_award_bodies('slug') ;
    $args['years']     = isset($args['years']) ? $args['years'] : dfdl_get_award_years('slug') ;

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
                    'orderby'                => 'post_title',
                    'order'                  => 'ASC',
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
                        $title = '<div class="entry"><span>' . array_shift($pieces);
                        if ( count($pieces) > 0 ) {
                            $title .= " –</span>";
                            $title .= '<span>' . implode("– ", $pieces) ;
                        }
                        if ( isset($p->post_content) ) {
                            $title .= "<br>" . $p->post_content;
                        } 
                        $title .= "</span>"; 
                        $output[] = $title;
                        $output[] = "</div></li>";
                        
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

/*
function __dfdl_award_args( array $args): array {

    $return = array(
        'post_type'      => 'dfdl_awards',
        'post_status'    => 'publish',
        'posts_per_page' => 99,
        'orderby' => 'post_title',
        'order'   => 'ASC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false
    );

    if ( empty($args['countries']) && empty($args['bodies']) && empty($args['years']) ) {

        $return['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'dfdl_award_bodies',
                'field' => 'id',
                'terms' => array( $body->term_id )
            ),
            array(
                'taxonomy' => 'dfdl_award_years',
                'field' => 'id',
                'terms' => array( $year->term_id ),
            ),
        );
    }


}

function __dfdl_get_awards( $args ): string {

    $defaults = array(
        'countries' => array(),
        'bodies'    => dfdl_get_award_bodies(),
        'years'     => dfdl_get_award_years(),
    );

    $args   = wp_parse_args( $args, $defaults );

    var_dump( $args );

    $types  = array("award", "ranking");
    $output = array() ;

    foreach ($args['years'] as $year ) {
        foreach ($args['bodies'] as $body ) {
            $header_added = false;
            foreach ($types as $type ) {
                $args = array(
                        'post_type'      => 'dfdl_awards',
                        'post_status'    => 'publish',
                        'posts_per_page' => 99,
                        'orderby' => 'post_title',
                        'order'   => 'ASC',
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

                // $args = args_add_award_bodies();

                if ( "" !== $country && in_array( $country, constant('DFDL_COUNTRIES')) ) {

                    $args['tax_query'] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'dfdl_award_bodies',
                            'field' => 'id',
                            'terms' => array( $body->term_id )
                        ),
                        array(
                            'taxonomy' => 'dfdl_award_years',
                            'field' => 'id',
                            'terms' => array( $year->term_id ),
                        ),
                        array(
                            'taxonomy' => 'dfdl_countries',
                            'field' => 'slug',
                            'terms' => array( $country )
                        ),
                    );

                } else {

                    $args['tax_query'] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'dfdl_award_bodies',
                            'field' => 'id',
                            'terms' => array( $body->term_id )
                        ),
                        array(
                            'taxonomy' => 'dfdl_award_years',
                            'field' => 'id',
                            'terms' => array( $year->term_id ),
                        ),
                    );
                }

                $awards = new WP_Query( $args );

                if ( count($awards->posts) > 0 ) {
                    if ( false == $header_added ) {
                        $output[] = '<div class="award-entry">';
                        $output[] = "<h4>" . $year->name . " " . $body->name . "</h4>";
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
                        $title = '<div class="entry"><span>' . array_shift($pieces);
                        if ( count($pieces) > 0 ) {
                            $title .= " –</span>";
                            $title .= '<span>' . implode("– ", $pieces) ;
                        }
                        if ( isset($p->post_content) ) {
                            $title .= "<br>" . $p->post_content;
                        } 
                        $title .= "</span>"; 
                        $output[] = $title;
                        $output[] = "</div></li>";
                        
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
 */



/** 
 * DFDL Award Types.
 * 
 * ! in use??? might be hardcoded and this is not needed.
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
 * DFDL Award Years.
 *
 * @return array of terms
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
* DFDL Countries.
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
* DFDL Desks.
*
* @return array of IDs
*/
function dfdl_get_countries(): array {
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 24,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
        'fields'                 => 'ids'
     );
    $pages = new WP_Query( $args );
    return $pages->posts;
}

/**
 * DFDL section
 * 
 * Return an array with section info
 * 
 * 1. solutions
 * 2. locations/country
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
    }

    return $return;

}

/**
 * Get Block Data
 * 
 * Get block data from page
 * 
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