<?php


function dfdl_team_filter() {}

function dfdl_get_countries() {
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
     );
    $countries = new WP_Query( $args );
    return $countries->posts;
}

function dfdl_get_section() {
    global $wp;
    $pieces     = explode("/", $wp->request ) ;
    $sections   = array( "awards", "desks", "insights", "solutions", "teams" );
    $section    = array_values(array_intersect( $pieces, $sections ));
    if ( is_array($section) && isset($section[0]) )
        return $section[0];
}