<?php
/**
 * Contact Us page(s) template
 */

// validation
wp_enqueue_script('dfdl-contact-validation', get_stylesheet_directory_uri() . '/assets/js/dfdl-contact.js', null, null, true );
 
get_header();

$country  = ( isset($GLOBALS['wp_query']->query_vars['dfdl_country']) ) ? $GLOBALS['wp_query']->query_vars['dfdl_country'] : "regional" ;

/**
 * Cache results
 */
$key = "contact-" . $country;
$K4 = new K4;
$K4->fragment_cache( $key, function() { 

$country  = ( isset($GLOBALS['wp_query']->query_vars['dfdl_country']) ) ? $GLOBALS['wp_query']->query_vars['dfdl_country'] : "regional" ;
$contacts = get_field("countries", "options");

if ( $contacts[$country]["contact"] ) {
    $user = get_user_by("id", $contacts[$country]["contact"]);
    $meta = get_user_meta($contacts[$country]["contact"]);
    $locations = array();
    if ( isset($meta['_dfdl_user_country']) ) {
        foreach( $meta['_dfdl_user_country'] as $c ) {
            $foo = get_term( $c, 'dfdl_countries', true);
            $locations[] = $foo->name;
        }
    }
 }

?>
<div id="contact-dfdl">
    <?php do_action("dfdl_solutions_country_nav"); ?>
    <div class="contact-stage narrow">
        <div class="copy-stage">
            <div class="copy">
                <h2>Contact Us</h2>
                <?php if ( $contacts[$country]["text"] ) : ?>
                    <div class="text"><?php echo wpautop($contacts[$country]["text"]); ?></div>
                <?php endif; ?>
                <?php if ( $contacts[$country]["phone"] ) : ?>
                    <div class="telephone"><?php echo $contacts[$country]["phone"]; ?></div>
                <?php endif; ?>
                <?php if ( $contacts[$country]["email"] ) : ?>
                    <div class="email"><a href="mailto:<?php echo $contacts[$country]["email"]; ?>"><?php echo $contacts[$country]["email"]; ?></a></div>
                <?php endif; ?>
            </div>
            <div class="form">
                <?php do_action("dfdl_contact_form"); ?>
            </div>
        </div>
    </div>

    <?php if ( $contacts[$country]["contact"] ) : ?>
        <?php 
            /*
            * Count user details.
            * If 2, align-left. If 3, justify.
            */
            $counter = 0;
            if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) {
                $counter++; 
            }
            if ( isset($meta['email']) && ! empty($meta['email'][0]) ) {
                $counter++; 
            }
            if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) {
                $counter++; 
            }
            $contact_align = ( $counter > 2 ) ? "full" : "partial" ;

            $bio = get_the_author_meta("description", $contacts[$country]["contact"]);
            $bio = dfdl_short_bio($bio, 3 );
        ?>
        <div class="team-lead-stage callout">
            <div class="team-lead narrow">
                <div class="lead-team-member dfdl-single-member">
                    <div class="avatar">
                        <a href="<?php echo get_author_posts_url($contacts[$country]["contact"]) ?>"><img src="<?php echo get_avatar_url($contacts[$country]["contact"], array('size' => 320)) ?>"></a>
                    </div>
                    <div class="details-stage ">
                        <div class="member">
                                <div class="slug">Regional Key Contact</div>
                                <div class="name"><a href="<?php echo get_author_posts_url($contacts[$country]["contact"]) ?>"><?php echo $user->display_name ?></a></div>
                                <?php if( isset($meta['position'][0]) ) : ?>
                                    <div class="position"><?php echo $meta['position'][0] ?></div> 
                                <?php endif; ?>
                                <?php if( is_array($locations) && count($locations) > 0 ) : ?>
                                    <div class="location"><?php echo implode(", ", $locations) ?></div>
                                <?php endif; ?>
                                <?php if( isset($bio) && ! empty($bio) && $bio !== "" ) : ?>
                                    <div class="bio"><?php echo $bio ?></div>
                                <?php endif; ?>
                                <div class="contact-details <?php echo $contact_align ?>">
                                    <div class="telephone">
                                        <?php if ( isset($meta['tel']) && ! empty($meta['tel'][0]) ) : ?>
                                            <?php echo $meta['tel'][0] ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="email">
                                        <?php if ( isset($meta['email']) && ! empty($meta['email'][0]) ) : ?>
                                            <a href="mailto:<?php echo $meta['email'][0] ?>"><div class="email">Email</div></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="linkedin">
                                        <?php if ( isset($meta['linkedin']) && ! empty($meta['linkedin'][0]) ) : ?>
                                            <a href="<?php echo $meta['linkedin'][0] ?>"><div class="linkedin">LinkedIn</div></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?> 


</div>
<?php }); // close K4 fragment ?>

<?php get_footer();