<?php

/**
 * Author URL
 */
add_filter( 'author_link', 'dfdl_author_link', 10, 2 );
function dfdl_author_link( $link, $user_id ) {  
    $first = strtolower(get_user_meta($user_id, 'first_name', true));
    $last  = strtolower(get_user_meta($user_id, 'last_name', true));
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
 * Insert author box into content
 */
add_filter( 'the_content', 'dfdl_author_callout', 100, 2);
function dfdl_author_callout( string $content )  {       
    
    global $post;

    if ( ! isset($post) ) {
        return $content;
    }

    /**
     * Only add author box to legal-and-tax posts
     */
    $terms = wp_get_post_terms($post->ID, 'category');
    $slugs = array();
    foreach( $terms as $t ) { $slugs[] = $t->slug; }
    if ( ! in_array( 'legal-and-tax', $slugs) ) {
        return $content;
    }

    $user = get_user_by('ID', $post->post_author);

    // $link        = get_home_url(null, 'teams/members/' . $member_slug . '/' . $user->data->ID . '/');
    $position    = get_user_meta( $user->data->ID, 'position', true);
    $locations   = array();
    $country_ids = get_user_meta( $user->data->ID, '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }

    set_query_var("user", $user);
    set_query_var("position", $position);
    set_query_var("locations", $locations);

    ob_start();
        get_template_part( 'includes/template-parts/content/author', 'callout' );
    $author_box_html = ob_get_clean();


    /*
    // clean up silly quotes
    $content = iconv('UTF-8', 'ASCII//TRANSLIT', $content); 

    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->loadHTML($content);
    $xpath = new DOMXPath( $xml );

    $nodes  = $xpath->evaluate('//div[starts-with(@if, "rand")]');
    $paras  = $xpath->query("//p");
    $insert = floor( count($paras) / 5 );

    $author_box = $xml->createElement("div", "author box goes here");

    $html = $xml->createDocumentFragment();
    $html->appendXML($author_box_html );

    if ($x = $paras[$insert]) {

        $x->parentNode->insertBefore($author_box, $x);
        
        $clone = $x->cloneNode();
        $clone->appendChild($html);

        $x->parentNode->replaceChild($clone, $x);

        //$new->parentNode->appendChild($html);
        //$x->parentNode->appendChild($author_box_html);
        //$xml->documentElement->appendChild($html);

        // move all the fetched nodes into the container
        foreach($nodes as $node) {

            //if ( $counter === $insert ) {
               // $xml->appendChild($author_box_html);
            //} else {
                $xml->appendChild($node);
            //}

            //$counter++;
            
        }
    }
    $content = $xml->saveXML();
    */
    
    return $content;
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