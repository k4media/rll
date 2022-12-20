<?php

     $height = "536";
     $title  = "";
     $image  = "";
     $class  = "";

     // get fields via acf
     if ( function_exists('get_field') ) {
          $title = get_field('title');
          $image = get_field('image');
     }
     if ( is_front_page() ) {
          $height = "844";
          $class  = "front-page";
     }

     // validate fields
     if ( "" === $title ) {
          $title = $post->post_title;
     }

?>
<div id="page-hero" class="<?php echo $class ?>" style="background-image:url(<?php if ( isset($image['url']) ) { echo $image['url']; } ?>)">
     <div class="fp-hero-title"><h2><?php echo $title ?></h2></div>
</div>
