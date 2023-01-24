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
          $post_title = get_the_title($c);
          $post_slug = sanitize_title($post_title);
          $output[] = '<li class="country-' . $post_slug . ' country">';
          $output[] = '<a href="' . get_permalink($c) . '">'  ;
          $output[] = $post_title;
          $output[] = '</a>';
          $output[] = '</li>';
     }
?>
<div class="dfdl-countries-stage callout">
     <div class="dfdl-countries silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="stage">
               <div class="map">
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/dfdl-map.svg">
               </div>
               <div class="countries">
                    <ul><?php echo implode($output) ?></ul>
               </div>
          </div>
          
     </div>
</div>
