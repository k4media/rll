<?php
/**
 * The template for Team Members
 */
 get_header();
            
    /**
     * All Members
     */
    $args = array(
        'number'    => get_option('posts_per_page'),
        'role__in ' => array('contributor', 'author', 'editor', 'admin', 'dfdl_member'),
        'orderby'   => array( 'dfdl_rank' => 'ASC', 'last_name' => 'ASC' )
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
    <div id="results_stage" class="team-stage all">
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
                    echo 'No users found.';
                }
                */

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