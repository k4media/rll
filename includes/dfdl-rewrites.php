<?php

add_action( 'init', 'dfdl_add_rewrite_rules', 10 );
function dfdl_add_rewrite_rules() {
    /* member page rewrite */
	add_rewrite_rule(
        '^(.*)/members/(.*)/?$',
        'index.php?pagename=member&member=$matches[2]',
        'top'
    );
}

/**
 * Template redirect for 'member'
 */
add_filter( 'template_include', 'dfdl_member_template_include' );
function dfdl_member_template_include($template) {
    global $wp_query;
	$query_vars = $GLOBALS['wp_query']->query_vars;
	if ( "member" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/page-member.php' ;				
		$wp_query->is_404 = false;
		status_header( '200' );
		require_once( $page_template );
		exit;
    }
    return $template;
}



/**
 * Add 'member' query var
 */
// add_filter('query_vars', 'dfdl_add_member_query_vars');
/*
function dfdl_add_member_query_vars( $query_vars ) {
    $query_vars[] = 'member';
    return $query_vars;
} */

