<?php

    /**
     * Template part for Member
     */
    $member_slug = sanitize_title($user->data->display_name);
    $link        = get_author_posts_url($user->data->ID);

    /** Position */
    $position    = get_user_meta( $user->data->ID, 'position', true);

    /** Locations */
    $locations   = array();
    $country_ids = get_user_meta( $user->data->ID, '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }

    /** Country expertise */
    $country_expertise = array();
    $countries = get_user_meta( $user->data->ID, '_dfdl_user_country_expertise');
    foreach( $countries as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $country_expertise[] = $country->name;
    }

    /** Solution Services */
    $expertise   = array();
    $solution_ids = get_user_meta( $user->data->ID, '_dfdl_user_solutions');
    foreach( $solution_ids as $s ) {
        $solution = get_term( $s, 'dfdl_solutions', true);
        $expertise[] = $solution->name;
    }

    $rank = get_user_meta( $user->data->ID, '_dfdl_member_rank', true);


?>
<a href="<?php echo $link ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class("member-loop"); ?>>
        <div class="team-member">
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
        </div>
        <div class="rollover">
            <div class="stage">
                <?php if ( isset($position) ) : ?>
                    <h4>Position</h4>
                    <ul>
                        <li><?php echo $position ?></li>
                    </ul>
                    <!-- <div class="ranking"><?php echo $position ?></div>-->
                <?php endif; ?>
                <?php if ( isset($expertise) && count($expertise) > 0 ) : ?>
                    <h4>Key Services</h4>
                    <ul>
                    <?php 
                        foreach( $expertise as $e ) {
                            echo '<li>' . $e . '</li>';
                        }
                    ?>
                    </ul>
                <?php endif; ?>
                <?php if ( isset($country_expertise) && count($country_expertise) > 0 ) : ?>
                    <h4>Key Countries</h4>
                    <ul>
                    <?php 
                        foreach( $country_expertise as $c ) {
                            echo '<li>' . $c . '</li>';
                        }
                    ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->
</a>