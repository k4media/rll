<?php

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
     $args                = array();
     $args['number']      = 8;
     $args['count_total'] = true;
     $args['orderby']     = array( '_dfdl_member_rank' => 'ASC', 'meta_value' => 'ASC' );

     /**
      * Sort keys
      */
     $args['meta_query'] = array(
          'relation' => 'AND',
          array(
               'key'   => '_dfdl_member_rank',
               'compare' => 'EXISTS'
          ),
          array(
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
          //$args['meta_key'] = '_dfdl_user_country';
          //$args['meta_value'] = $term->term_id;
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
          //$args['meta_key'] = '_dfdl_user_desks';
          //$args['meta_value'] = $term->term_id;
          $args['meta_query'][] = array(
               'key'     => '_dfdl_user_desks',
               'value'   => $term->term_id,
               'compare' => '='
          );
     }

     /**
      * Teams
      */
     if ( "teams" === $sections[0] ) {
          $args['fields'] = 'all_with_meta';
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


?>
<div class="team-grid-stage <?php echo implode(" ", $block_classes) ?>">
     <div class="team-grid silo">
          <?php if ( "locations" !== $sections[0] && "desks" !== $sections[0] ) : ?>
               <?php do_action("dfdl_solutions_country_nav") ?>
          <?php endif; ?>  
          <div id="results_stage" class="team-stage <?php echo $post_class ?>">
               <div>
                    <?php
                         if ( count($users) > 0) {
                              foreach( $users as $user ) {
                                   set_query_var("user", $user);
                                   get_template_part( 'includes/template-parts/content/member' );
                              }
                         } else {
                              echo '<div class="no-team-members not-found"><p>No Team Members Found.</p></div>';
                         }
                    ?>
               </div>
          </div>
          <?php
               if ( is_admin() ) {
                    echo "<h4>Showing 4 of possibly many users</h4>";
               }
          ?>
          <?php if ( count($users) > $args['number'] ) : ?>
               <a class="button green ghost see-all" href="<?php echo $jump ?>">See All</a>
          <?php endif; ?>
     </div>
</div>
