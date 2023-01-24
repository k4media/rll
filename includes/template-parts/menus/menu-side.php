<div id="menu-side">
    <div class="stage silo">

        <div id="menu-side-close"><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-close.svg"></div>

        <nav id="side-navigation">
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'side',
                        'menu_id'        => 'side',
                        'fallback_cb'	 => false,
                        'container'		 => ''
                    )
                );
            ?>
        </nav>

        <div class="footer">
            <img class="art" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/menu-side-art.svg">    
            <div class="fineprint">
                <?php do_action('copyright_notice') ?>
                <?php do_action('social_links') ?>
                <?php do_action('legal_nav') ?>
            </div>
        </div>

    </div>
</div>
