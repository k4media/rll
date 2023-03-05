<?php

     $title    = "";
     $subtitle = "";
     $user     = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $title    = get_field('title');
          $subtitle = get_field('subtitle');
          $user     = ( get_field('user') ) ? get_field('user') : 1 ;

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

?>
<div class="team-lead-stage callout">
     <div class="team-lead narrow">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="lead-team-member dfdl-single-member">
               <div class="avatar"><a href="<?php echo get_author_posts_url($user->ID) ?>"><img src="<?php echo get_avatar_url($user->ID, array('size' => 320)) ?>"></a></div>
               <div class="details-stage">
                    <div class="member">
                         <div class="slug">Regional Key Contact</div>
                         <div class="name"><?php echo $user->display_name ?></div>
                         <?php if( isset($meta['position'][0]) ) : ?>
                              <div class="position"><?php echo $meta['position'][0] ?></div> 
                         <?php endif; ?>
                         <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                              <div class="location"><?php echo implode(", ", $locations) ?></div>
                         <?php endif; ?>
                         <?php if( $user->user_description ) : ?>
                              <div class="bio"><?php echo nl2br($user->user_description) ?></div>
                         <?php endif; ?>
                         <div class="contact-details">
                              <div class="telephone">
                                   <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) : ?>
                                        <?php echo $meta['tel'][0] ?>
                                   <?php endif; ?>
                              </div>
                              <div class="mobile">
                                   <?php if ( isset($meta['mob']) && ! empty($meta['mob'][0]) ) : ?>
                                        <?php echo $meta['mob'][0] ?>
                                   <?php endif; ?>
                              </div>
                              <div class="email">
                                   <?php if ( isset($meta['email']) && ! empty($meta['email'][0]) ) : ?>
                                        <a href="mailto:<?php echo $meta['email'][0] ?>">Email</a>
                                   <?php endif; ?>
                              </div>
                              <div class="linkedin">
                                   <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) : ?>
                                        <a href="<?php echo $meta['linkedin'][0] ?>">LinkedIn</a>
                                   <?php endif; ?>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
