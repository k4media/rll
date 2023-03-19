<?php
    /*
    Template Name: Search Page
    */
    get_header();
?>

<div id="searchpage" class="searchpage page page-wrapper silo">

    <div class="searchpage-searchform">
        <?php get_search_form(); ?>
    </div>

    <?php if ( isset($_REQUEST['q']) && ! empty($_REQUEST['q']) ) : ?>

        <header class="page-header alignwide">
            <h1 class="page-title">
                <?php
                printf(
                    /* translators: %s: Search term. */
                    esc_html__( 'Results for "%s"', 'dfdl' ),
                    '<span class="page-description search-term">' . esc_html( $_REQUEST['q'] ) . '</span>'
                );
                ?>
            </h1>
        </header><!-- .page-header -->

        <?php do_action("dfdl_search") ?>

    <?php else : ?>

        <div class="empty-search" style="text-align: center">
            What can we help you find?
        </div>

    <?php endif; ?>

</div>

<?php get_footer(); ?>