<?php
/**
 * DFDL Theme
 * 
 * Set some constants and call theme bootstrap under includes.
 * 
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * 
 */

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

/**
 * DFDL FRAGMENT CACHE
 * 
 * Use fragment caching? True of false
 */
define('DFDL_FRAGMENT_CACHE_ENABLED', false );

/**
 * DFDL SECTIONS
 * 
 * Array of valid site sections, used mostly for validation.
 * Ex: locations, solutions, teams, insights, etc.
 */
define('DFDL_SECTONS', array( "awards", "desks", "insights", "locations", "solutions", "teams") );

/**
 * DFDL COUNTRIES
 * 
 * Array of valid country slugs, used mostly for validation.
 * Ex: locations, solutions, teams, insights, etc.
 */
define('DFDL_COUNTRIES', array( "bangladesh", "cambodia", "indonesia", "laos-pdr", "myanmar", "philippines", "singapore", "thailand", "vietnam") );

/**
 * DFDL DESKS
 * 
 * Array of valid desks, used mostly for validation.
 * Ex: china, france, india, usa, etc.
 */
define('DFDL_DESKS', array( "china", "eu", "india", "usa" ) );

// DFDL theme bootstrap
require get_template_directory() . '/includes/dfdl.php';