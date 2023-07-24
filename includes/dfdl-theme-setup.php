<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 */

/**
 * Theme setup
 */
add_action( 'after_setup_theme', 'dfdl_theme_setup' );
function dfdl_theme_setup() {

    /*
     * Let WordPress manage the document title.
     * This theme does not use a hard-coded <title> tag in the document head,
     * WordPress will provide it for us.
     */
    add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'primary' 	=> esc_html__( 'Primary menu', 'dfdl' ),
			'secondary' => esc_html__( 'Secondary menu', 'dfdl' ),
			'side'  	=> esc_html__( 'Side menu', 'dfdl' ),
			'mobile'  	=> esc_html__( 'Mobile menu', 'dfdl' ),
			'footer'  	=> esc_html__( 'Footer menu', 'dfdl' ),
			'legal'  	=> esc_html__( 'Legal menu', 'dfdl' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => esc_html__( 'Small', 'dfdl' ),
				'shortName' => esc_html_x( 'S', 'Font size', 'dfdl' ),
				'size'      => 14,
				'slug'      => 'extra-small',
			),
			array(
				'name'      => esc_html__( 'Body', 'dfdl' ),
				'shortName' => esc_html_x( 'M', 'Font size', 'dfdl' ),
				'size'      => 16,
				'slug'      => 'small',
			),
			array(
				'name'      => esc_html__( 'Large', 'dfdl' ),
				'shortName' => esc_html_x( 'L', 'Font size', 'dfdl' ),
				'size'      => 18,
				'slug'      => 'normal',
			),
			array(
				'name'      => esc_html__( 'Big', 'dfdl' ),
				'shortName' => esc_html_x( 'XL', 'Font size', 'dfdl' ),
				'size'      => 28,
				'slug'      => 'large',
			),
			array(
				'name'      => esc_html__( 'Huge', 'dfdl' ),
				'shortName' => esc_html_x( 'XXL', 'Font size', 'dfdl' ),
				'size'      => 32,
				'slug'      => 'extra-large',
			),
			array(
				'name'      => esc_html__( 'Jumbo', 'dfdl' ),
				'shortName' => esc_html_x( 'XXXL', 'Font size', 'dfdl' ),
				'size'      => 56,
				'slug'      => 'huge',
			),
			array(
				'name'      => esc_html__( 'Super Jumbo', 'dfdl' ),
				'shortName' => esc_html_x( 'XXXXL', 'Font size', 'dfdl' ),
				'size'      => 72,
				'slug'      => 'gigantic',
			),
		)
	);

	// Editor color palette.
	$black     	  = '#000000';
	$dark_gray 	  = '#131313';
	$gray      	  = '#7D7D7D';
	$light_gray   = '#F6F6F6';
	$green     	  = '#004C45';
	$bright_green = '#10B565'; 
	$red		  = '#66000';
	$yellow       = '#FFBD5C';
	$white        = '#FFFFFF';

	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Black', 'dfdl' ),
				'slug'  => 'black',
				'color' => $black,
			),
			array(
				'name'  => esc_html__( 'Dark gray', 'dfdl' ),
				'slug'  => 'dark-gray',
				'color' => $dark_gray,
			),
			array(
				'name'  => esc_html__( 'Gray', 'dfdl' ),
				'slug'  => 'gray',
				'color' => $gray,
			),
			array(
				'name'  => esc_html__( 'Light Gray', 'dfdl' ),
				'slug'  => 'light-gray',
				'color' => $light_gray,
			),
			array(
				'name'  => esc_html__( 'Green', 'dfdl' ),
				'slug'  => 'green',
				'color' => $green,
			),
			array(
				'name'  => esc_html__( 'Bright Green', 'dfdl' ),
				'slug'  => 'bright-green',
				'color' => $bright_green,
			),
			array(
				'name'  => esc_html__( 'Red', 'dfdl' ),
				'slug'  => 'red',
				'color' => $red,
			),
			array(
				'name'  => esc_html__( 'Yellow', 'dfdl' ),
				'slug'  => 'yellow',
				'color' => $yellow,
			),
			array(
				'name'  => esc_html__( 'White', 'dfdl' ),
				'slug'  => 'white',
				'color' => $white,
			),
		)
	);

	// Remove feed icon link from legacy RSS widget.
	add_filter( 'rss_widget_feed_link', '__return_false' );
	
}

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'dfdl_scripts' );
function dfdl_scripts() {

	// Theme Stylesheet
	$filetime = filemtime( get_stylesheet_directory() . '/style.css');
	wp_enqueue_style('dfdl', get_stylesheet_directory_uri() . '/style.css', false, $filetime, 'all');

	// Theme JS
	$filetime = filemtime( get_stylesheet_directory() . '/assets/js/dfdl.js');
	wp_enqueue_script('dfdl', get_stylesheet_directory_uri() . '/assets/js/dfdl.js', array("jquery"), $filetime, true );

	// Masonry for Our Commitments
	// if ( is_page('our-firm') ) { wp_enqueue_script('masonry'); }
	
	// Localize script for ajax calls
	$params = array(
		'ajaxurl' 		 	=> admin_url( 'admin-ajax.php' ),
		'awards_nonce' 	 	=> wp_create_nonce('dfdl_awards'),
		'teams_nonce' 	 	=> wp_create_nonce('dfdl_teams'),
		'teams_see_more' 	=> wp_create_nonce('dfdl_teams_see_more'),
		'insights_nonce' 	=> wp_create_nonce('dfdl_insights'),
		'insights_see_more' => wp_create_nonce('dfdl_insights_see_more'),
		'permalink'         => get_permalink(),
		'stylesheet_uri'	=> get_stylesheet_directory_uri() . '/assets/media'
	);
	wp_localize_script( 'dfdl', 'ajax_object', $params);

}

add_action( 'admin_enqueue_scripts', 'enqueuing_admin_scripts' );
function enqueuing_admin_scripts(){
    wp_enqueue_style('dfdl', get_template_directory_uri().'/assets/css/admin.css');
}

add_action( 'after_setup_theme', 'dfdl_custom_logo_setup' );
function dfdl_custom_logo_setup() {
	$defaults = array(
		'height'               => 100,
		'width'                => 400,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true, 
	);
	add_theme_support( 'custom-logo', $defaults );
} 

add_action( 'customize_register', 'dfdl_customize_register' );
function dfdl_customize_register($wp_customize) {

	$wp_customize->add_setting('dfdl_logo_reversed');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'dfdl_logo_reversed', array(
        'label' => 'Reversed logo',
        'section' => 'title_tagline', 
        'settings' => 'dfdl_logo_reversed',
        'priority' => 8 
    )));

	// Register theme options
    $wp_customize->add_section( 'dfdl_theme_options' , array(
        'title'      => __( 'Social Media', 'dfdl' ),
        'priority'   => 5000,
        'sanitize_callback'  => 'sanitize_url',
    ));

    // linkedin
    $wp_customize->add_setting( 'dfdl_linkedin' , array(
        'default'     => '',
        'transport'   => 'refresh',
        'sanitize_callback'  => 'sanitize_url',
    ));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'dfdl_linkedin',
            array(
                'label'          => __( 'LinkedIn', 'dfdl' ),
                'section'        => 'dfdl_theme_options',
                'settings'       => 'dfdl_linkedin',
                'type'           => 'text'
            )
        )
    );
    // facebook
    $wp_customize->add_setting( 'dfdl_facebook' , array(
        'default'     => '',
        'transport'   => 'refresh',
        'sanitize_callback'  => 'sanitize_url',
    ));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'dfdl_facebook',
            array(
                'label'          => __( 'Facebook', 'dfdl' ),
                'section'        => 'dfdl_theme_options',
                'settings'       => 'dfdl_facebook',
                'type'           => 'text'
            )
        )
    );
    //twitter
    $wp_customize->add_setting( 'dfdl_twitter' , array(
        'default'     => '',
        'transport'   => 'refresh',
        'sanitize_callback'  => 'sanitize_url',
    ));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'dfdl_twitter',
            array(
                'label'          => __( 'Twitter', 'dfdl' ),
                'section'        => 'dfdl_theme_options',
                'settings'       => 'dfdl_twitter',
                'type'           => 'text'
            )
        )
    );
    //youtube
    $wp_customize->add_setting( 'dfdl_youtube' , array(
        'default'     => '',
        'transport'   => 'refresh',
        'sanitize_callback'  => 'sanitize_url',
    ));
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'dfdl_youtube',
            array(
                'label'          => __( 'YouTube', 'dfdl' ),
                'section'        => 'dfdl_theme_options',
                'settings'       => 'dfdl_youtube',
                'type'           => 'text'
            )
        )
    );
}

/*
 * Create a theme options page with ACF
 */
add_action( 'admin_menu', 'rll_settings_page' );
function rll_settings_page() {
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	=> "RLL Settings",
			'menu_title'	=> "RLL",
			'menu_slug' 	=> 'dfdl-settings',
			'capability'	=> 'edit_posts',
			'redirect'		=> false
		));
	}
}