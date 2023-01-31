<?php

/**
 * Whitelist query vars
 */
add_filter('query_vars', 'dfdl_add_member_query_vars');
function dfdl_add_member_query_vars( $query_vars ) {
    $query_vars[] = 'dfdl_member';
    $query_vars[] = 'dfdl_country';
    return $query_vars;
} 

/**
 * Rewrite rules
 */
add_action( 'init', 'dfdl_add_rewrite_rules', 10 );
function dfdl_add_rewrite_rules() {

    /* teams/all rewrite */
	add_rewrite_rule(
        '^(.*)teams/all/?$',
        'index.php?pagename=teamsall',
        'top'
    );

    /* country/teams rewrite */
	add_rewrite_rule(
        '^locations/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/teams/?$',
        'index.php?pagename=countryteam&dfdl_country=$matches[1]',
        'top'
    );

    /* member page rewrite */
	add_rewrite_rule(
        '^(.*)/members/(.*)/(.*)?$',
        'index.php?pagename=member&dfdl_member=$matches[3]',
        'top'
    );

    /* awards/country */
	add_rewrite_rule(
        '^locations/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/awards/?$',
        'index.php?pagename=country_awards&dfdl_country=$matches[1]',
        'top'
    );

}

/**
 * Template redirects
 */
add_filter( 'template_include', 'dfdl_member_template_include' );
function dfdl_member_template_include($template) {
    global $wp_query;
	$query_vars = $GLOBALS['wp_query']->query_vars;

     /**
     * Country Team Page
     * /country/team/
     */
    if ( "countryteam" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-country-team.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

     /**
     * Team All Page
     * /teams/all
     */
	if ( "teamsall" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-team-all.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    /**
     * Team Member Page
     * /team/members/member-name
     */
	if ( "member" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-member.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    /**
     * Awards Page
     * /country/awards
     */
	if ( "country_awards" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-awards-country.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }
    
    return $template;
}
