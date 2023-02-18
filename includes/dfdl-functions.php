<?php

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
                        $title = '<div class="entry">';
                                                
                        if ( count($pieces) > 1 ) {
                            $title .= '<span>' . array_shift($pieces) . ' –</span>';
                            $title .= '<span>' . implode("– ", $pieces) . '</span>';
                        } else {
                            $title .= array_shift($pieces);
                        }
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
 * Oldest year by post category
 * news, legal-and-tax, events, etc
 *
 * @return array of terms
*/
function dfdl_get_insights_years() {

    global $wp;

    $pieces   = explode("/", $wp->request ) ;
    $category = end($pieces);
    $truncate = null;

    if ( "insights" === $category ) {
        $loop = get_posts( 'numberposts=1&order=ASC' );
    } else {
        $loop = get_posts( 'numberposts=1&category=' . $category . '&order=ASC' );
    }
    
    $oldest_post_date = $loop[0]->post_date; 

    if ( isset($oldest_post_date) ) {

        $current_year = intval(date("Y"));
        $pieces       = explode("-", $oldest_post_date);
        $oldest_year  = intval($pieces[0]);
        
        if ( $current_year - 3 > $oldest_year ) {
            $oldest_year = $current_year - 3;
            $truncate = true;
        }

        $options = array();

        for ( $i = $current_year ; $i >= $oldest_year ; $i-- ) {
            $obj = (object)[];
            $obj->term_id = $i;
            $obj->name = $i;
            $obj->slug = $i;
            $options[] = $obj;
        }

        if ( true === $truncate ) {
            $obj = (object)[];
            $obj->term_id = 0;
            $obj->name = 'Older';
            $obj->slug = 'older';
            $options[] = $obj;
        }

        return $options;

    }
    
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