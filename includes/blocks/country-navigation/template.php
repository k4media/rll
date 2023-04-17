<?php

global $wp;

$pieces    = explode("/", $wp->request ) ;
$countries = dfdl_get_countries();
$nav       = array();

foreach($countries as $id) {

     $page_title = get_the_title($id);
     $page_slug  = str_replace( " ", "-", strtolower($page_title) );

     if ( is_admin() ) {
          $nav[] = '<li><a href="#">' . $page_title . '</a></li>' ;
     } else {
          if ( in_array(strtolower($page_slug), $pieces)  ) {
               $nav[] = '<li><a class="current-menu-item" href="' . get_permalink($id) . '">' . $page_title . '</a></li>' ;
          } else {
               $nav[] = '<li><a href="' . get_permalink($id) . '">' . $page_title . '</a></li>' ;
          }
     }

}

echo '<nav class="country-subnav-stage desks-subnav"><ul>';
echo implode($nav);
echo '</ul></nav>';


?>

