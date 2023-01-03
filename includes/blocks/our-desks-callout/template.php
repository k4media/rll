<?php

     $title    = "";
     $subtitle = "";
     $desks    = array();

     // get fields
     if ( function_exists('get_fields') ) {
          $title = get_field('title');
          $subtitle = get_field('subtitle');
     }

     $pages = dfdl_get_desks();

     foreach( $pages as $pid ) {

          $page_title = get_the_title($pid);
          $page_slug  = sanitize_title($page_title);

          $icon = get_stylesheet_directory_uri() . '/assets/media/di-missing.svg';
          if ( function_exists('get_fields') ) {
               $flag = get_field('flag', $pid);
               $icon = ( isset($flag['url']) ) ? $flag['url'] : get_stylesheet_directory_uri() . '/assets/media/di-missing.svg';
          }

          $desk = '<div class="desk ' . $page_slug . ' ">';
          $desk .= '<a href="' . get_permalink($pid) . '">';
          $desk .= '<img src="' . $icon . '">';
          $desk .= $page_title;
          $desk .= '</a>';
          $desk .= '</div>';
          $desks[] = $desk;
     }

?>
<div class="our-desks-callout-stage callout">
     <div class="our-desks-callout silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="our-desks stage"><?php echo implode($desks) ?></div>
     </div>
     <a class="button green ghost" href="<?php echo get_permalink(get_page_by_path( 'desks' )) ?>">Learn More</a>
</div>
