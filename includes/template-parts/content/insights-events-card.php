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
            <?php echo dfdl_post_terms($story->ID); ?>
        </div>
        <?php if (isset($show_date)) : ?>
            <div class="date"><?php echo $show_date ; ?></div>
        <?php endif; ?>
    </div>
    <?php if (isset($dateline)) : ?>
        <div class="dateline"><?php echo $dateline ?></div>
    <?php endif; ?>
    <h4><a href="<?php echo esc_url(get_permalink($story->ID)) ?>"><?php echo esc_attr($story->post_title) ?></a></h4>
</div>