<?php
/**
 * The template for awards/country
 */
 get_header();

/**
 * Cache results
 *
 */
$key = "dfdl-awards-" . $GLOBALS['wp_query']->query_vars['dfdl_country'];
$K4 = new K4;
//$K4->fragment_cache( $key, function() { 
    $awards  = dfdl_get_awards( $GLOBALS['wp_query']->query_vars['dfdl_country'] );
?>
<div id="award-grid-stage awards-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" >
    <div class="award-grid silo">
        <?php do_action("dfdl_solutions_country_nav"); ?>
        <div class="award-stage">
            <?php
                if ( isset($awards) && "" !== $awards ) {
                    echo $awards;
                } else {
                    echo '<div class="no-awards not-found"><p>No awards just yet</p></div>';
                }
            ?>
        </div>
    </div>
</div>
<?php //}); // close K4 fragment ?>

<?php get_footer();
