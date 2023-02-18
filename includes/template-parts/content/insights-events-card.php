<?php



?>

<div class="card news">
    <div class="meta">
        <div class="tax">
            <?php if ( isset($term) ) : ?>
                <span class="section"><?php echo $term->name ?></span>
            <?php endif; ?>
            <?php if ( isset($category) ) : ?>
                <span class="category"><?php echo $category ?></span>
            <?php endif; ?>
        </div>
        <div class="date">
            <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() ); ?>
        </div>
    </div>
    EVENT DATE
    <h4><a href="<?php echo esc_url(get_permalink($story->ID)) ?>"><?php echo esc_attr($story->post_title) ?></a></h4>
</div>