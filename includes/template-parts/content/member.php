<?php

    /**
     * Template part for Member
     */
    $member_slug = sanitize_title($user->data->display_name);
    $link        = get_home_url(null, 'teams/members/' . $member_slug . '/' . $user->data->ID . '/');
    $position    = get_user_meta( $user->data->ID, 'position', true);
    $locations   = array();
    $country_ids = get_user_meta( $user->data->ID, '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }

?>
<article id="post-<?php the_ID(); ?>" <?php post_class("member-loop"); ?>>
    <div class="team-member">
        <a href="<?php echo $link ?>">
            <img src="<?php echo get_avatar_url($user->data->ID, array('size' => 320)) ?>">
            <div class="details-stage">
                <div class="details">
                    <div class="name"><?php echo $user->data->display_name ?></div>
                    <?php if( isset($position) ) : ?>
                        <div class="position"><?php echo $position ?></div> 
                    <?php endif; ?>
                    <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                        <div class="location"><?php echo implode(", ", $locations) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
