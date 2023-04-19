<?php

/**
 * Author URL
 */
add_filter( 'author_link', 'dfdl_author_link', 99, 2 );
function dfdl_author_link( $link, $user_id ) {
    $first = trim(str_replace(" ", "-", strtolower(get_user_meta($user_id, 'first_name', true))));
    $last  = trim(str_replace(" ", "-", strtolower(get_user_meta($user_id, 'last_name', true))));
    $link  = get_home_url('', 'teams/members/' . esc_attr($first) . '-' . esc_attr($last) . '/' . $user_id . '/');
    return $link;              
}

/**
 * YouTube iframe wrapper div
 */
add_filter('embed_oembed_html', 'dfdl_youtube_wrapper', 10, 4);
function dfdl_youtube_wrapper($html, $url, $attr, $post_id) {
    if (strpos($html, 'youtube') !== false) {
        return '<div class="video-container">' . $html . '</div>';
    }
    return $html;
}

/**
 * Sign in/Log out urls
 */
add_filter( 'wp_nav_menu_objects', 'dfdl_sign_in_menu_item', 10, 1 );
function dfdl_sign_in_menu_item($items) {
    foreach( $items as $item ) {
        if( $item->title === "Sign In" ) { 
            if( is_user_logged_in() ) {
                $item->title = "Sign Out";
                $item->url = wp_logout_url();
            } else {
                $item->title = "Sign In";
                $item->url = wp_login_url();
            }
        }
    }
    return $items;
}

/**
 * Insights Archive Title
 */
function dfdl_filter_archive_insights_title() {
    global $wp_query;
    $page_title = "";
    if ( isset($wp_query->query['dfdl_country']) ) {
        $country  = get_term_by("slug", $wp_query->query['dfdl_country'], "dfdl_countries");
        $page_title .= $country->name;
    }
    if ( isset($wp_query->query['dfdl_category']) ) {
        $category = get_term_by("slug", $wp_query->query['dfdl_category'], 'category');
        $page_title .= " " . $category->name;
    }
    return $page_title . " by DFDL";
}

/**
 * Style first text graph
 */
add_filter( 'the_content', 'dfdl_story_lead', 100, 2);
function dfdl_story_lead( string $content )  {  

    if ( ! empty($content) ) {
        // get first graph
        $first_graph = substr($content, 0, strpos($content, "</p>") + 4);

        // sometimes first graph is an image, so strip html
        $first_graph = strip_tags($first_graph);

        if ( "" === $first_graph ) {
            $posx  = strposX($content, "</p>", 2);
            if ( isset($posx) ) {
                $front = substr($content, 0, $posx + 4);
                $back  = substr($content, $posx);
                $front = str_replace("<p", "<p class='lead' ", $front);
                return $front . $back;
            }
        }
    }
    
    return $content;
}

/**
 * Insert event registration link
 */
add_filter( 'the_content', 'dfdl_event_registration', 10, 2);
function dfdl_event_registration( string $content )  {   

    /**
     * Only add link to Events 
     */
    if ( function_exists('get_field') && has_category(668) ) {
        $link = get_field('link');
        if ( empty($link) ) {
            return $content;
        }
    } else {
        return $content;
    }
    
    $insert = strposX($content, "</p>", 1);
    $front  = substr($content, 0, $insert+4);
    $back   = substr($content, $insert);
    
    $content = $front;
    $content .= '<div class="registration"><a class="button register" href="' . $link . '">R.S.V.P to Reserve Your Spot</a></div>';
    $content .= $back;

    return $content;
}

/**
 * Insert author box into content
 */
add_filter( 'the_content', 'dfdl_author_callout', 100, 2);
function dfdl_author_callout( string $content ): string  {       
    
    global $post;

    /**
     * Bail if no post
     */
    if ( ! isset($post) ) 
        return $content;
    
    /**
     * Bail if not in Insights
     */
    $sections = dfdl_get_section();
    if ( ! is_array($sections) && $sections[0] !== "insights")
        return $content;

    /**
     * If Tax & Legal Updates (cat id 47)
     * Use author for callout box
     */
    if ( has_category(47) ) {
        
        /**
         * Check Coauthors Plus plugin
         */
        if ( function_exists('get_coauthors') ) {
            $cos = get_coauthors();
            if ( is_array($cos) ) {
                $user = $cos[0];
            } else {
                $user = get_user_by('ID', $post->post_author);
            }
        } else {
            $user = get_user_by('ID', $post->post_author);
        }
        
    } else {

        /**
         * Use Key Contact if set
         */
        if ( function_exists('get_field')) {
            $user = get_field('contact');
            if ( null !== $user && false !== $user ) {
                $user = get_user_by('ID', $user['ID']);
            } else {
                return $content;
            }    
        }

    }

    $author = array();
    $author['avatar']   = get_avatar_url($user->data->ID, array('size' => 240));
    $author['name']     = esc_attr($user->data->display_name);
    $author['position'] = get_user_meta( $user->data->ID, 'position', true);
    $author['location'] = '';
    $author['bio']      = dfdl_short_bio( get_the_author_meta('description', $user->data->ID), 1 );
    $author['link']     = get_author_posts_url($user->data->ID);
    
    // some old user links may have spaces
    $author['link']     = str_replace(" ", "-", $author['link']);

    /** Locations */
    $locations   = array();
    $country_ids = get_user_meta( $user->data->ID, '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }
    if ( count($locations) > 0 ) {
        $author['location'] = implode(", ", $locations);
    }
    
    $meta = get_user_meta($user->data->ID);

    set_query_var("author", $author);
    set_query_var("meta", $meta);
    ob_start();
        get_template_part( 'includes/template-parts/content/author', 'callout' );
    $author_box_html = ob_get_clean();

    //$insert = strposX($content, "</p>", 6);
    //$front  = substr($content, 0, $insert+4);
    //$back   = substr($content, $insert);
    //return  $front . " " . $author_box_html . " " . $back;

    return $content . " " . $author_box_html;
}  

/**
 * Excerp customisations
 */
add_filter( 'excerpt_length', 'dfdl_excerpt_filter');
function dfdl_excerpt_filter( $length ) {
	if ( is_admin() && ! defined('DOING_AJAX') ) {
		return $length;
	}
	return 20;
}
add_filter( 'excerpt_more', 'dfdl_link_excerpt_jump');
function dfdl_link_excerpt_jump( $more ) {
	if ( is_admin() && ! defined('DOING_AJAX') ) {
		return $more;
	}
	return ' &hellip;';
 }

/**
 * Disable Gutenberg for dfdl_contact_forms CPT
 */
add_filter('use_block_editor_for_post_type', 'dfdl_disable_gutenberg_for_contact_forms', 10, 2);
function dfdl_disable_gutenberg_for_contact_forms($current_status, $post_type) {
    if ($post_type === 'dfdl_contact_forms') return false;
    return $current_status;
}

/**
 * Remove "category" from Archive urls
 */
add_filter( 'user_trailingslashit', 'dfdl_remove_category', 100, 2);
function dfdl_remove_category( $string, $type )  {       
    if ( $type != 'single' && $type == 'category' && ( strpos( $string, 'category' ) !== false ) ) {
        $url_without_category = str_replace( "/category/", "/", $string ); 
        return trailingslashit( $url_without_category );
    }
    return $string;
}

/**
 * Remove "Category:" from Archive page titles
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

/**
 * Add current-page-parent class to nav parents
 */
add_filter( 'nav_menu_link_attributes', 'dfdl_add_menu_link_class', 1, 3 );
function dfdl_add_menu_link_class( $atts, $item, $args ) {
    if ( "primary-menu" === $args->menu->slug ) {
        $sections = dfdl_get_section();
        if ( ! empty($sections) ) {
            $pieces  = explode("/", $item->url); 
            if ( in_array( $sections[0], $pieces ) ) {
                $atts['class'] = "current-page-parent";
            }
        }
    }
    return $atts;
}