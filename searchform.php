<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$dfdl_unique_id = wp_unique_id( 'search-form-' );

$dfdl_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>
<form role="search" <?php echo $dfdl_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/search/' ) ); ?>">
	<input type="search" id="<?php echo esc_attr( $dfdl_unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="Search..." data-rlvlive="true" data-rlvparentel="#rlvlive" data-rlvconfig="default"/>
</form>
<div class="button-stage"><input type="image" src="<?php echo get_stylesheet_directory_uri() ?>/assets/media/icon-search.svg" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'dfdl' ); ?>" method="get" action="<?php echo get_home_url('', '/search/') ?>" /></div>

