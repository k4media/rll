<?php

     $title       = "";
     $text        = "";
     $button_text = "";
     $button_link = "";
     $image       = "";

     // get fields
     if ( function_exists('get_fields') ) {
          $title       = get_field('title');
          $text        = get_field('text');
          $button_text = get_field('btext');
          $button_link = get_field('link');
          $image       = get_field('image');
     }
?>
<div class="photo-feature-stage">
     <div class="photo-feature">
          <?php if ( isset($image['url']) ) : ?>
               <div class="image"><img src="<?php echo $image['url'] ?>"></div>
          <?php endif; ?>
          <div class="copy-stage">
               <div class="copy">
                    <?php if ( isset($title) ) : ?>
                         <h2><?php echo $title ?></h2>
                    <?php endif; ?>
                    <?php if ( isset($text) ) : ?>
                         <p><?php echo $text ?></p>
                    <?php endif; ?>
                    <?php if ( isset($button_link, $button_text) ) : ?>
                         <a class="button green solid" href="<?php echo esc_url($button_link) ?>"><?php echo esc_attr($button_text) ?></a>
                    <?php endif; ?>
               </div>
          </div>
     </div>
</div>