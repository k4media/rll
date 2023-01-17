<?php

     $values  = null;
     $output  = array();
     $counter = 0;

     // get fields via acf
     if ( function_exists('get_field') ) {
          $values   = get_field('values');
          foreach ( $values as $v ) {
               $counter++;
               $output[] = '<div class="col col' . $counter . '">';
               $output[] = '<h2 class="title">' . $v['title'] . '</h2>';
               $output[] = '<div class="excerpt">' . $v['text']. '</div>';
               $output[]= '</div>';
          }
     }

?>
<div id="firm-values">
     <div class="narrow">
          <?php echo implode($output); ?>
     </div>
</div>
