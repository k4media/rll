<?php
/**
 * The template for Team Members
 */
 get_header();
            
    /**
     * All Members
     */
    $args = array(
        'number'    => -1,
        'role__in ' => array('contributor', 'author', 'editor', 'admin', 'dfdl_member'),
        'orderby'   => 'user_nicename',
        'order'     => 'ASC'
    );

    // The Query
    $user_query = new WP_User_Query( $args );

?>
<div id="team-all" >
    <?php do_action("dfdl_solutions_country_nav"); ?>
    <div class="team-stage silo">
        <?php
            // The Loop
            if ( ! empty( $user_query->get_results() ) ) {
                foreach ( $user_query->get_results() as $user ) {
                    set_query_var("user", $user);
                    get_template_part( 'includes/template-parts/content/member' );
                }
            } else {
                echo 'No users found.';
            }
        ?>
    </div>
</div>

<?php
    /**
     * Show reusable "contact" block, ID 700
     */
     do_action("dfdl_reusable_block", 700);
?>

<?php get_footer();