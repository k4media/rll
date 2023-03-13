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
          $style = ( "1" === $cols ) ? " single-column " : "" ;
     }
     
?>
<div class="text-feature-stage">
     <div class="text-feature narrow">
          <?php if ( ! empty($title) ) : ?>
               <h3 class="title"><?php echo $title ?></h3>
          <?php endif; ?>
          
          <div class="columns <?php echo $style ?>">
               <div class="lcol">
                    <?php echo apply_filters( 'the_content', wp_kses_post( $lcol ) ); ?>
               </div>
               <div class="rcol">
                    <?php echo apply_filters( 'the_content', wp_kses_post( $rcol ) ); ?>
               </div>
          </div>
     </div>
</div>
