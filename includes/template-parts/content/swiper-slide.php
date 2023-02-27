<div class="swiper-slide">
    <div class="swiper-card">
        <div class="taxonomy"><?php echo $categories?></div>
        <h2><?php echo $story->post_title ?></h2>
        <div class="excerpt"><?php echo get_the_excerpt($story->ID) ?></div>
        <a class="button green ghost" href="<?php echo get_the_permalink($story->ID) ?>">Read More</a>
    </div>
</div>