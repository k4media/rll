<a class="card-hover" href="<?php echo esc_url(get_permalink($story->ID)) ?>">
    <div class="card events">
        <div class="meta">
            <?php if ( isset($term) ) : ?>
                <span class="category"><?php echo $term->name ?></span>
            <?php endif; ?>
            <div class="date">
                <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp($story->ID) ); ?>
            </div>
        </div>
        <div class="dateline"><?php echo esc_attr($dateline) ?></div>
        <h4><?php echo esc_attr($story->post_title) ?></h4>
    </div>
</a>