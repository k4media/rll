<?php

     $title  = null;
     $lcol   = null;
     $rcol   = null;
     $cols   = null;
     $style  = "";

     if ( function_exists('get_field') ) {
          $title = get_field('title');
          $lcol  = get_field('lcol');
          $rcol  = get_field('rcol');
          $cols  = get_field('columns');
          $style = ( "1" === $cols ) ? "single-column" : "double-column" ;
     }
     
     $section = dfdl_get_section();
     if ( empty($section) ) {
          $section = array();
          $section[0] = "non";
     }

     
     
?>
<div class="text-feature-stage <?php echo $section[0] . ' ' . $style ?>">
     <div class="text-feature narrow">
          <?php if ( ! empty($title) ): ?>
               <h3 class="title"><?php echo $title ?></h3>
          <?php endif; ?>
          <div class="columns">
               <div class="lcol">
                    <?php echo apply_filters( 'the_content', wp_kses_post( $lcol ) ); ?>
               </div>
               <div class="rcol">
                    <?php echo apply_filters( 'the_content', wp_kses_post( $rcol ) ); ?>
               </div>
          </div>
     </div>
</div>
