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

     /**
      * User query args
      */
     $args = array();

     $args['no_found_rows']          = true;
     $args['ignore_sticky_posts']    = true;
     $args['update_post_meta_cache'] = false;
     $args['update_post_term_cache'] = false;

     $args['number']                 = 12;

     if ( "locations" === $sections[0] ) {
          $term = get_term_by('slug', sanitize_title($sections[1]), 'dfdl_countries');
          $args['meta_key'] = '_dfdl_user_country';
          $args['meta_value'] = $term->term_id;

          // $jump = get_home_url(null, $sections[0] . '/' . $sections[1] . '/teams/');

     }
     if ( is_admin() ) {
          $args['number'] = 4;
     }
     
     /**
      * User query
      */
     $users  = get_users($args);
     $output = array();
     foreach( $users as $u ) {
          
          $position    = get_user_meta( $u->data->ID, 'position', true);
          $locations   = array();
          $country_ids = get_user_meta( $u->data->ID, '_dfdl_user_country'); 
          foreach( $country_ids as $c ) {
               $country = get_term( $c, 'dfdl_countries', true);
               $locations[] = $country->name;
          }

          $member_slug = sanitize_title($u->data->display_name);

          $output[] = '<div class="team-member">';
               $output[] = '<a href="' . get_home_url(null, 'teams/members/' . $member_slug . '/' . $u->data->ID . '/') . '">';
               $output[] = '<img src="' . get_avatar_url($u->data->ID, array('size' => 320)) . '">';
               $output[] = '<div class="details-stage"><div class="details">';
                    $output[] = '<div class="name">' . $u->data->display_name . '</div>';
                    if (isset($position)) {
                          $output[] = '<div class="position">' . $position . '</div>'; 
                    }
                    if ( count($locations) > 0 ) {
                            $output[] = '<div class="location">' . implode(", ", $locations) . '</div>'; 
                    }
                $output[] = '</div></div>';
                $output[] = '</a>';
          $output[] = '</div>';
          
     }

?>
<div class="team-grid-stage <?php echo implode(" ", $block_classes) ?>">
     <div class="team-grid silo">
          <?php if ( "locations" !== $sections[0] ) : ?>
               <?php do_action("dfdl_solutions_country_nav") ?>
          <?php endif; ?>   
          <div class="team-stage">
               <?php echo implode($output) ?>
          </div>
          <?php /* if ( "locations" !== $sections[0] ) : ?>
               <a class="button green ghost" href="<?php echo $jump ?>">See All</a>
          <?php endif; */?>
     </div>
</div>
