<?php

/*
* Devine Cellars ACF Blocks
*
* Gutenberg blocks created with Advanced Custom Fields Pro
* https://www.advancedcustomfields.com/blog/the-state-of-acf-in-a-gutenberg-world/
*
*/
add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
	foreach ( glob( get_stylesheet_directory() . "/includes/blocks/*/block.json" ) as $file ) {
		register_block_type( $file );
	}
}

// Add DFDL block category
add_filter( 'block_categories_all' , function( $categories ) {
	$categories[] = array(
		'slug'  => 'dfdl-block',
		'title' => 'DFDL'
	);
	return $categories;
} );