<?php

add_action('init', 'dfdl_migrate');
function dfdl_migrate() {
    if ( isset($_GET['migration']) && "run" === $_GET['migration'] ) {
        dfdl_migration_update_countries();
        dfdl_migration_update_categories();
        dfdl_migration_update_keywords();
    }
    if ( isset($_GET['migration']) && "reset" === $_GET['migration'] ) {
        dfdl_migration_reset_all();
    }
    exit;
}


function dfdl_migration_update_keywords() {  

    $counter = 0;

    $country_terms = dfdl_get_countries_tax();
    $dfdl_countries = array();
    foreach( $country_terms as $ct ) {
        $dfdl_countries[strtolower($ct->name)] = $ct->term_id;
    }

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => array('publish', 'pending', 'draft', 'future', 'private'),
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
    );
    
    $posts = new WP_Query( $query_args );

    echo "<h3>Processing posts</h3>";

    if ( $posts->post_count > 0 ) {

        foreach ( $posts->posts as $post ) {

            echo "<p>" . $counter . " | <a href='" . get_permalink($post->ID) . "'>" . $post->post_title . "</a> (" . $post->ID . ")</p>";

            $post_title     = preg_replace('/[[:punct:]]/', '', $post->post_title);
            $post_title     = convert_smart_quotes($post_title);
            $post_title     = str_replace("'", " ", $post_title);
            $post_title     = strtolower($post_title);
            $post_title     = str_replace("laos pdr", "laos-pdr", $post_title);
            $post_title     = str_replace("lao pdr", "laos-pdr", $post_title);
            $words          = explode(" ", strtolower($post_title));
        
            /**
             * Countries
             */
            $countries['bangladesh'] = array(
                "dhaka"
            );
            $countries['cambodia'] = array(
                "phnom penh"
            );
            $countries['indonesia'] = array(
                "jakarta"
            );
            $countries['lao-pdr'] = array(
                "vientiane"
            );
            $countries['myanmar'] = array(
                "yangoon"
            );
            $countries['philippines'] = array(
                "manilla"
            );
            $countries['thailand'] = array(
                "bangkok",
                "samui",
                "thai"
            );
            $countries['vietnam'] = array(
                "hanoi",
                "ho chi minh"
            );

            foreach ( $countries as $key => $value ) {
                if ( array_intersect( $value, $words ) ) {
                    echo $key . " " . $dfdl_countries[$key] . "<br>";
                    wp_set_post_terms($post->ID, $dfdl_countries[$key], 'dfdl_countries', true);
                }
            }

            /**
             * Solutions
             */
            $solutions = array();

            $solutions['anti-trust-competition'] = array();
            $solutions['aviation-logistics'] = array();
            $solutions['banking-finance'] = array();
            $solutions['compliance-investigations'] = array();
            $solutions['corporate-and-ma'] = array();
            $solutions['dispute-resolution'] = array();
            $solutions['employment'] = array();
            $solutions['energy-natural-resources-infrastructure'] = array();
            $solutions['healthcare-life-science'] = array();
            $solutions['investment-funds'] = array();
            $solutions['real-estate-hospitality'] = array();
            $solutions['restructuring'] = array();
            $solutions['tax-transfer-pricing'] = array();
            $solutions['technology-media-telecoms'] = array();



            /**
             * Set dfdl_countries tax
             */
            //wp_set_post_terms($post->ID, $solution->term_id, 'dfdl_solutions');
                
            $counter++;

        }

    } else {
        echo "<p>no posts found</p>";
    }

    
    
    echo "<p>done!</p>";

}


function dfdl_migration_update_categories() {  

    $counter = 0;
    $limit   = 10000;

    $solutions = dfdl_get_solutions_tax();

    foreach ( $solutions as $solution ) {

        $search_term = str_replace("and", "", $solution->name);
        $search_term = str_replace('&#038;', "", $search_term);
        $search_term = preg_replace('/[[:punct:]]/', '', $search_term);

        $query_args = array(
            'post_type'      => 'post',
            'post_status'    => array('publish', 'pending', 'draft', 'future', 'private'),
            's'              => $search_term,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'          => true,
            'ignore_sticky_posts'    => true,
            'update_post_meta_cache' => false, 
            'update_post_term_cache' => false,
            );
        
        $posts = new WP_Query( $query_args );
    
        echo "<h3>Processing posts: " . $solution->name . " (" . $search_term . ") </h3>";

        if ( $posts->post_count > 0 ) {

            foreach ( $posts->posts as $post ) {
    
                echo "<p>" . $counter . " | " . $post->post_title . " (" . $post->ID . ")</p>";
    
                /**
                 * Set dfdl_countries tax
                 */
                wp_set_post_terms($post->ID, $solution->term_id, 'dfdl_solutions');
                    
                /**
                 * Check for script limit
                 */
                $counter++;
                if ( $counter >= $limit ) {
                    echo "<p>stopping at " . $limit . " posts</p>";
                    exit;
                }
    
            }
    
        } else {
            echo "<p>no posts found for " . $solution->name . "</p>";
        }

    }
    
    echo "<p>done!</p>";

}


function dfdl_migration_update_countries() {  
    
    $counter = 0;
    $limit   = 10000;

    $dfdl_countries = dfdl_get_countries(array('return'=>'slug'));

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => array('publish', 'pending', 'draft', 'future', 'private'),  
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
        );
    
    $posts = new WP_Query( $query_args );

    if ( $posts->post_count > 0 ) {

        echo "<p>Processing posts</p>";

        foreach ( $posts->posts as $post ) {

            echo "<p><strong>" . $counter . " | " . $post->post_title . " (" . $post->ID . ")</strong></p>";

            /**
             * Check title for country name, add to tax if present
             */
            $post_countries = array();
            $post_title     = preg_replace('/[[:punct:]]/', '', $post->post_title);
            $post_title     = convert_smart_quotes($post_title);
            $post_title     = str_replace("'", " ", $post_title);
            $post_title     = strtolower($post_title);
            $post_title     = str_replace("laos pdr", "laos-pdr", $post_title);
            $post_title     = str_replace("lao pdr", "laos-pdr", $post_title);
            $words          = explode(" ", strtolower($post_title));
            $post_countries = array_intersect( $words, $dfdl_countries );

            if ( count($post_countries) > 0 ) {
                echo "Title Countries: " . implode(",", $post_countries)  . "</br>";
            }

            /**
             * Check post for country categories
             */
            $category_countries = array();
            $terms = wp_get_post_terms( $post->ID, 'category');
            $post_term_names = array();
            foreach( $terms as $t ) {
                $post_term_names[] = strtolower($t->name);
            }
            $category_countries = array_intersect( $post_term_names, $dfdl_countries );
            if ( count($category_countries) > 0 ) {
                foreach( $category_countries as $c ) {
                    $post_countries[] = $c;
                }
                //echo "Category Countries: " , implode(",", $category_countries) . "<br>";
            }

            /**
             * Set dfdl_countries tax
             */
            $post_countries = array_unique($post_countries);
            if ( count($post_countries) > 0 ) {
                echo "Countries: " . implode(",", $post_countries) . "</br>";
                foreach( $post_countries as $pc ) {
                    $term = get_term_by('slug', $pc, 'dfdl_countries');
                    wp_set_post_terms($post->ID, $term->term_id, 'dfdl_countries', 'true');
                }
            }

            /**
             * Check for script limit
             */
            $counter++;
            if ( $counter >= $limit ) {
                echo "<p>stopping at " . $limit . " posts</p>";
                exit;
            }

        }

    } else {
        echo "<p>no posts selected</p>";
    }
    
    echo "<p>done!</p>";
 
}

function dfdl_migration_reset_all() {  

    $solutions_terms = dfdl_get_solutions_tax();
    $dfdl_solutions = array();
    foreach( $solutions_terms as $st ) {
        $dfdl_solutions[] = $st->term_id;
    }

    $country_terms = dfdl_get_countries_tax();
    $dfdl_countries = array();
    foreach( $country_terms as $ct ) {
        $dfdl_countries[] = $ct->term_id;
    }

    $query_args = array(
        'post_type'      => 'post',
        'post_status'    => array('publish', 'pending', 'draft', 'future', 'private'),
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
        );
    
    $posts = new WP_Query( $query_args );

    echo "<h3>Processing posts</h3>";

    $counter = 0; 

    if ( $posts->post_count > 0 ) {

        foreach ( $posts->posts as $post ) {
            echo "<p>" . $counter . " | " . $post->post_title . " (" . $post->ID . ")</p>";
            wp_remove_object_terms($post->ID, $dfdl_solutions, 'dfdl_solutions');    
            wp_remove_object_terms($post->ID, $dfdl_countries, 'dfdl_countries');
            $counter++;
        }

    } else {
        echo "<p>no posts found</p>";
    }

    echo "<p>done!</p>";

}

// helper function for migration script
function convert_smart_quotes(string $string): string{

    $chr_map = array(
        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
        "\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
        "\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
        "\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
        "\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
     
        // Regular Unicode     // U+0022 quotation mark (")
                               // U+0027 apostrophe     (')
        "\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
        "\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
        "\xE2\x80\x98" => "'", // U+2018 left single quotation mark
        "\xE2\x80\x99" => "'", // U+2019 right single quotation mark
        "\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
        "\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9C" => '"', // U+201C left double quotation mark
        "\xE2\x80\x9D" => '"', // U+201D right double quotation mark
        "\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
        "\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
     );
     $chr = array_keys  ($chr_map); // but: for efficiency you should
     $rpl = array_values($chr_map); // pre-calculate these two arrays
     $str = str_replace($chr, $rpl, html_entity_decode($string, ENT_QUOTES, "UTF-8"));

     return $str;

}
