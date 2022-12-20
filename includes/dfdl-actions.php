<?php

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
