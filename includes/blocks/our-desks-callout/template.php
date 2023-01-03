<?php

     $title    = "";
     $subtitle = "";
     $desks    = array();

     // get fields
     if ( function_exists('get_fields') ) {
          $title = get_field('title');
          $subtitle = get_field('subtitle');
     }

     $title = ( "" === $title) ? "Our Desks" : $title ;
     $subtitle = ( "" ===  $subtitle ) ? "" : $subtitle ;

     // get desks
     $page = get_page_by_path( 'desks' );
     $args = array(
          'post_type'      => 'page',
          'posts_per_page' => -1,
          'child_of'       => $page->ID,
          'order'          => 'ASC',
          'orderby'        => 'menu_order',
          'no_found_rows'          => true,
          'update_post_meta_cache' => false, 
          'update_post_term_cache' => false,
          'fields'                 => 'ids'
     );
     $pages = get_pages($args);
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
