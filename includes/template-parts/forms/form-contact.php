<?php

/**
 * Process Contact Form
 */

$sections = dfdl_get_section();
$country  = ( isset($sections[1]) ) ? $sections[1] : "regional" ;

if ( isset($_POST['contact-submit']) && ! empty(isset($_POST['contact-submit'])) ) {

    /** validate form nonce */
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'comment-form_' . $country )) {
        echo "<p>Failed security check. Please refresh the page and try again.</h4>";
        exit;
    }

    $elements = array();

    $elements['firstname'] = $_POST['firstname'];
    $elements['lastname']  = $_POST['lastname'];
    $elements['email']     = $_POST['email'];
    $elements['telephone'] = $_POST['telephone'];
    $elements['company']   = $_POST['company'];
    $elements['position']  = $_POST['position'];
    $elements['message']   = $_POST['message'];

    /** sanitize input */
    $clean_elements = array_map('sanitize_text_field', $elements);

    /** validate input */
    $error_messages = array();
    foreach( $clean_elements as $key => $value ) {
        if ( "" === $value ) {
            $error_messages[$key] = true;
        }
    }

    /** sanitize email */
    if (! filter_var($clean_elements['email'], FILTER_VALIDATE_EMAIL)) {
        $error_messages['email'] = true;
    }

    if ( count($error_messages) === 0 ) {

        // insert into cpt
        $submitted = true;

    }

}

?>

<?php if ( isset($submitted) && true === $submitted ) : ?>
    
    <div class="contact-form submitted">
        <h2>Thanks!</h2>
        <p>We have received your submission and will follow up shortly.</p>
    </div>

<?php else : ?>

    <div class="contact-form">

    <?php if ( isset($error_messages) && count($error_messages) > 0 ) : ?>
        
        <p class='error-banner'>You missed some fields. Please review the form and try again.</p>

    <?php endif; ?>

        <form id="dfdl-contact" method="post"action="">
        <?php
            $sections = dfdl_get_section();
            $country  = ( isset($sections[1]) ) ? $sections[1] : "regional" ;
            wp_nonce_field( 'comment-form_' . $country );
        ?>
        <input type="hidden" id="form_country" name="form_country" value="<?php echo $country ?>">
        <div class="details">
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['firstname'])) {
                        $error_class = " dirty invalid";
                    }
                ?>
                <label class="<?php echo $error_class ?>">Name <span class="required">*</span>
                    <input type="text" id="firstname" name="firstname" value="<?php if (isset($elements['firstname'])) { echo esc_attr($elements['firstname']); } ?>">
                    <span class="error-message">Please give your first name</span>
                </label>
            </div>
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['lastname'])) {
                        $error_class = " dirty invalid";
                    }
                ?>
                <label class="<?php echo $error_class ?>">Surname <span class="required">*</span>
                    <input type="text" id="lastname" name="lastname" value="<?php if (isset($elements['lastname'])) { echo esc_attr($elements['lastname']); } ?>">
                    <span class="error-message">Please give your surname</span>
                </label>
            </div>
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['email'])) {
                        $error_class = " dirty invalid";
                    }
                ?>
                <label class="<?php echo $error_class ?>">Email <span class="required">*</span>
                    <input type="email" id="email" name="email" value="<?php if (isset($elements['email'])) { echo esc_attr($elements['email']); } ?>">
                    <span class="error-message">Please enter a valid email address</span>
                </label>
            </div>
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['telephone'])) {
                        $error_class = " dirty invalid";
                    }
                ?>    
                <label class="<?php echo $error_class ?>">Phone Number <span class="required">*</span>
                    <input type="text" id="telephone" name="telephone" value="<?php if (isset($elements['telephone'])) { echo esc_attr($elements['telephone']); } ?>">
                    <span class="error-message">Please give your telephone number</span>
                </label>
            </div>
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['company'])) {
                        $error_class = " dirty invalid";
                    }
                ?>   
                <label class="<?php echo $error_class ?>">Company <span class="required">*</span>
                    <input type="text" id="company" name="company" value="<?php if (isset($elements['company'])) { echo esc_attr($elements['company']); } ?>">
                    <span class="error-message">Please give your company name</span>
                </label>
            </div>
            <div>
                <?php
                    $error_class = "";
                    if ( isset($error_messages['position'])) {
                        $error_class = " dirty invalid";
                    }
                ?> 
                <label class="<?php echo $error_class ?>">Position <span class="required">*</span>
                    <input type="text" id="position" name="position" value="<?php if (isset($elements['position'])) { echo esc_attr($elements['position']); } ?>">
                    <span class="error-message">Please give your job position</span>
                </label>
            </div>
        </div>

        <h4>Solutions</h4>
        <div class="solutions">
            <?php
                $solutions = dfdl_get_solutions_tax();
                foreach( $solutions as $s ) {
                    $checked = "";
                    if ( isset($_POST['solutions']) && ! empty($_POST['solutions']) ) {
                        if ( in_array( $s->term_id, $_POST['solutions'])) {
                            $checked = "checked";
                        }
                    }
                    echo '<label class="checkbox-control">';
                    echo '<input type="checkbox" id="' . esc_attr($s->slug) . '" name="solutions[]" value="' . esc_attr($s->term_id) . '" class="checkbox" ' .  $checked . '>' . esc_attr($s->name) ;
                    echo '</label>';
                }
            ?>
        </div>

        <?php
            $error_class = "";
            if ( isset($error_messages['message'])) {
                $error_class = " dirty invalid";
            }
        ?> 
        <div class="stage-message <?php echo $error_class ?>">
            <h4>Message <span class="required">*</span></h4>
            <textarea id="message" name="message" class="message"><?php if (isset($_POST['message'])) { echo esc_attr(trim($_POST['message'])); } ?></textarea>
            <span class="error-message">Please tell us a little bit about your enquiry</span>
        </div>
        <button id="contact-submit" name="contact-submit" type="submit"  value="contact-form-submit" class="button green solid submit disabled">Submit</button>
    </div>

<?php endif; ?>