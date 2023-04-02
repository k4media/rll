<?php

/**
 * Insights Callout
 * 
 * Show insights for the page
 * Automagically filters for the correct country/solution etc
 * 
 */
global $wp, $wp_query;  

$title    = "";
$subtitle = "";

// get fields
if ( function_exists('get_fields') ) {
     $title = get_field('title');
     $subtitle = get_field('subtitle');
}

$insights = dfdl_insights();
if ( isset($insights) ) {
     $post_class = ( $insights->have_posts() ) ? "" : "no-results" ; 
} else {
     return;
}

/**
 * View More button link
 */
$jump = get_home_url('', '/insights/');

/**
 * check for country
 */
$sections = dfdl_get_section();
$maybe_country = end($sections);
if( isset($maybe_country) && in_array( $maybe_country, constant('DFDL_COUNTRIES') ) ) {
     $jump .= $maybe_country . "/";
}

?>
<div class="insights-callout-stage callout">
     <div class="insights-callout silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="posts <?php echo $post_class ?>" >
               <?php if ( $insights->have_posts() ) : ?>
                    <?php while ( $insights->have_posts() ) : ?>
                         <?php

                         global $post;

                         // setup the post
                         $insights->the_post();

                         $term = dfdl_post_solution($post->ID);

                         // pass data to template
                         set_query_var("story", $post );
                         set_query_var("term", $term);

                         

                         //set_query_var("term", $category);
                         //set_query_var("slug", dfdl_content_hub_category($post->ID));
                         //$post_terms = wp_get_post_terms($post->ID, 'category');
                         //var_dump($post_terms);
                         //var_dump(dfdl_content_hub_category($post->ID));
                         /*
                         $startdate = get_post_meta( $post->ID, 'startdate', true);
                         if ( isset($startdate) ) {
                         $show_date = mysql2date( get_option( 'date_format' ), $startdate );
                         }
                         set_query_var("sponsor", get_post_meta( $post->ID, 'sponsor', true));
                         set_query_var("dateline", get_post_meta( $post->ID, 'dateline', true));
                         set_query_var("timeline", get_post_meta( $post->ID, 'timeline', true));
                         set_query_var("show_date", $show_date);
                         */

                         // define template
                         $file = get_stylesheet_directory() . '/includes/template-parts/content/insights-' . $term->slug . '-card.php';

                         if ( file_exists($file) ) {
                              get_template_part( 'includes/template-parts/content/insights', $term->slug . '-card' );
                         } else {
                              get_template_part( 'includes/template-parts/content/insights', 'news-card' );
                         }

                         ?>
                    <?php endwhile; ?>
               <?php else : ?>
                    <?php get_template_part( 'includes/template-parts/content/content-none' ); ?>
               <?php endif; ?>
          </div>

          <div class="jump"><a class="button green ghost see-all" href="<?php echo $jump ?>">See More</a></div>
          
     </div>
</div>
