<a href="<?php echo esc_url(get_author_posts_url($user['user']['ID'])) ?>" class="swiper-slide">
    <article <?php post_class("executive-loop executive"); ?>>
        <div class="executive">
            <img src="<?php echo esc_url(get_avatar_url($user['user']['ID'], array('size' => 120))) ?>">
            <div class="details-stage">
                <div class="details">
                    <div class="name"><?php echo esc_attr($user['user']['display_name']) ?></div>
                </div>
            </div>
        </div>
    </article>
</a>