<?php

     // get solutions pages
     $page = get_page_by_path( 'desks' );
     $args = array(
          'post_type'      => 'page',
          'posts_per_page' => 16,
          'child_of'       => $page->ID,
          'order'          => 'ASC',
          'orderby'        => 'menu_order',
          'no_found_rows'          => true,
          'ignore_sticky_posts'    => true,
          'update_post_meta_cache' => false, 
          'update_post_term_cache' => false,
     );
     $pages = get_pages($args);

     foreach( $pages as $post ) {

          // image
          $image   = get_block_data($post, 'acf/dfdl-page-hero', 'image');
          $image   = wp_get_attachment_image_url($image, 'medium');

          // overlay
          $overlay = get_block_data($post, 'acf/dfdl-page-hero', 'overlay');

          // output
          
          $solution = '<a href="' . get_permalink($post->ID) . '">'  ;
          $solution .= '<div class="solution ' . sanitize_title($post->post_title). ' ">';

               // image thumbnail
               $solution .= '<div class="thumbnail" style="background-image:url(' . $image . ')">';
               $solution .= '<div class="overlay" style="background-color:' . $overlay . '"></div>';
               $solution .= '</div>';

          $solution .= "<h3>" . esc_attr($post->post_title) . "</h3>";
          $solution .= '</div>';
          $solution .= '</a>';
          $solutions[] = $solution;
     }

?>
<div class="solutions-grid-stage">
     <div class="solutions-grid silo">
          <div class="solutions stage"><?php echo implode($solutions) ?></div>
     </div>
</div>
