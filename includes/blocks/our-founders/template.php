<?php

     $title    = null;
     $subtitle = null;
     $founders = null;

     // get fields via acf
     if ( function_exists('get_field') ) {

          $title    = get_field('title');
          $subtitle = get_field('subtitle');
          $founders = get_field('founders');

          $output   = array();

          foreach( $founders as $f ) {

               $position    = get_user_meta( $f['founder']['ID'], 'position', true);

               $locations   = array();
               $country_ids = get_user_meta( $f['founder']['ID'], '_dfdl_user_country'); 
               foreach( $country_ids as $c ) {
                    $country = get_term( $c, 'dfdl_countries', true);
                    $locations[] = $country->name;
               }
               
               $output[] = '<div class="founder">';
                    $output[] = '<img src="' . get_avatar_url($f['founder']['ID'], array('size' => 320)) . '">';
                    $output[] = '<div class="details-stage"><div class="details">';
                         $output[] = '<div class="name">' . $f['founder']['display_name']. '</div>';
                         if (isset($position)) {
                              $output[] = '<div class="position">' . $position . '</div>'; 
                         }
                         if ( count($locations) > 0 ) {
                              $output[] = '<div class="location">' . implode(", ", $locations) . '</div>'; 
                         }
                    $output[] = '</div></div>';
               $output[] = '</div>';
          }

     }
?>
<div id="our-founders" class="our-founders-stage callout">
     <div class="our-founders silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="founders stage"><?php echo implode("", $output) ?></div>
     </div>
</div>
