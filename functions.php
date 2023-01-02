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
 * DFDL SECTIONS
 * 
 * Array of valid site sections, used mostly for validation.
 * Ex: locations, solutions, teams, insights, etc.
 */
define('DFDL_SECTONS', array( "awards", "desks", "insights", "locations", "solutions", "teams") );

/**
 * DFDL_COUNTRIES
 * 
 * Array of valid countries, used mostly for validation.
 * Ex: locations, solutions, teams, insights, etc.
 */
define('DFDL_COUNTRIES', array( "bangladesh", "cambodia", "indonesia", "laos-pdr", "myanmar", "philippines", "thailand", "vietnam") );

// DFDL theme bootstrap
require get_template_directory() . '/includes/dfdl.php';