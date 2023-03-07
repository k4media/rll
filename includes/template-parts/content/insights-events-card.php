<div class="card events">
    <div class="meta">
        <?php if ( isset($term) ) : ?>
            <span class="category"><?php echo $term->name ?></span>
        <?php endif; ?>
        <?php if (isset($show_date)) : ?>
            <div class="date"><?php echo $show_date ; ?></div>
        <?php endif; ?>
    </div>
    <?php if (isset($dateline)) : ?>
        <div class="dateline"><?php echo $dateline ?></div>
    <?php endif; ?>
    <h4><a href="<?php echo esc_url(get_permalink($story->ID)) ?>"><?php echo esc_attr($story->post_title) ?></a></h4>
</div>