<div class="card news <?php if (isset($class)) { echo $class; } ?>">
    <div class="meta">
        <div class="taxonomy">
            <?php if ( isset($term) ) : ?>
                <span class="category"><?php echo $term->name ?></span>
            <?php endif; ?>
            <?php if ( isset($category) ) : ?>
                <span class="subcategory"><?php // echo $category ?></span>
            <?php endif; ?>
        </div>
        <div class="date">
            <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp($story->ID) ); ?>
        </div>
    </div>
    <h4><a href="<?php echo esc_url(get_permalink($story->ID)) ?>"><?php echo esc_attr($story->post_title) ?></a></h4>
    <div class="excerpt">
        <?php echo get_the_excerpt($story->ID) ?>
    </div>
</div>