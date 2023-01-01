<?php
/**
 * The template for Team Members
 */

 global $wp;

 get_header();

 $user = get_user_by('id', $GLOBALS['wp_query']->query_vars['dfdl_member']) ;
 $meta = get_user_meta($user->data->ID);
 $position    = get_user_meta( $user->data->ID, 'position', true);
 $locations   = array();
 $country_ids = get_user_meta( $user->data->ID, '_dfdl_user_country');
 foreach( $country_ids as $c ) {
    $country = get_term( $c, 'dfdl_countries', true);
    $locations[] = $country->name;
 }

?>

<div id="dfdl-member-<?php echo $GLOBALS['wp_query']->query_vars['dfdl_member'] ?>" class="dfdl-single-member-stage">
    <div class="dfdl-single-member <?php echo sanitize_title($user->data->display_name) ?> narrow">
        <div class="avatar"><img src="<?php echo get_avatar_url($user->data->ID, array('size' => 320)) ?>"></div>
        <div class="details-stage">
            <div class="member">

                <div class="name"><?php echo $user->data->display_name ?></div>
                <?php if( isset($position) ) : ?>
                     <div class="position"><?php echo $position ?></div> 
                <?php endif; ?>
                <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                    <div class="location"><?php echo implode(", ", $locations) ?></div>
                 <?php endif; ?>
                 <?php if( $meta['description'] ) : ?>
                    <div class="bio"><?php echo $meta['description'][0] ?></div>
                 <?php endif; ?>

                 <div class="contact-details">
                    <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) : ?>
                        <div class="telephone"><?php echo $meta['tel'][0] ?></div>
                    <?php endif; ?>
                    <?php if ( isset($meta['mob']) && ! empty($meta['mob'][0]) ) : ?>
                        <div class="mobile"><?php echo $meta['mob'][0] ?></div>
                    <?php endif; ?>
                    <?php if ( isset($meta['email']) && ! empty($meta['email'][0]) ) : ?>
                        <div class="email"><a href="mailto:<?php echo $meta['email'][0] ?>">email</a></div>
                    <?php endif; ?>
                    <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) : ?>
                        <div class="linkedin"><a href="<?php echo $meta['linkedin'][0] ?>">linkedIn</a></div>
                    <?php endif; ?>
                 </div>

             </div>
        </div>

    </div>
</div>

<?php
    get_footer();
