<?php

    /**
	 * Set subcategory
	 */
    $post_terms = wp_get_post_terms($story->ID, 'category');
    foreach( $post_terms as $t ) {
        if ( $term->term_id === $t->parent ) {
            $subcategory = $t->name;
        }
    }

?>
<div class="card news">
    <div class="meta">
        <div class="taxonomy">
            <?php if ( isset($term) ) : ?>
                <span class="category"><?php echo $term->name ?></span>
                <?php if ( isset($subcategory) ) : ?>
					<span class="separator">|</span>
					<span class="subcategory"><?php echo $subcategory ?></span>
				<?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="date">
            <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?>
        </div>
    </div>
    EVENT DATE
    <h4><a href="<?php echo esc_url(get_permalink($story->ID)) ?>"><?php echo esc_attr($story->post_title) ?></a></h4>
</div>