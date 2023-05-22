<?php

     $user     = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $title    = get_field('title');
          //$subtitle = get_field('subtitle');
          $user     = ( get_field('user') ) ? get_field('user') : 1 ;
          $user     = get_user_by("id", $user);
          $meta     = get_user_meta($user->ID);
          $locations = array();
          if ( isset($meta['_dfdl_user_country']) ) {
               foreach( $meta['_dfdl_user_country'] as $c ) {
                    $country = get_term( $c, 'dfdl_countries', true);
                    $locations[] = $country->name;
               }
          }
     }

     if ( empty($title) ) {
          $title = "Key Contact";
     }

     $section = dfdl_get_section();
     $style   = "";
     if ( isset($section) ) {
          $style = $section[0];
     }

     /*
      * Count user details.
      * If 2, align-left. If 3, justify.
      */
     $counter = 0;
     if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) {
          $counter++; 
     }
     if ( isset($user->user_email) && ! empty($user->user_email) ) {
          $counter++; 
     }
     if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) {
          $counter++; 
     }
     $contact_align = ( $counter > 2 ) ? "full" : "partial" ;

?>
<div class="team-lead-stage callout <?php echo $style; ?>">
     <div class="team-lead narrow">
          <h2><?php echo $title ?></h2>
          <?php if ( isset($subtitle) ) : ?>
               <h3><?php echo $subtitle ?></h3>
          <?php endif; ?>
          
          <div class="lead-team-member dfdl-single-member">
               <div class="avatar"><a href="<?php echo get_author_posts_url($user->ID) ?>"><img src="<?php echo get_avatar_url($user->ID, array('size' => 320)) ?>"></a></div>
               <div class="details-stage">
                    <div class="member">
                         <!--<div class="slug">Regional Key Contact</div>-->
                         <div class="name"><a href="<?php echo get_author_posts_url($user->ID) ?>"><?php echo $user->display_name ?></a></div>
                         <?php if( isset($meta['position'][0]) ) : ?>
                              <div class="position"><?php echo $meta['position'][0] ?></div> 
                         <?php endif; ?>
                         <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                              <div class="location"><?php echo implode(", ", $locations) ?></div>
                         <?php endif; ?>
                         <div class="contact-details <?php echo $contact_align ?>">
                              <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) && $meta['tel'][0] !== "" ) : ?>
                                   <div class="telephone"><?php echo $meta['tel'][0] ?></div>
                              <?php endif; ?>
                              <?php if ( isset($user->user_email) && ! empty($user->user_email) && $user->user_email !== "" ) : ?>
                                   <a href="mailto:<?php echo $user->user_email ?>"><div class="email">Email</div></a>
                              <?php endif; ?>
                              <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) && $meta['linkedin'][0] !== "" ) : ?>
                                   <a href="<?php echo $meta['linkedin'][0] ?>"><div class="linkedin">LinkedIn</div></a>
                              <?php endif; ?>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
