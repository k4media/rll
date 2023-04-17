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
               $nav[] = '<li class="swiper-slide"><a class="current-menu-item" href="' . get_permalink($id) . '">' . $page_title . '</a></li>' ;
          } else {
               $nav[] = '<li class="swiper-slide"><a href="' . get_permalink($id) . '">' . $page_title . '</a></li>' ;
          }
     }

}



echo '<nav id="location-subnav" class="country-subnav-stage desks-subnav"><div class="subnav-swiper"><ul class="swiper-wrapper">';
echo implode($nav);
echo '</ul></div></nav>';


?>

