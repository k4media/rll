<?php

     $title     = "";
     $subtitle  = "";
     $solutions = array();

     // get fields
     if ( function_exists('get_fields') ) {
          $title = get_field('title');
          $subtitle = get_field('subtitle');
     }

     // get solutions pages
     $page      = get_page_by_path( 'solutions' );
     $args = array(
          'post_type'      => 'page',
          'posts_per_page' => -1,
          'child_of'       => $page->ID,
          'order'          => 'ASC',
          'orderby'        => 'menu_order'
     );
     $pages = get_pages($args);
     foreach( $pages as $p ) {
          $solution = '<div class="solution ' . $p->post_title . ' ">';
          $icon = get_stylesheet_directory() . '/assets/media/si-' . $p->post_name . '.svg';
          $solution .= ( file_exists($icon) ) ? '<img src="' . get_stylesheet_directory_uri() . '/assets/media/si-' . $p->post_name . '.svg' . '">' : '<img src="' . get_stylesheet_directory_uri() . '/assets/media/si-missing.svg">' ;
          $solution .= '<a href="' . get_permalink($p->ID) . '">'  ;
          $solution .= $p->post_title;
          $solution .= '</a>';
          $solution .= '</div>';
          $solutions[] = $solution;
     }

?>
<div class="solutions-callout-stage callout">
     <div class="solutions-callout silo">
          <h2><?php echo $title ?></h2>
          <h3><?php echo $subtitle ?></h3>
          <div class="solutions stage"><?php echo implode($solutions) ?></div>
     </div>
     <a class="button green ghost" href="<?php echo get_permalink(get_page_by_path( 'solutions' )) ?>">Learn More</a>
</div>
