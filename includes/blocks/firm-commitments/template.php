<?php
     $title    = null;
     $subtitle = null;
     $output   = array();
     $counter  = 0;
     if ( function_exists('get_fields') ) {
          $title       = get_field('title');
          $subtitle    = get_field('subtitle');
          $commitments = get_field('commitments');
          foreach ( $commitments as $c ) {
               $counter++;
               $output[] = '<div class="commitment commitment' . $counter . '">';
               $output[] = '<h2 class="title">' . $c['title'] . '</h2>';
               $output[] = '<div class="excerpt">' . nl2br($c['text']). '</div>';
               $output[] = '</div>';
          }
     }
?>
<div id="firm-commitments" class="callout">
     <div class="narrow">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="commitments-stage">
               <?php echo implode ($output); ?>
          </div>
     </div>
</div>