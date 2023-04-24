<div class="swiper-slide">
    <div class="swiper-card">
        <div class="stage">
            <div class="taxonomy"><?php echo $categories?></div>
            <h2><a href="<?php echo get_the_permalink($story->ID) ?>"><?php echo $story->post_title ?></a></h2>
            <div class="excerpt"><?php echo get_the_excerpt($story->ID) ?></div>
            <a class="button green ghost" href="<?php echo get_the_permalink($story->ID) ?>">Read More</a>
        </div>
    </div>
</div>