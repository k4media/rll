<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */
?>

    <div id="colophon" class="site-footer">
        <div class="site-info silo">
            <div class="about-dfld">
                <?php do_action('footer_logo') ?>
                <?php do_action('footer_text') ?>
                <?php do_action('footer_nav') ?>
            </div>
            <div class="newsletter-signup">
                <?php do_action('newsletter_signup') ?>
            </div>
        </div>
        <div id="fineprint" class="silo">
            <?php do_action('copyright_notice') ?>
            <?php do_action('social_links') ?>
            <?php do_action('legal_nav') ?>
        </div>
    </div>

</div><!-- /page -->

<?php wp_footer(); ?>

</body>
</html>
