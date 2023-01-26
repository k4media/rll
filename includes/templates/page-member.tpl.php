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

 // office location
 if ( is_array($meta['_dfdl_user_country']) ) {
    foreach( $meta['_dfdl_user_country'] as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $locations[] = $country->name;
     }
 }
 
  // country expertise
  if ( is_array($meta['_dfdl_user_country_expertise']) ) {
    foreach( $meta['_dfdl_user_country_expertise'] as $c ) {
        $country = get_term( $c, 'dfdl_countries', true);
        $expertise[] = $country->name;
     }
 }

 // solutions
 if ( is_array($meta['_dfdl_user_solutions']) ) {
    foreach( $meta['_dfdl_user_solutions'] as $c ) {
        $solution = get_term( $c, 'dfdl_solutions', true);
        $solutions[] = $solution->name;
     }
 }

?>

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
                 <?php /* if( $meta['description'] ) : ?>
                    <div class="bio"><?php echo $meta['description'][0] ?></div>
                 <?php endif; */ ?>

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
                    <h3>Solutions</h3>
                    <div class="solutions"><?php echo implode(", ", $solutions) ?></div>
                </div>
                <div class="rcol">
                    <h3>Country of expertise</h3>
                    <?php echo implode(", ", $expertise) ?>
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

    <div id="dfdl-member-published"" class="xtra silo">
        <h2>Written by <?php echo esc_attr($user->data->display_name) ?></h2>
        <div>coming soon</div>
    </div>

    <div id="dfdl-member-news"" class="xtra silo">
        <h2>In the News</h2>
        <div>coming soon</div>
    </div>

</div>

<?php
    get_footer(); 