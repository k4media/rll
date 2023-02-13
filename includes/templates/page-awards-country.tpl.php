<?php
/**
 * The template for awards/country
 */
 get_header();

/**
 * Cache results
 */
$key = "dfdl-awards-" . $GLOBALS['wp_query']->query_vars['dfdl_country'];
$K4 = new K4;
$K4->fragment_cache( $key, function() { 
    $awards  = dfdl_get_awards(array('country'=>$GLOBALS['wp_query']->query_vars['dfdl_country']));
    $class = ( ! isset($awards) || "" === $awards ) ? "no-results" : "" ;
?>
<input type="hidden" id="dfdl_award_country" name="dfdl_award_country" value="<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" />
<div id="award-grid-stage" class="awards-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" >
    <div class="award-grid silo">
        <?php do_action("dfdl_solutions_country_nav"); ?>
        <div id="results_stage" class="award-stage <?php echo $class ?>">
            <div>
                <?php
                if ( isset($awards) && "" !== $awards ) {
                    echo $awards ;
                } else {
                    echo '<p class="no-awards not-found">No awards just yet</p>';
                }
            ?>
            </div>
        </div>
    </div>
</div>
<?php }); // close K4 fragment ?>

<?php get_footer();
