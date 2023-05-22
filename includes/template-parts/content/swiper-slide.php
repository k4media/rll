<div class="swiper-slide">
    <div class="swiper-card">
        <div class="stage">
            <div class="taxonomy"><?php echo $categories?></div>
            <h2>
                <a href="<?php echo get_the_permalink($story->ID) ?>">
                    <?php
                        echo wp_trim_words($story->post_title, 12);
                    ?>
                </a>
            </h2>
            <div class="excerpt">
                <?php
                    // echo get_the_excerpt($story->ID);
                    echo wp_trim_words(get_the_excerpt($story->ID), 22);
                    ?>
                </div>
            <a class="button green ghost" href="<?php echo get_the_permalink($story->ID) ?>">Read More</a>
        </div>
    </div>
</div>