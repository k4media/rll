<?php

/**
 * Insights Callout
 * 
 * Show insights for the page
 * Automagically filters for the correct country/solution etc
 * 
 */
global $wp, $wp_query;  

/*
if ( isset($wp_query->query['dfdl_country']) ) {
    $query_args['tax_query'][] = array(
        array(
            'taxonomy' => 'dfdl_countries',
            'field'    => 'slug',
            'terms'    => $wp_query->query['dfdl_country'],
        )
    );
}
*/

$insights = dfdl_insights();

$jump = get_home_url('', '/insights/');

/*
if ( isset($insights->tax_query->queries[0]['terms'][0]) && ! empty($insights->tax_query->queries[0]['terms'][0]) ) {
     // var_dump($insights->tax_query->queries[0]['terms'][0]);
     $jump = get_home_url('', '/insights/' . $insights->tax_query->queries[0]['terms'][0] . '/');
} else {
     $jump = get_home_url('', '/insights/');
}
*/

if ( isset($insights) ) {
     $post_class = ( $insights->have_posts() ) ? "" : "no-results" ; 
} else {
     return;
}

?>
<div class="insights-callout-stage callout">
     <div class="insights-callout silo">
          <h2>Insights</h2>
          <h3>Vulputate mattis venenatis enim eget sit enim, nisi 
enim bibendum cras risus consectetur elit cras.</h3>
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
