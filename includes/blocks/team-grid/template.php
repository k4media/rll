<?php

// Swiper.js
wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.js' );
wp_enqueue_style('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');

$sections = dfdl_get_section();

/**
 * Block css class
     *
     * Class based on section and subsection
     */
$block_classes = array( $sections[0] );
if ( "locations" === $sections[0] && isset($sections[1]) ) {
     $block_classes[] = "country ";
     $block_classes[] = $sections[1];
}
if ( "desks" === $sections[0] && isset($sections[1]) ) {
     $block_classes[] = "desks ";
     $block_classes[] = $sections[1];
}
if ( "teams" === $sections[0] ) {
     $block_classes[] = "teams ";
}

/**
 * View All Link
     */
if ( "teams" === $sections[0] ) {
     $jump = get_home_url(null, '/teams/all/');
} else {
     $jump = get_home_url(null, $sections[0] . '/' . $sections[1] . '/teams/');
}

/**
 * Build User Query
 */
$args = array(
     'number'    =>  get_option('posts_per_page'),
     'role__in' => array('dfdl_member'),
     'orderby'   => array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' ),
     'no_found_rows'          => false,
     'ignore_sticky_posts'    => true,
     'update_post_meta_cache' => false, 
     'update_post_term_cache' => false,
 );

/**
 * Sort keys
 */
$args['meta_query'] = array(
     'relation' => 'AND',
     'dfdl_rank' => array(
          'key'   => '_dfdl_member_rank',
          'compare' => 'EXISTS'
     ),
     'last_name' => array(
          'key'   => 'last_name',
          'compare' => 'EXISTS'
     ),
);

/**
 * Solutions
 */
if ( "solutions" === $sections[0] ) {
     $term = get_term_by('slug', sanitize_title($sections[1]), 'dfdl_solutions');
     $args['meta_query'][] = array(
          'key'     => '_dfdl_user_solutions',
          'value'   => $term->term_id,
          'compare' => '='
     );
}

/**
 * Locations
 */
if ( "locations" === $sections[0] ) {
     $term = get_term_by('slug', sanitize_title($sections[1]), 'dfdl_countries');
     $args['meta_query'][] = array(
          'key'     => '_dfdl_user_country',
          'value'   => $term->term_id,
          'compare' => '='
     );
}

/**
 * Desks
 */
if ( "desks" === $sections[0] ) {
     $term = get_term_by('slug', sanitize_title($sections[1]), 'dfdl_desks');
     $args['meta_query'][] = array(
          'key'     => '_dfdl_user_desks',
          'value'   => $term->term_id,
          'compare' => '='
     );
}

/**
 * Limit members in admin
*/
if ( is_admin() ) {
     $args['number'] = 4;
     $jump = "#";
}

/**
 * User query
 */
$users = get_users($args);

$post_class = ( count($users) > 0  ) ? "" : "no-results" ; 

if ( count($users) > 0) :

?>

<div class="team-grid-stage <?php echo implode(" ", $block_classes) ?>">
     <input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
     <input type="hidden" id="teams_all_page" name="teams_all_page" value="1">
     <?php if ( isset($GLOBALS['wp_query']->query_vars['dfdl_country'])) : ?>
          <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" />
     <?php else: ?>
          <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="" />
     <?php endif; ?>
     <?php if ( "locations" !== $sections[0] && "desks" !== $sections[0] && "solutions" !== $sections[0] ) : ?>
          <div id="beacon"></div>
          <div id="subnav-stage"><?php do_action("dfdl_solutions_country_nav") ?></div>
     <?php endif; ?>  
     <div id="team-grid" class="team-grid silo">
          <div id="results_stage" class="team-stage <?php echo $post_class ?>">
               <div id="team-grid-swiper">
                    <?php

                         if ( count($users) > 0) {

                              ob_start();
                              foreach( $users as $user ) {
                                   set_query_var("user", $user);
                                   get_template_part( 'includes/template-parts/content/member' );
                              }
                              $slides = ob_get_clean();

                              ob_start();
                                   get_template_part( 'includes/template-parts/content/swiper', 'team-callout' );
                              $template = ob_get_clean();
                              $template = str_replace("{posts}", $slides, $template);
                              echo $template;

                         } else {
                              echo '<div class="no-team-members not-found"><p>No Team Members Found.</p></div>';
                         }
                    ?>
                    <?php if ( "desks" !== $sections[0] ) : ?>
                         <div class="see-more">
                              <a class="button green ghost see-all" href="<?php echo $jump ?>">See All</a>
                         </div>
                    <?php endif; ?>
                    </div>
               </div>
          </div>
          
          <?php
               if ( is_admin() ) {
                    echo "<h4>Showing 4 of possibly many users</h4>";
               }
          ?>
          
     </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
     swiperInit = false;
     function swiperCard() {
          if (window.innerWidth <= 700) {
               if (!swiperInit) {
                    swiperInit = true;
                    teamSwiper = new Swiper(".team-swiper", {
                         loop: false,
                         preloadImages: true,
                         lazy: true,
                         breakpoints: {
                              400: {
                                   slidesPerView: 2,
                                   spaceBetween: 24,
                              },
                              0: {
                                   slidesPerView: 1.1,
                                   spaceBetween: 24,
                              },
                         }
                    });
               }
          } else if (swiperInit) {
               teamSwiper.destroy();
               swiperInit = false;
          }
     }
     swiperCard();
     window.addEventListener("resize", swiperCard);
});
</script>
<?php endif; ?>