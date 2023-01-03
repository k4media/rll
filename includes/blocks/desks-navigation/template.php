<?php

     /**
     * Desks, as determined from subpages
     */
    $desks = get_page_by_path("desks");
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $desks->ID,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
        'update_post_term_cache' => false,
        'fields'                 => 'ids'
     );
    $pages = new WP_Query( $args );

?>
<div class="awards-callout-stage callout">
     <div class="awards-callout narrow">
          <h2>Awards Placeholder</h2>
     </div>
</div>
