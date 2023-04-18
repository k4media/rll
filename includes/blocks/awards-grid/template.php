<?php

/**
 * Cache results
 *
 */
$key = "dfdl-awards";
$K4 = new K4;
$K4->fragment_cache( $key, function() { 
      $awards = dfdl_get_awards();
?>
<div class="award-grid-stage">
     <div class="award-grid silo">
          <div id="beacon"></div>
          <div id="subnav-stage"><?php do_action("dfdl_solutions_country_nav") ?></div>
          <div id="results_stage" class="award-stage">
               <div>
                    <?php echo $awards; ?>
               </div>
          </div>
     </div>
</div>
<?php }); // close K4 fragment ?>