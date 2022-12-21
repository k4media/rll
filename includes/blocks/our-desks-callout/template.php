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
          'orderby'        => 'menu_order'
     );
     $pages = get_pages($args);
     foreach( $pages as $p ) {
          $desk = '<div class="desk ' . $p->post_title . ' ">';
          $desk .= '<a href="' . get_permalink($p->ID) . '">'  ;
          $icon = get_stylesheet_directory() . '/assets/media/di-' . $p->post_name . '.svg';
          $desk .= ( file_exists($icon) ) ? '<img src="' . get_stylesheet_directory_uri() . '/assets/media/si-' . $p->post_name . '.svg' . '">' : '<img src="' . get_stylesheet_directory_uri() . '/assets/media/di-missing.svg">' ;
          $desk .= $p->post_title;
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
