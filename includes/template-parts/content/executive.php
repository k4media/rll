<?php
    /** Locations */
    $locations   = array();
    $country_ids = get_user_meta( $user['user']['ID'], '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }
?>
<a href="<?php echo esc_url(get_author_posts_url($user['user']['ID'])) ?>" class="swiper-slide">
    <article <?php post_class("executive-loop executive"); ?>>
        <div class="executive">
            <img src="<?php echo esc_url(get_avatar_url($user['user']['ID'], array('size' => 120))) ?>">
            <div class="details-stage">
                <div class="details">
                    <div class="name"><?php echo esc_attr($user['user']['display_name']) ?></div>
                    <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                        <div class="location"><?php echo implode(", ", $locations) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</a>