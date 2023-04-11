<?php

     global $post;

     $user    = "";
     $section = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $user = ( get_field('user') ) ? get_field('user') : 1 ;
          $user = get_user_by("id", $user);
          $meta = get_user_meta($user->ID);
          $locations   = array();
          if ( isset($meta['_dfdl_user_country']) ) {
               foreach( $meta['_dfdl_user_country'] as $c ) {
                    $country = get_term( $c, 'dfdl_countries', true);
                    $locations[] = $country->name;
               }
          }
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
<div class="team-member-stage callout">
     <div class="team-member narrow">
          <div class="team-member dfdl-single-member">
               <div class="avatar"><a href="<?php echo get_author_posts_url($user->ID) ?>"><img src="<?php echo get_avatar_url($user->ID, array('size' => 320)) ?>"></a></div>
               <div class="details-stage">
                    <div class="member">
                         <div class="slug">
                              <?php if ($post->post_title): ?>
                                   <?php echo esc_attr($post->post_title); ?>
                              <?php endif; ?>
                              Key Contact
                         </div>
                         <div class="name"><a href="<?php echo get_author_posts_url($user->ID) ?>"><?php echo $user->display_name ?></a></div>
                         <?php if( isset($meta['position'][0]) ) : ?>
                              <div class="position"><?php echo $meta['position'][0] ?></div> 
                         <?php endif; ?>
                         <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                              <div class="location"><?php echo implode(", ", $locations) ?></div>
                         <?php endif; ?>
                         <div class="contact-details <?php echo $contact_align ?>">
                              <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) && $meta['tel'][0] !== "") : ?>
                                   <div class="telephone"><?php echo $meta['tel'][0] ?></div>
                              <?php endif; ?>
                              <?php 
                              if ( isset($user->user_email) && ! empty($user->user_email) && $user->user_email !== "") : ?>
                                   <div class="email"><a href="mailto:<?php echo $user->user_email ?>">Email</a></div>
                              <?php endif; ?>
                              <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) && $meta['linkedin'][0] !== "") : ?>
                                   <div class="linkedin"><a href="<?php echo $meta['linkedin'][0] ?>">LinkedIn</a></div>
                              <?php endif; ?>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
