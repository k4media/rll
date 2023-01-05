<?php

     $title    = null;
     $subtitle = null;
     $founders = null;

     // get fields via acf
     if ( function_exists('get_field') ) {
          $title    = get_field('title');
          $subtitle = get_field('subtitle');
          $founders = get_field('founders');
          $output   = array();
     }
?>
<div id="our-founders" class="our-founders-stage callout">
     <div class="our-founders silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="founders-stage">
          <?php 
               if ( isset($founders) ) {
                    foreach( $founders as $user ) {
                         set_query_var("user", $user);
                         get_template_part( 'includes/template-parts/content/founder' );
                    }
               }
          ?>
          </div>
     </div>
</div>
