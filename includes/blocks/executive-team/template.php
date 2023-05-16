<?php
     $title      = null;
     $executives = null;
     $et_title   = null;

     if ( function_exists('get_field') ) {
          $title      = get_field('title');
          $executives = get_field('team');
          $output     = array();
     }
     if ( empty($et_title) || null === $et_title ) {
          $et_title = "Regional Executive Team";
     }
?>
<div id="dfdl-ret" class="callout">
     <div class="silo">
          <?php if ( isset($title) && ! empty($title) && null !== $et_title ) : ?>
               <h4><?php echo $title ?></h4>
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
