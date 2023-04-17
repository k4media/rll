<?php

/**
 * Insights Callout
 * 
 * Show insights for the page
 * Automagically filters for the correct country/solution etc
 * 
 */
global $wp, $wp_query;  

$title    = "Insights";
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
          <?php if ( isset($title) && $title !== "") : ?>
               <h2><?php echo $title ?></h2>
          <?php endif; ?>
          <?php if ( isset($subtitle) && $subtitle !== "") : ?>
               <h3><?php echo $subtitle ?></h3>
          <?php endif; ?>
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
