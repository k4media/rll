<?php
/**
 * The template for Country Team Members
 */
 get_header();

global $wp;
            
/**
 * Country/team
 */
$args = array(
    'number'    => get_option('posts_per_page'),
    'role__in ' => array('contributor', 'author', 'editor', 'admin', 'dfdl_member'),
    'orderby'   => array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' ),
    'no_found_rows'          => true,
    'ignore_sticky_posts'    => true,
    'update_post_meta_cache' => false, 
    'update_post_term_cache' => false,
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

/**
 * Country
 */
if ( isset($GLOBALS['wp_query']->query_vars['dfdl_country']) ) {
    $term = get_term_by('slug', $GLOBALS['wp_query']->query_vars['dfdl_country'], 'dfdl_countries');
    $args['meta_query'][] = array(
            'key'     => '_dfdl_user_country',
            'value'   => $term->term_id,
            'compare' => '='
        );
    
}

// The Query
$user_query = new WP_User_Query( $args );
$class = ( 0 === count($user_query->results)) ? "no-results" : "" ;

?>
<div id="team-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" class="teams-country-stage silo">
    <?php do_action("dfdl_solutions_country_nav"); ?>
    <input type="hidden" id="dfdl_teams_country" name="dfdl_teams_country" value="<?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] ?>" />
    <div id="results_stage" class="team-stage country <?php echo $GLOBALS['wp_query']->query_vars['dfdl_country'] . " " . $class ?>">
        <div id="team-grid-swiper">
        <?php
            // The Loop
            /*
            if ( ! empty( $user_query->get_results() ) ) {
                foreach ( $user_query->get_results() as $user ) {
                    set_query_var("user", $user);
                    get_template_part( 'includes/template-parts/content/member' );
                }
            } else {
                echo '<div class="no-team-members not-found"><p>No Team Members Found.</p></div>';
            } */

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
