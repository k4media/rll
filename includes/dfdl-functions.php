<?php


function dfdl_team_filter() {}

/*
* DFDL Countries
*
* @return array of IDs
*/
function dfdl_get_countries() {
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
        'fields'                 => 'ids'
     );
    $countries = new WP_Query( $args );
    return $countries->posts;
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

    $return = array();
    $pieces = explode("/", $wp->request ) ;

    if ( in_array($pieces[0], DFDL_SECTONS) ) {
        $return[0] = $pieces[0];
    }
    if ( isset($pieces[1]) && "locations" === $return[0] && in_array($pieces[1], DFDL_COUNTRIES) ) {
        $return[1] = $pieces[1];
    }

    return $return;

}