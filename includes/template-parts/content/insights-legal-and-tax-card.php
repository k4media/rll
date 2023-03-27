<a class="card-hover" href="<?php echo esc_url(get_permalink($story->ID)) ?>">
    <div class="card news">
        <div class="meta">
            <div class="taxonomy">
                <?php if ( isset($term) ) : ?>
                    <span class="category"><?php echo $term->name ?></span>
                <?php endif; ?>
                <?php if ( isset($subcategory) ) : ?>
                    <span class="subcategory"><?php echo $category ?></span>
                <?php endif; ?>
            </div>
            <div class="date">
                <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?>
            </div>
        </div>
        <div class="author"><?php echo get_the_author_meta('display_name', $story->post_author); ?></div>
        <h4><?php echo esc_attr($story->post_title) ?></h4>
    </div>
</a>