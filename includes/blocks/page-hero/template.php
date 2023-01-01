<?php

     global $post;

     $title   = "";
     $image   = "";
     $class   = "";
     $overlay = "";

     // get fields via acf
     if ( function_exists('get_field') ) {
          $title   = get_field('title');
          $image   = get_field('image');
          $overlay = get_field('overlay');
     }

     // set css class
     $class = ( is_front_page() ) ? "front-page" : "page" ;

     
     // validate fields
     if ( "Page Hero Title" === $title ) {
          $title = $post->post_title;
     }

?>
<div id="page-hero" class="hero <?php echo $class ?>" style="background-image:url(<?php if ( isset($image['url']) ) { echo $image['url']; } ?>)">
     
     <?php if( isset($overlay) ) : ?>
          <div class="overlay" style="background-color:<?php echo $overlay ?>"></div>
     <?php endif; ?>

     <div class="fp-hero-title"><h2><?php echo $title ?></h2></div>
</div>
