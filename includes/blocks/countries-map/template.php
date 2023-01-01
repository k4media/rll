<?php

     $title     = "";
     $subtitle  = "";
     $countries = array();
     $output    = array();

     // get fields
     if ( function_exists('get_fields') ) {
          $title = get_field('title');
          $subtitle = get_field('subtitle');
     }

     // get countires
     $countries = dfdl_get_countries();

     foreach( $countries as $c ) {
          $output[] = '<li class="country-' . $c->post_title . ' country">';
          $output[] = '<a href="' . get_permalink($c->ID) . '">'  ;
          $output[] = $c->post_title;
          $output[] = '</a>';
          $output[] = '</li>';
     }

?>
<div class="dfdl-countries-stage callout">
     <div class="our-countries silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="countries stage"><ul><?php echo implode($output) ?></ul></div>
     </div>
</div>
