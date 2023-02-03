<?php

// add_action( 'pre_get_posts', 'dfdl_awards_sort' );
/*
function dfdl_awards_sort( $query ) {
    if ( $query->is_main_query() && !is_admin() ) {
        if ( $query->is_tax() || $query->is_post_type_archive() ) {
            $query->set('orderby', 'dfdl_award_years');  
            $query->set('meta_key', 'sort_order');  
            $query->set('order', 'ASC'); 
        }       
    }
} 
*/

/**
 * DFDL Award Filter
 * 
 * Return HTML select2 for filter facet
 * 
 * @type Filter facet: type, solution, year
 * 
 * @return string
 * 
 */
add_action('dfdl_filter', 'dfdl_filter', 1);
function dfdl_filter( string $filter ): void {

    switch($filter) {

        case "award_bodies":
            $options = dfdl_get_award_bodies();
            break;
        case "award_solutions":
            $options = dfdl_get_solutions_tax();
            break;
        case "award_years":
            $options = dfdl_get_award_years();
            break;
        default:
            // no default
    }

    $select   = array();
    $select[] = '<select multiple="multiple" id="' . $filter . '" name="' . $filter . '">';
    foreach( $options as $option ) {
        $select[] = '<option name="' . $option->slug. '" value="' . $option->term_id. '">' .  $option->name . '</option>'; 
    }
    $select[] = '</select>';

    echo implode($select);

}

/**
 * Country Nav
 */
add_action('dfdl_solutions_country_nav', 'dfdl_solutions_country_nav');
function dfdl_solutions_country_nav() {

    global $wp;

    $pieces     = explode("/", $wp->request ) ;
    $sections   = array("teams", "awards");
    $section    = array_values(array_intersect( $pieces, $sections ));

    if ( isset($section[0]) ) {
        $section = $section[0];
    } else {
        /**
         * Set fallback for is_admin()
         */
        if ( is_admin() ) {
            echo '<nav class="country-subnav-stage"><ul><li>[country navigation may appear here, depending on section]</li></ul></nav>';
        }
        return;
    }
    
    /**
     * Locations, as determined from subpages
     */
    $locations = get_page_by_path("locations");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => 12,
        'post_parent'    => $locations->ID,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
     );
    $pages = new WP_Query( $args );

    $nav      = array();
    $home_url = get_home_url(NULL);

    /**
     * Make navigation links
     */
    foreach($pages->posts as $page) {
        if ( is_admin() ) {
            $nav[] = '<li><a class="current-menu-item" href="#">' . $page->post_title . '</a></li>' ;
        } else {
            if ( in_array(strtolower($page->post_name), $pieces)  ) {
                $nav[] = '<li><a class="current-menu-item" href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
            } else {
                $nav[] = '<li><a href="' . $home_url . '/locations/' . $page->post_name . '/' . $section . '/">' . $page->post_title . '</a></li>' ;
            }
        }
    }

    // Add teams filter
    if ( "teams" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "awards");
        $nav[] = ob_get_clean();
    }
    // Add awards filter
    if ( "awards" === $section ) {
        ob_start();
            get_template_part("includes/template-parts/filters/filter", "awards");
        $nav[] = ob_get_clean();
    }

    /**
     * Prepare html output
     */
    $class  = is_admin() ? "admin" : "" ;
    $output = array();

    $output[] = '<nav class="country-subnav-stage"><ul class="' . $class . ' ' . $section . '-country-nav country-nav">';
    if ( "all" === end($pieces) ) {
        $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $section . '/all/">All</a></li>';
    } else {
        if( "awards" === $section ) {
            if ( count($pieces) == 1 ) {
                $output[] = '<li><a class="current-menu-item" href="' . $home_url . '/' . $section . '/">All</a></li>';
            } else {
                $output[] = '<li><a href="' . $home_url . '/' . $section . '/">All</a></li>';
            }
        } else {
            $output[] = '<li><a href="' . $home_url . '/' . $section . '/all/">All</a></li>';
        }
    }

    $output[] = implode("", $nav);
    $output[] = '</ul>';

    
    $output[] = '</nav>';

    // output
    echo implode("", $output);

}

/**
 * Get resuable block by ID
 */
add_action( 'dfdl_reusable_block', 'dfdl_reusable_block', 10, 1);
function dfdl_reusable_block( int $id ) {
    if ( ! isset($id) ) 
        return;
    $block = get_post( $id );
    echo apply_filters( 'the_content', $block->post_content);
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