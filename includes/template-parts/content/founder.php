<?php

    /**
     * Template part for Founder
     */
    $member_slug = sanitize_title($user['founder']['display_name']);
    $link        = get_home_url(null, 'teams/members/' . $member_slug . '/' . $user['founder']['ID'] . '/');
    $position    = get_user_meta( $user['founder']['ID'], 'position', true);
    $locations   = array();
    $country_ids = get_user_meta( $user['founder']['ID'], '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("member-loop founder"); ?>>
    <div class="team-member">
        <a href="<?php echo $link ?>">
            <img src="<?php echo esc_url(get_avatar_url($user['founder']['ID'], array('size' => 320))) ?>">
            <div class="details-stage">
                <div class="details">
                    <div class="name"><?php echo esc_attr($user['founder']['display_name']) ?></div>
                    <?php if( isset($position) ) : ?>
                        <div class="position"><?php echo esc_attr($position) ?></div> 
                    <?php endif; ?>
                    <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                        <div class="location"><?php echo esc_attr(implode(", ", $locations)) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
