<?php

function dfdl_team_filter() {}

/** 
 * DFDL Award Types.
 *
 * @return array of terms
*/
function dfdl_get_award_types() {
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
function dfdl_get_award_bodies() {
    return get_terms(array(
        'taxonomy' => 'dfdl_award_bodies',
        'hide_empty' => false,
        'orderby'  => 'name',
        'order'    => 'ASC'
    ));

}

/** 
 * DFDL Award Years.
 *
 * @return array of terms
*/
function dfdl_get_award_years() {
    return get_terms(array(
        'taxonomy' => 'dfdl_award_years',
        'hide_empty' => false,
        'orderby'  => 'id',
        'order'    => 'DESC'
    ));
}

/*
* DFDL Solutions.
*
* @return array of IDs
*/
function dfdl_get_solutions() {
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
    return $pages->posts;
}

/*
* DFDL Countries.
*
* @return array of IDs
*/
function dfdl_get_desks() {
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
function dfdl_get_countries() {
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
function dfdl_get_section() {
    
    global $wp;

    if ( is_admin() ) 
        return array("admin");
    
    $return = array();
    $pieces = explode("/", $wp->request ) ; 

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
function get_block_data($post, $block_name = 'core/heading', $field_name = "" ){
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