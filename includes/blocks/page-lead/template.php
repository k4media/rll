<?php

$K4  = new K4;
$key = $K4->cache_key("block-page-lead");
$K4->fragment_cache( $key, function() {

     $lead = null;
     $text = null;

     // get fields via acf
     if ( function_exists('get_field') ) {
          $lead   = get_field('lead');
          $text   = get_field('text');
     }
?>
<div id="page-lead">
     <div class="narrow">
          <?php if( isset($lead) ) : ?>
               <div class="lead"><?php echo apply_filters( 'the_content', wp_kses_post( $lead ) ); ?></div>
          <?php endif; ?>
          <?php if( isset($text) ) : ?>
               <?php echo apply_filters( 'the_content', wp_kses_post( $text ) ); ?>
          <?php endif; ?>
     </div>
</div>
<?php }); // close K4 fragment ?>