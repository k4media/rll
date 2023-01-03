<?php

function desks_navigation() {

     global $wp;

     $pieces   = explode("/", $wp->request ) ;
     $desks    = dfdl_get_desks();
     $nav      = array();

     foreach($desks as $did) {

          /*
          * Page title will include the word "desk", 
          * Ex: China Desk
          * Strip the "desk" part for navigation
          */
          $page_title = get_the_title($did);
          $nav_title  = explode( " ", $page_title );
          $nav_title  = $nav_title[0];
          $page_slug  = get_post_field( 'post_name', $did );

          if ( is_admin() ) {
               $nav[] = '<li><a href="#">' . $page_title . '</a></li>' ;
          } else {
               if ( in_array(strtolower($nav_title), $pieces)  ) {
                    $nav[] = '<li><a class="current-menu-item" href="' . get_permalink($did) . '">' . $nav_title . '</a></li>' ;
               } else {
                    $nav[] = '<li><a href="' . get_permalink($did) . '">' . $nav_title . '</a></li>' ;
               }
          }

     }

     echo '<nav class="country-subnav-stage desks-subnav"><ul>';
     echo implode($nav);
     echo '</ul></nav>';

}

desks_navigation();

?>

