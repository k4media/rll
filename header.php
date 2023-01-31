<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php

$key = "dfdl-header";
$K4 = new K4;
$K4->fragment_cache( $key, function() { ?>
<header>
	<div class="header-stage silo">
		<div class="left">
			<button id="hamburger" type="button" aria-label="Menu" aria-controls="navigation"></button>
			<nav id="site-navigation" class="main-navigation">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary',
							'fallback_cb'	 => false,
							'container'		 => ''
						)
					);
				?>
			</nav><!-- #site-navigation -->
		</div>
		<div class="site-branding">
			<?php do_action("dfdl_logo") ?>
		</div><!-- .site-branding -->
		<div class="right">
			<nav id="site-navigation" class="secondary-navigation">
				<?php
					wp_nav_menu(
						array(
							'theme_location' => 'secondary',
							'menu_id'        => 'secondary',
							'fallback_cb'	 => false,
							'container'		 => ''
						)
					);
				?>
			</nav><!-- #site-navigation -->
		</div>
	</div>
</header>
<?php }); // close K4 fragment ?>

<div id="page" class="site">
