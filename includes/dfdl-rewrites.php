<?php

/**
 * Whitelist query vars
 */
add_filter('query_vars', 'dfdl_add_member_query_vars');
function dfdl_add_member_query_vars( $query_vars ) {
    $query_vars[] = 'dfdl_member';
    $query_vars[] = 'dfdl_country';
    $query_vars[] = 'dfdl_category';
    return $query_vars;
} 

/**
 * Enpoints
 * 
 * Country endpoints to handle insights and post categories
 */
add_action( 'init', 'makeplugins_add_json_endpoint' );
function makeplugins_add_json_endpoint() {
    foreach( constant('DFDL_COUNTRIES') as $c) {
        add_rewrite_endpoint( $c, EP_PERMALINK );
    }
}

/**
 * Rewrite rules
 */
add_action( 'init', 'dfdl_add_rewrite_rules', 10 );
function dfdl_add_rewrite_rules() {

    /* teams/all rewrite */
	add_rewrite_rule(
        '^teams/all/?$',
        'index.php?pagename=teamsall',
        'top'
    );

    /* /locations/[country]/teams/ rewrite */
	add_rewrite_rule(
        '^locations/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/teams/?$',
        'index.php?pagename=countryteam&dfdl_country=$matches[1]',
        'top'
    );

    /* member page rewrite */
	add_rewrite_rule(
        '^teams/members/(.*)/(.*)/?$',
        'index.php?pagename=member&dfdl_member=$matches[2]',
        'top'
    );

    /* locations/[country]/awards/ */
	add_rewrite_rule(
        '^locations/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/awards/?$',
        'index.php?pagename=country_awards&dfdl_country=$matches[1]',
        'top'
    );

    /* locations/[country]/contact-us/ */
	add_rewrite_rule(
        '^locations/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/contact-us/?$',
        'index.php?pagename=country_contact&dfdl_country=$matches[1]',
        'top'
    );



    /** content hub */
    add_rewrite_rule(
        'insights/content-hub/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/?$',
        'index.php?pagename=dfdl_contenthub&dfdl_category=content-hub&dfdl_country=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        'insights/content-hub/?$',
        'index.php?pagename=dfdl_contenthub&dfdl_category=content-hub',
        'top'
    );


     /* insights/[country]/ */
	add_rewrite_rule(
        'insights/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/?$',
        'index.php?pagename=dfdl_insights_country&dfdl_country=$matches[1]',
        'top'
    );

    /* insights/[category]/[country]/ */
	add_rewrite_rule(
        'insights/(.*)/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/?$',
        'index.php?pagename=dfdl_insights&dfdl_category=$matches[1]&dfdl_country=$matches[2]',
        'top'
    );

    /** insights pagination */
    add_rewrite_rule(
        'insights/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/page/?([0-9]{1,})/?$',
        'index.php?pagename=dfdl_insights&dfdl_country=$matches[1]&page=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        'insights/(.*)/(bangladesh|cambodia|indonesia|laos-pdr|myanmar|philippines|singapore|thailand|vietnam)/page/?([0-9]{1,})/?$',
        'index.php?pagename=dfdl_insights&dfdl_category=$matches[1]&dfdl_country=$matches[2]&page=$matches[3]',
        'top'
    );
    add_rewrite_rule(
        'insights/(.*)/page/?([0-9]{1,})/?$',
        'index.php?pagename=dfdl_insights&dfdl_category=$matches[1]&page=$matches[2]',
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
     * /locations/[country]/team/
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
     * /locations/[country]/awards/
     */
	if ( "country_awards" == $query_vars['pagename']  ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-awards-country.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    /**
     * Contacts Page
     * /locations/[country]/contact-us
     */
	if ( "country_contact" == $query_vars['pagename'] || "contact-us" == $query_vars['pagename'] ) {
		$page_template = get_stylesheet_directory() . '/includes/templates/page-contact.tpl.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }
    
    /**
     * Insights 
     * /insights/[country]/
     */
	if ( "dfdl_insights_country" == $query_vars['pagename'] ) {
        $page_template = get_stylesheet_directory() . '/page-insights.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    /**
     * Insights 
     * /insights/[country]/
     * /insights/[category]/[country]/
     */
	if ( "dfdl_insights" == $query_vars['pagename'] ) {
        $page_template = get_stylesheet_directory() . '/archive-insights.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    /**
     * Content Hub 
     * /insights/content-hub/
     * /insights/content-hub/[country]/
     */
	if ( "dfdl_contenthub" == $query_vars['pagename'] ) {
        $page_template = get_stylesheet_directory() . '/archive-content-hub.php' ;				
		$wp_query->is_404 = false;
		status_header('200');
		require_once($page_template);
		exit;
    }

    return $template;
    
}
