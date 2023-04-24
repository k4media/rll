<?php
     $name  = "";
     $desc  = "";
     $image = "";
     $url   = "";
     if ( function_exists('get_fields') ) {
          $name  = get_field('name');
          $desc  = get_field('description');
          $image = get_field('image');
          $url   = get_field('url');
     }
?>
<div class="network-partner">
     <div class="brand">
          <?php if ( isset($image['url']) ) : ?>
               <div class="logo"><img src="<?php echo $image['url'] ?>"></div>
          <?php endif; ?>
          <?php if ( isset($url) ) : ?>
               <div class="url">
                    <a href="<?php echo $url ?>">View Website</a>
               </div>
          <?php endif; ?>
     </div>
     <div class="details">
          <?php if ( isset($name) ) : ?>
               <h3 class="name">
               <?php if ( isset($url) ) : ?>
                    <a href="<?php echo $url ?>"><?php echo $name ?></a>
               <?php else : ?>
                    <?php echo $name ?>
               <?php endif; ?>
               </h3>
          <?php endif; ?>
          <?php if ( isset($desc) ) : ?>
               <?php echo nl2br($desc) ?>
          <?php endif; ?>
     </div>
</div>