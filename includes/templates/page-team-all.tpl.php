<?php
/**
 * The template for Team Members
 */
 get_header();
            
/**
 * All Members
 */
$args = array(
    'number'  => get_option('posts_per_page'),
    'role'    => array('dfdl_member'),
    'orderby' => array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' )
);

/**
 * Sort keys
 */
$args['meta_query'] = array(
    'relation' => 'AND',
    'dfdl_rank' => array(
        'key'   => '_dfdl_member_rank',
        'compare' => 'EXISTS'
    ),
    'last_name' => array(
        'key'   => 'last_name',
        'compare' => 'EXISTS'
    ),
);

// The Query
$user_query = new WP_User_Query($args);

?>
<div id="team-all" class="silo">
    <?php do_action("dfdl_solutions_country_nav"); ?>
    <input type="hidden" id="ajax_count" name="ajax_count" value="<?php echo get_option('posts_per_page') ?>">
    <input type="hidden" id="teams_all_page" name="teams_all_page" value="1">
    <?php if ( isset($GLOBALS['wp_query']->query_vars['dfdl_country'])) : ?>
          <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" />
     <?php else: ?>
          <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="" />
     <?php endif; ?>
    <div id="results_stage" class="team-stage all">
        <div id="team-grid-swiper">
            <?php
                // The Loop
                if ( ! empty( $user_query->get_results() ) ) {

                    ob_start();
                    foreach( $user_query->get_results() as $user ) {
                         set_query_var("user", $user);
                         get_template_part( 'includes/template-parts/content/member' );
                    }
                    $slides = ob_get_clean();
    
                    ob_start();
                         get_template_part( 'includes/template-parts/content/swiper', 'team-callout' );
                    $template = ob_get_clean();
                    $template = str_replace("{posts}", $slides, $template);
    
                    echo $template;
    
               } else {
                    echo '<div class="no-team-members not-found"><p>No Team Members Found.</p></div>';
               }
               
            ?>
            <?php if ( $user_query->get_total() > count($user_query->results)) : ?>
                <div class="see-more">
                    <button id="teams-all-see-more" class="button green ghost see-more">See More<span></span></button>
                </div>
            <?php endif; ?>
        </div>
        
    </div>
    
</div>

<?php
    /**
     * Show reusable "contact" block, ID 50588
     */
     do_action("dfdl_reusable_block", 50588);
?>

<?php get_footer();