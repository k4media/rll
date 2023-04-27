<?php
     $title      = null;
     $subtitle   = null;
     $founders   = null;
     $executives = null;
     $et_title   = null;

     if ( function_exists('get_field') ) {
          $title      = get_field('title');
          $subtitle   = get_field('subtitle');
          $founders   = get_field('founders');
          $et_title   = get_field('et_title');
          $executives = get_field('team');
          $output     = array();
     }
     if ( empty($et_title) || null === $et_title ) {
          $et_title = "Regional Executive Team";
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
          <?php if ( isset($et_title) && ! empty($et_title) && null !== $et_title ) : ?>
               <h4><?php echo $et_title ?></h4>
          <?php endif; ?>
          <div class="executives-stage">
          <?php 
               if ( isset($executives) ) {
                    foreach( $executives as $executive ) {
                         set_query_var("user", $executive);
                         get_template_part( 'includes/template-parts/content/executive' );
                    }
               }
          ?>
          </div>
     </div>
</div>
