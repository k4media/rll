<?php

/**
 * Cache results
 *
 */
$key = "dfdl-awards";
$K4 = new K4;
//$K4->fragment_cache( $key, function() { 
      $awards = dfdl_get_awards();
?>
<div class="award-grid-stage">
     <div class="award-grid silo">
          <?php do_action("dfdl_solutions_country_nav") ?>
          <div id="results_stage" class="award-stage">
               <div>
                    <?php echo $awards; ?>
               </div>
          </div>
     </div>
</div>
<?php //}); // close K4 fragment ?>