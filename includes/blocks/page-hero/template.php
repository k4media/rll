<?php

     $height = "536";
     $title  = "";
     $image  = "";
     $class  = "";

     if ( function_exists('get_field') ) {
          $title = get_field('title');
          $image = get_field('image');
     }
     if ( is_front_page() ) {
          $height = "844";
          $class  = "front-page";
     }

?>

<div id="page-hero" class="<?php echo $class ?>" style="background-image:url(<?php echo $image['url'] ?>)">
     <div class="title"><h2><?php echo $title ?></h2></div>
</div>
