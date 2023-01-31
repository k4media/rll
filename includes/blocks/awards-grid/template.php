<?php

     /**
      * Cache results
      *
      */
     $key = "dfdl-awards";
     $K4 = new K4;
     $K4->fragment_cache( $key, function() { 


     $years        = dfdl_get_award_years();
     $award_bodies = dfdl_get_award_bodies();
     $award_types  = array("award", "ranking");

     $output       = array() ;

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
                              $output[] = '<div class="award-entry">';
                              $output[] = "<h4>" . $year->name . " " . $body->name . "</h4>";
                              $output[] = "<ul>";
                              $header_added = true;
                              $div_open = true;
                         }

                         $count = 0;
                         foreach ( $awards->posts as $p ) {

                              $pieces = explode("–", $p->post_title);
                              $title = '<div class="entry"><span>' . array_shift($pieces);
                              
                              if ( count($pieces) > 0 ) {
                                   $title .= " –</span>"; 
                              } else {
                                   $title .= "</span>"; 
                              }
                              $title .= '<span>' . implode("– ", $pieces) . '</span></div>';
                              
                              if ( "award" === $type ) {
                                   $output[] = '<li class="award">';
                              } else {
                                   $output[] = "<li>";
                              }
                              $output[] = $title;
                              $output[] = "</li>";
                              
                         }
                    }
               }
               if ( isset($div_open) && true === $div_open) {
                    $output[] = "</ul></div>";
                    $div_open = false;
               }
               
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
<?php }); // close K4 fragment ?>