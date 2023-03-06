<?php

$K4  = new K4;
$key = $K4->cache_key("block-page-hero");
$K4->fragment_cache( $key, function() {

     global $post;

     $title   = "";
     $image   = "";
     $class   = "";
     $overlay = "";
     $size    = "";

     // get fields via acf
     if ( function_exists('get_field') ) {
          $title   = get_field('title');
          $image   = get_field('image');
          $overlay = get_field('overlay');
          $size    = get_field('size');
     }

     // set css classes
     $class = ( is_front_page() ) ? "front-page" : "page" ;

     // hero image height
     if ( isset($size) ) {
          $class .= ( "landing page" === strtolower($size) ) ? " landing " : "" ;
     }

     $sections = dfdl_get_section() ;
     if ( isset($sections[0]) && "solutions" === $sections[0]) {
          $class .= " solutions ";
     }
     
     // validate fields
     if ( "Page Hero Title" === $title ) {
          $title = $post->post_title;
     }

?>
<div id="page-hero" class="hero <?php echo $class ?>" style="background-image:url(<?php if ( isset($image['url']) ) { echo $image['url']; } ?>)">
     <?php if( isset($overlay) ) : ?>
          <div class="overlay" style="background-color:<?php echo $overlay ?>"></div>
     <?php endif; ?>
     <div class="stage silo">
          <nav class="subnav-stage">
               <ul>
                    <li class="back"><a href="<?php echo get_home_url(null, '/solutions/') ?>">Back</a></li>
               </ul>
          </nav>
          <h2><?php echo $title ?></h2>
     </div>
     
</div>
<?php }); // close K4 fragment ?>