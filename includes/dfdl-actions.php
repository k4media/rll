<?php

/**
 * Country Nav
 */
add_action('dfdl_solutions_country_nav', 'dfdl_solutions_country_nav');
function dfdl_solutions_country_nav() {

    global $wp;

    $pieces     = explode("/", $wp->request ) ;
    $sections   = array("solutions", "teams", "awards");
    $section    = array_values(array_intersect( $pieces, $sections ));

    if ( isset($section[0]) ) {
        $section = $section[0];
    } else {
        /**
         * Set fallback for is_admin()
         */
        $section = "#";
    }
    
    /**
     * Locations, as determined from subpages
     */
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
     );
    $pages = new WP_Query( $args );

    $nav       = array();
    $home_url = get_home_url(NULL);

    foreach($pages->posts as $page) {
        if ( in_array(strtolower($page->post_name), $pieces)  ) {
            $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
        } else {
            $nav[] = '<li><a href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
        }
    }

    /**
     * Prepare html output
     */
    $class = is_admin() ? "admin" : "" ;

    $output   = array();

    if ( is_admin() ) {

        $output[] = '<div class="country-nav-stage"><ul class="country-nav"><li>[country navigation may appear here, depending on section]</li></ul></div>';

    } else {

        $output[] = '<div class="country-nav-stage"><ul class="' . $class . ' ' . $section . '-country-nav country-nav">';
        if ( "all" === end($pieces) ) {
            $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $section . '/all/">All</a></li>';
        } else {
            $output[] = '<li><a href="' . $home_url . '/' . $section . '/all/">All</a></li>';
        }
        $output[] = implode("", $nav);
        $output[] = '</ul>';

        // Add teams filter
        if ( "teams" === $section ) {
            $output[] = dfdl_team_filter();
        }
        $output[] = '</div>';

    }
    
    // output
    echo implode("", $output);

}

/**
 * ACF Color Palette
 *
 * Add default color palatte to ACF color picker for branding
 * Match these colors to colors in /functions.php & /assets/scss/partials/base/variables.scss
 *
*/
add_action( 'acf/input/admin_footer', 'wd_acf_color_palette' );
function wd_acf_color_palette() { ?>
<script type="text/javascript">
(function($) {
     acf.add_filter('color_picker_args', function( args, $field ){
          // add the hexadecimal codes here for the colors you want to appear as swatches
          args.palettes = ['#000000', '131313', '#7D7D7D', '#F6F6F6', '#004C45', '#10B565', '#66000', '#FFBD5C', '#ffffff']
          // return colors
          return args;
     });
})(jQuery);
</script>
<?php }

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
