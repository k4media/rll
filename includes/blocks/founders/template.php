<?php
     $title      = null;
     $subtitle   = null;
     $founders   = null;
     if ( function_exists('get_field') ) {
          $title      = get_field('title');
          $subtitle   = get_field('subtitle');
          $founders   = get_field('founders');
     }
?>
<div id="our-founders" class="our-founders-stage callout">
     <div class="our-founders silo">
          <h2><?php echo $title ?></h2>
          <?php if ( isset($subtitle) && ! empty($subtitle) && null !== $subtitle ) : ?>
               <h3><?php echo $subtitle ?></h3>
          <?php endif; ?>
          <div class="founders-stage">
          <?php 
               if ( isset($founders) ) {
                    foreach( $founders as $founder ) {
                         set_query_var("user", $founder);
                         get_template_part( 'includes/template-parts/content/founder' );
                    }
               }
          ?>
          </div>
     </div>
</div>
