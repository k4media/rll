<?php

/**
 * Cache results
 *
 */
$key = "dfdl-awards-callout-" . md5(get_permalink()) ;
$K4 = new K4;
$K4->fragment_cache( $key, function() { 

     global $post;

     if ( is_admin() ) {
          $args = array(
               'country' => $post->post_name
          );
     } else {
          $sections = dfdl_get_section();
          if ( "locations" === $sections[0]) {
               $args = array(
                    'country' => $sections[1]
               );
          } elseif ( "solutions" === $sections[0] ) {
               $args = array(
                    'solutions' => array($sections[1])
               );
          }
     }

     $awards = dfdl_get_awards($args);
     if ( "" !== $awards ) :
?>
<div class="awards-callout-stage callout">
     <div class="award-grid-stage">
          <div class="award-grid narrow">
               <h2>Awards</h2>
               <?php 
                    if ( ! is_admin() ) {
                         do_action("dfdl_solutions_country_nav");
                    }
               ?>
               <div id="results_stage" class="award-stage">
                    <div><?php echo $awards; ?></div>
               </div>
          </div>
     </div>
</div>
<?php else: ?>
     <?php if ( is_admin() ) : ?>
          <div class="award-grid narrow">
               <h3 class="title">Awards</h3>
               <p style='font-color: #7d7d7d'>This country does not yet have awards defined.</p>
          </div>
     <?php endif; ?>    
<?php endif; ?>
<?php }); // close K4 fragment ?>