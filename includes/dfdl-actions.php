<?php

/**
 * DFDL Logo
 */
add_action('dfdl_logo', 'dfdl_logo');
function dfdl_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    if ( is_front_page() ) {
        echo '<div class="site-name"><img src="' . $image[0]. '"></div>';
    } else {
        echo '<div class="site-name"><a href="' . get_home_url(). '"><img src="' . $image[0] . '"></a></div>';
    }    
}

/**
 * Solutions Country Nav
 */
add_action('dfdl_solutions_country_nav', 'dfdl_solutions_country_nav');
function dfdl_solutions_country_nav() {

    global $wp;

    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order'
     );
    $pages = new WP_Query( $args );

    $ul = array();
    $home_url = get_home_url(NULL);

    $pieces = explode("/", $wp->request);

    foreach($pages->posts as $page) {
        if ( in_array(strtolower($page->post_name), $pieces)  ) {
            $ul[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $page->post_name . '/solutions/">' . $page->post_title . '</a></li>' ;
        } else {
            $ul[] = '<li><a href="' . $home_url . '/locations/' . $page->post_name . '/solutions/">' . $page->post_title . '</a></li>' ;
        }
    }

    $output   = array();
    $output[] = '<div class="country-nav-stage"><ul class="solutions-country-nav country-nav">';
    if ( 1 === count($pieces) ) {
        $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/solutions/' . '">All</a></li>';
    } else {
        $output[] = '<li><a href="' . $home_url . '/solutions/' . '">All</a></li>';
    }
    
    $output[] = implode("", $ul);
    $output[] = '</ul></div>';

    echo implode("", $output);
}

/**
 * Footer actions
 */
add_action('footer_logo', 'footer_logo');
function footer_logo() {
    $mods = get_theme_mods();
    echo '<div class="site-name"><img src="' . $mods['dfdl_logo_reversed'] . '"></div>';
}

add_action('footer_text', 'footer_text');
function footer_text() {
    if( function_exists('get_field') ) {
        $text = get_field('ftext', 'option');
        if ( isset($text) ) {
            echo '<div class="footer-text">' . str_replace('[year]', date('Y'), $text) . '</div>';
        }
    }
}

add_action('footer_nav', 'footer_nav');
function footer_nav() {
    echo '<div class="footer-nav">';
    wp_nav_menu( array(
        'theme_location'	=> 'footer',
        'fallback_cb'       => '',
        'container'         => 'nav'
    ) );
    echo '</div>';
}

add_action('newsletter_signup', 'newsletter_signup');
function newsletter_signup() {
    get_template_part( 'includes/template-parts/footer/newsletter-signup' );
}

add_action('copyright_notice', 'copyright_notice');
function copyright_notice() {
    if( function_exists('get_field') ) {
        $notice = get_field('cnotice', 'option');
        if ( isset($notice) ) {
            echo '<div class="copyright-notice">' . str_replace('[year]', date('Y'), $notice) . '</div>';
        }
    }
}

add_action('social_links', 'social_links');
function social_links() {
    $mods = get_theme_mods();
    echo '<div class="social-links">';
    if ( isset($mods['dfdl_linkedin']) ) {
        echo '<a href="' . $mods['dfdl_linkedin'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-linkedin.svg"></a>';
    }
    if ( isset($mods['dfdl_facebook']) ) {
        echo '<a href="' . $mods['dfdl_facebook'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-facebook.svg"></a>';
    }
    if ( isset($mods['dfdl_twitter']) ) {
        echo '<a href="' . $mods['dfdl_twitter'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-twitter.svg"></a>';
    }  
    if ( isset($mods['dfdl_youtube']) ) {
        echo '<a href="' . $mods['dfdl_youtube'] . '"><img src="' . get_stylesheet_directory_uri() . '/assets/media/icon-youtube.svg"></a>';
    }
    echo '</div>';
}

add_action('legal_nav', 'legal_nav');
function legal_nav() {
    echo '<div class="legal-nav">';
    wp_nav_menu( array(
        'theme_location'	=> 'legal',
        'fallback_cb'       => '',
        'container'         => 'nav'
    ) );
    echo '</div>';
}
