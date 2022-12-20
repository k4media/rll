<?php

     // https://github.com/DarioCorno/counterUp
     // wp_enqueue_script('countup', get_stylesheet_directory_uri() . '/assets/js/countup.js', array(), false, true );

     // get fields
     if ( function_exists('get_fields') ) {
          $rows = get_field('box');
          if($rows) {
               $boxes = array();
               $i = 1;
               foreach( $rows as $row ) {
                    $boxes[$i]['number'] = $row['number'];
                    $boxes[$i]['label']  = $row['label'];
                    $i++;
               }
          }
          $text = get_field('text');
     } 

     // default values
     if ( ! isset($boxes) ) {
          $boxes = array();
          $i = 1;
          while ($i < 5) {
               $boxes[$i]['number'] = 999;
               $boxes[$i]['label'] = 'lorum ipsum';
               $i++;
          }
     }

?>
<div class="countup-box-stage" >
     <div id="countup-boxes" class="countup-boxes silo">
          <div class="box box-1">
               <h4 class="countup" cup-end="<?php echo $boxes[1]['number'] ?>"><?php echo $boxes[1]['number'] ?></h4>
               <div class="label"><?php echo $boxes[1]['label'] ?></div>
          </div>
          <div class="box box-2">
               <h4 class="countup" cup-end="<?php echo $boxes[2]['number'] ?>"><?php echo $boxes[2]['number'] ?></h4>
               <div class="label"><?php echo $boxes[2]['label'] ?></div>
          </div>
          <div class="box box-3">
               <h4 class="countup" cup-end="<?php echo $boxes[3]['number'] ?>"><?php echo $boxes[3]['number'] ?></h4>
               <div class="label"><?php echo $boxes[3]['label'] ?></div>
          </div>
          <div class="box box-4">
               <h4 class="countup" cup-end="<?php echo $boxes[4]['number'] ?>"><?php echo $boxes[4]['number'] ?></h4>
               <div class="label"><?php echo $boxes[4]['label'] ?></div>
          </div>
     </div>
     <?php if (isset($text)) : ?>
          <div class="countup-text silo"><?php echo $text ?></div>
     <?php endif; ?>
</div>
<script src='<?php  echo get_stylesheet_directory_uri() ?>/assets/js/countup.js' id='countup'></script>
<script>
     var cu = new counterUp({
        selector: '.countup',
        duration: 1000,
        intvalues: true,
        prepend: '',
        append: '',
        interval:50
    });
    var stage = document.getElementById("countup-boxes");
     window.onscroll = function() {
          if ( isScrolledIntoView(stage) ) {
               cu.start();
          }
     };
</script>
