<?php
/**
 * The template for Country Team Members
 */
 get_header();

    global $wp;
            
    /**
     * country/team
     */
    $args = array(
        'number'    => -1,
        'role__in ' => array('contributor', 'author', 'editor', 'admin', 'dfdl_member'),
        'orderby'   => 'user_nicename',
        'order'     => 'ASC',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false, 
	    'update_post_term_cache' => false,
    );

    if ( isset($GLOBALS['wp_query']->query_vars['dfdl_country']) ) {
        $term = get_term_by('slug', $GLOBALS['wp_query']->query_vars['dfdl_country'], 'dfdl_countries');
        $args['meta_key'] = '_dfdl_user_country';
        $args['meta_value'] = $term->term_id;
    }

    // The Query
    $user_query = new WP_User_Query( $args );

?>
<div id="team-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" class="teams-country-stage">
    <?php do_action("dfdl_solutions_country_nav"); ?>
    <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" />
    <div id="results_stage" class="team-stage <?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?> silo">
        <div>
            <?php
                // The Loop
                if ( ! empty( $user_query->get_results() ) ) {
                    foreach ( $user_query->get_results() as $user ) {
                        set_query_var("user", $user);
                        get_template_part( 'includes/template-parts/content/member' );
                    }
                } else {
                    echo '<div class="no-team-members not-found"><p>No Team Members Found.</p></div>';
                }
            ?>
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
