<?php
/**
 * The template for Team Members
 */

global $wp;

get_header();

$position  = "";
$locations = array();
$expertise = array();
$solutions = array();

$user = get_user_by('id', $GLOBALS['wp_query']->query_vars['dfdl_member']) ;
$meta = get_user_meta($user->data->ID);

// position
if ( isset($meta['position'][0]) ) {
    $position = $meta['position'][0];
}

// language
if ( isset($meta['languages'][0]) ) {
    $languages = $meta['languages'][0];
}

// office location
if ( isset($meta) && is_array($meta) && array_key_exists('_dfdl_user_country', $meta) ) {
    foreach( $meta['_dfdl_user_country'] as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
    }
}

// country expertise
if ( isset($meta) && is_array($meta) && array_key_exists('_dfdl_user_country_expertise', $meta) ) {
    foreach( $meta['_dfdl_user_country_expertise'] as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $expertise[] = '<li>' . $country->name. '</li>';
    }
} else {
    $expertise[] = "<li>Regional Expert</li>";
}

// solutions
if ( isset($meta) && is_array($meta) && array_key_exists('_dfdl_user_solutions', $meta) ) {
    foreach( $meta['_dfdl_user_solutions'] as $c ) {
        $solution = get_term( $c, 'dfdl_solutions', true);
        if (isset($solution)) {
            $solutions[] = '<li>' . $solution->name . '</li>';
        }
        
    }
} else {
    $solutions[] = "<li>General Law Expert</li>";
}
?>
<nav class="subnav-stage silo">
    <ul>
        <li class="back"><a href="<?php echo dfdl_solutions_back_link() ?>">Back</a></li>
    </ul>
</nav>
<div id="dfdl-member-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_member'] ?>" class="dfdl-single-member-stage">
    <div class="dfdl-single-member <?php echo sanitize_title($user->data->display_name) ?> narrow">
        <div class="avatar"><img src="<?php echo get_avatar_url($user->data->ID, array('size' => 320)) ?>"></div>
        <div class="details-stage">
            <div class="member">
                <div class="name"><?php echo esc_attr($user->data->display_name) ?></div>
                <?php if( isset($position) ) : ?>
                     <div class="position"><?php echo $position ?></div> 
                <?php endif; ?>
                <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                    <div class="location"><?php echo implode(", ", $locations) ?></div>
                 <?php endif; ?>
                 <?php if( isset($languages) ) : ?>
                     <div class="languages"><?php echo $languages ?></div> 
                <?php endif; ?>
                 <div class="contact-details">
                    <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) : ?>
                        <div class="telephone"><?php echo $meta['tel'][0] ?></div>
                    <?php endif; ?>
                    <?php /* if ( isset($meta['mob']) && ! empty($meta['mob'][0]) ) : ?>
                        <div class="mobile"><?php echo $meta['mob'][0] ?></div>
                    <?php endif; */ ?>
                    <?php if ( isset($user->user_email) && ! empty($user->user_email) ) : ?>
                        <div class="email"><a href="mailto:<?php echo $user->user_email ?>">email</a></div>
                    <?php endif; ?>
                    <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) : ?>
                        <div class="linkedin"><a href="<?php echo $meta['linkedin'][0] ?>">linkedIn</a></div>
                    <?php endif; ?>
                 </div>
             </div>
        </div>
    </div>
    <div id="dfdl-member-xtra"" class="text-feature-stage">
        <div class="text-feature member narrow">
            <div class="columns">
                <div class="lcol">
                    <h3>Expertise</h3>
                    <div class="solutions"><ul><?php echo implode($solutions) ?></ul></div>
                </div>
                <div class="rcol">
                    <?php if ( count($expertise) > 1 ) : ?>
                        <h3>Countries of expertise</h3>
                    <?php else: ?>
                        <h3>Country of expertise</h3>
                    <?php endif; ?>
                    <div class="countries"><ul><?php echo implode($expertise) ?></ul></div>
                </div>
            </div>
            <div class="bio">
                <?php
                    if ( isset($meta['description'][0]) ) {
                        echo wpautop($meta['description'][0]);
                    }
                ?>
            </div>
        </div>
    </div>
    <?php do_action("dfdl_written_by") ?>
    <?php do_action("dfdl_in_the_news") ?>
</div>

<?php
    get_footer(); 