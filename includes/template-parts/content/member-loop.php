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
    <div class="founder member">';
        <img src="<?php get_avatar_url($f['founder']['ID'], array('size' => 330)) ?>">
        <div class="details-stage">
            <div class="details">
                <div class="name"><?php $f['founder']['display_name'] ?></div>';
                <?php if (isset($position)) {
                    $output[] = '<div class="position">' . $position . '</div>'; 
                }
                if ( count($locations) > 0 ) {
                    $output[] = '<div class="location">' . implode(", ", $locations) . '</div>'; 
                }
                ?>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
