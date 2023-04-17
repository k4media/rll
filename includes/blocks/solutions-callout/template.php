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
     $page = get_page_by_path( 'solutions' );
     $args = array(
          'post_type'      => 'page',
          'posts_per_page' => -1,
          'child_of'       => $page->ID,
          'order'          => 'ASC',
          'orderby'        => 'menu_order',
          'no_found_rows'          => true,
          'ignore_sticky_posts'    => true,
          'update_post_meta_cache' => false, 
          'update_post_term_cache' => false,
          'fields'                 => 'ids'
     );
     $pages = get_pages($args);
     
     // this function doesn't work. why??
     // $pages = dfdl_get_solutions();

     foreach( $pages as $pid ) {

          $page_title = get_the_title($pid);
          $page_slug  = sanitize_title($page_title);
          $solution = '<div class="solution ' . $page_slug . ' ">';
          $solution .= '<a href="' . get_permalink($pid) . '">'  ;
          $solution .= '<span>&#x2022;</span>' . $page_title;
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
