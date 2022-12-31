<?php

     $title = null;
     $lcol = null;
     $rcol = null;

     // get fields via acf
     if ( function_exists('get_field') ) {
          $title   = get_field('title');
          $lcol   = get_field('lcol');
          $rcol   = get_field('rcol');
     }
?>
<div class="text-feature-stage">
     <div class="text-feature narrow">
          <h3 class="title"><?php echo $title ?></h3>
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
