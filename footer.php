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
<?php
$K4 = new K4;
$K4->fragment_cache( "page-footer", function() { ?>
    <div id="colophon" class="site-footer">
        <div class="site-info">
            <div class="about-dfld">
                <?php do_action('footer_logo') ?>
                <?php do_action('footer_text') ?>
            </div>
            <div class="newsletter-signup">
                <?php do_action('newsletter_signup') ?>
            </div>
        </div>

        <?php do_action('footer_nav') ?>

        
        <div class="fineprint">
            <?php do_action('copyright_notice') ?>
            <?php do_action('social_links') ?>
            <?php do_action('legal_nav') ?>
        </div>
    </div>
    
<?php }); // close K4 fragment ?>
</div><!-- /page -->
<?php wp_footer(); ?>
</body>
</html>
