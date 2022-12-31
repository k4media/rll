<?php

     $lead = null;
     $text = null;

     // get fields via acf
     if ( function_exists('get_field') ) {
          $lead   = get_field('lead');
          $text   = get_field('text');
     }
?>
<div id="page-lead">
     <div class="narrow">
          <?php if( isset($lead) ) : ?>
               <div class="lead"><?php echo apply_filters( 'the_content', wp_kses_post( $lead ) ); ?></div>
          <?php endif; ?>
          <?php if( isset($text) ) : ?>
               <?php echo apply_filters( 'the_content', wp_kses_post( $text ) ); ?>
          <?php endif; ?>
     </div>
</div>
