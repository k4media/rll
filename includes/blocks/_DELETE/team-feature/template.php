<?php

     // get users...
     
     $args = array(
          'number'        => 16
     );
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
<div class="team-feature-stage">
     <div class="team-feature silo">
          <?php do_action("dfdl_solutions_country_nav"); ?>
          <div class="team-stage"><?php echo implode($output) ?></div>
          <a class="button green ghost" href="<?php echo get_home_url(null, 'teams/all/') ?>">See All</a>
     </div>
</div>
