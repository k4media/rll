<?php

    /**
     * Template part for Founder
     */
    $member_slug = sanitize_title($user['founder']['display_name']);
    $link        = get_author_posts_url($user['founder']['ID']);
    $position    = get_user_meta( $user['founder']['ID'], 'position', true);
    $locations   = array();
    $country_ids = get_user_meta( $user['founder']['ID'], '_dfdl_user_country');
    foreach( $country_ids as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }

    /** Country expertise */
    $country_expertise = array();
    $countries = get_user_meta( $user['founder']['ID'], '_dfdl_user_country_expertise');
    foreach( $countries as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $country_expertise[] = $country->name;
    }

    /** Solution Services */
    $expertise   = array();
    $solution_ids = get_user_meta( $user['founder']['ID'], '_dfdl_user_solutions');
    foreach( $solution_ids as $s ) {
        $solution = get_term( $s, 'dfdl_solutions', true);
        $expertise[] = $solution->name;
    }

?>

<a href="<?php echo $link ?>" class="swiper-slide">
    <article id="post-<?php the_ID(); ?>" <?php post_class("member-loop founder"); ?>>
        <div class="team-member">
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
                    <h4>Expertise</h4>
                    <ul>
                    <?php 
                        foreach( $expertise as $e ) {
                            echo '<li>' . $e . '</li>';
                        }
                    ?>
                    </ul>
                <?php endif; ?>
                <?php if ( isset($country_expertise) && count($country_expertise) > 0 ) : ?>
                    <?php if ( count($country_expertise) === 1 ) : ?>
                        <h4>Country of Expertise</h4>
                    <?php else : ?>
                        <h4>Countries of Expertise</h4>
                    <?php endif; ?>
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