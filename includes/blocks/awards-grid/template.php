<?php


     $years        = dfdl_get_award_years();
     $award_bodies = dfdl_get_award_bodies();
     $award_types  = array("award", "ranking");

     $output = array() ;

     foreach ($years as $year ) {
          foreach ($award_bodies as $body ) {
               $header_added = false;
               foreach ($award_types as $type ) {
                    $args = array(
                         'post_type'      => 'dfdl_awards',
                         'post_status'    => 'publish',
                         'posts_per_page' => 99,
                         'orderby' => 'post_title',
                         'order'   => 'ASC',
                         'no_found_rows'          => true,
                         'ignore_sticky_posts'    => true,
                         'update_post_meta_cache' => false, 
                         'update_post_term_cache' => false,
                         'tax_query' => array(
                              'relation' => 'AND',
                              array(
                                   'taxonomy' => 'dfdl_award_bodies',
                                   'field' => 'id',
                                   'terms' => array( $body->term_id )
                              ),
                              array(
                                   'taxonomy' => 'dfdl_award_years',
                                   'field' => 'id',
                                   'terms' => array( $year->term_id ),
                              )
                         ),
                         'meta_query' => array(
                              array(
                                  'key'     => 'type',
                                  'value'   =>  $type,
                                  'compare' => 'LIKE',
                              ),
                          ),
                    );
                    $awards = new WP_Query( $args );
                    if ( count($awards->posts) > 0 ) {
                         if ( false == $header_added ) {
                              $output[] = "<h4>" . $year->name . " " . $body->name . "</h4>";
                              $output[] = "<ul>";
                              $header_added = true;
                         }
                         foreach ( $awards->posts as $p ) {
                              $output[] = "<li>" . $p->post_title . "</li>";
                         }
                    }
               }
               $output[] = "</ul>";
          }
     }
?>
<div class="award-grid-stage">
     <div class="award-grid silo">
          <?php do_action("dfdl_solutions_country_nav") ?>
          <div class="award-stage">
               <?php
                    echo implode($output);
               ?>
          </div>
     </div>
</div>
