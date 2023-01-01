<?php

/**
 * Add current-page-parent class to nav parents
 */
add_filter( 'nav_menu_link_attributes', 'dfdl_add_menu_link_class', 1, 3 );
function dfdl_add_menu_link_class( $atts, $item, $args ) {
    if ( "primary-menu" === $args->menu->slug ) {
        $section = dfdl_get_section();
        if ( null !== $section) {
            $pieces  = explode("/", $item->url); 
            if ( in_array( $section, $pieces ) ) {
                $atts['class'] = "current-page-parent";
            }
        }
    }
    return $atts;
}
