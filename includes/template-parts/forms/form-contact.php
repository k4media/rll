<?php

/**
 * Process Contact Form
 */

require( get_template_directory() .'/rll-config.php');

/**
 * Page Section & Country
 * Used to create and validate nonce
 */

$country  = "regional" ;
$send_to  = array('info@dfdl.com', 'robert@k4media.com');

/**
 * Process form submission
 */

if ( isset($_POST['form_country']) && ! empty(isset($_POST['form_country'])) ) {

    /** validate form nonce */
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'contact-form_malaysia' )) {
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
    $elements['country']   = $_POST['form_country'];

    /** Sanitize input */
    $clean_elements = array_map('sanitize_text_field', $elements);

    /** Sanitize Solutions */
    if ( isset($_POST['solutions']) ) {
        $clean_solutions = array_map('intval', $_POST['solutions']);
    }
    
    /** Validate input */
    $errors = array();
    $errors['count'] = 0;
    $error_messages = array();
    foreach( $clean_elements as $key => $value ) {
        if ( "" === $value ) {
            $errors[$key] = true;
            $errors['count']++;
        }
    }

    /** Sanitize email */
    if (! filter_var($clean_elements['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['count']++;
        $error_messages['email'] = "Invalid email address";
    }

    /**
     * for email field, let's actually check it
     * take a given email address and split it into 
     * the username and domain.
     */
    list($userName, $mailDomain) = explode("@", $clean_elements['email']);
   
    /**
     * use php checkdnsr to see if email/domain is valid
     * this only works on unix servers though
     * so check to se we are on the live server
     */
    if (!@checkdnsrr($mailDomain, "MX")) {
        $errors['count']++;
        $error_messages['email'] = "Invalid email address";
    }

    /**
     * SPAM CHECKING
     */
    if ( $clean_elements['firstname'] == $clean_elements['lastname']) {
        $errors['count']++;
    }
    if ( $clean_elements['firstname'] == $clean_elements['position']) {
        $errors['count']++;
    }
    if ( $clean_elements['company'] == $clean_elements['lastname']) {
        $errors['count']++;
    }
    if ( strlen($clean_elements['message']) < 16 ) {
        $errors['count']++;
    }

    /**
     * GOOGLE CAPTCHA CHECK
     */
    $captcha = $_POST['g-recaptcha-response'];
    if ( empty( $_POST['g-recaptcha-response'] ) ) {
        $errors['count']++;
    } else {
        $is_captcha_valid = validate_google_captcha($_POST['g-recaptcha-response'], $googleApiSecret);
        if ( ! $is_captcha_valid == 1 ) {
            $errors['count']++;
            $error_messages['spam'] = "Expired ReCaptcha. Please reload the page and try again.";
        }
    }

    if ( $errors['count'] === 0 ) {

        $nice_date = wp_date("M d, Y, H:i:s");

        $args = array(
            'post_title'  => $clean_elements['firstname'] . " " . $clean_elements['lastname'] . " | " . $nice_date,
            'post_type'   => 'dfdl_contact_forms',
            'post_status' => 'private'
        );

        /** insert new custom post */
        $new_post = wp_insert_post($args);

        update_field( "field_64035670e27ac", $clean_elements['country'], $new_post );
        update_field( "field_6403527fff8d6", $clean_elements['firstname'] , $new_post );
        update_field( "field_64035293ff8d7", $clean_elements['lastname'] , $new_post );
        update_field( "field_6403529bff8d8", $clean_elements['email'] , $new_post );
        update_field( "field_640352adff8d9", $clean_elements['telephone'] , $new_post );
        update_field( "field_640352baff8da", $clean_elements['company'] , $new_post );
        update_field( "field_640352c6ff8db", $clean_elements['position'] , $new_post );
        update_field( "field_640352cbff8dc", $clean_elements['message'] , $new_post );

        /** set solutions cpt */
        if (! empty($clean_solutions) ) {
            $result = wp_set_object_terms($new_post, $clean_solutions, 'dfdl_solutions');
        }

        /** insert flag */
        $submitted = true;

        /** Compose email */
        $message = '<h4>Someone submitted a Contact Form on Robin Lynn & Lee</h4>';
        $message .= '<p>Name: ' . $clean_elements['firstname'] . '  ' . $clean_elements['lastname'] . '<br>';
        $message .= 'Email: ' . $clean_elements['email'] . '<br>';
        $message .= 'Phone: ' . $clean_elements['telephone'] . '<br>';
        $message .= 'Company: ' . $clean_elements['company'] . '<br>';
        $message .= 'Position: ' . $clean_elements['position'] . '</p>';
        
        if ( isset($clean_solutions) && count($clean_solutions) > 0 ) {
            $message .= "<h4>SOLUTIONS</h4><ul>";
            foreach( $clean_solutions as $solution ) {
                $tax = get_term_by('ID', $solution, 'dfdl_solutions');
                $message .= "<li>" . esc_attr($tax->name) . "</li>";
            }
            $message .= "</ul>";
        }
        
        $message .= "<h4>MESSAGE</h4>";
        $message .= '<p>' . nl2br($elements['message']) . '</p>';

        $admin_link = get_admin_url('', 'post.php?post=' . $new_post . '&action=edit');
        $message .= '<p>You can view this form online at <a href="' . $admin_link . '">' . $admin_link . '</a></p>';

        /** Send Email */
        $headers = array('Content-Type: text/html; charset=UTF-8');

        /** Lookup country contact email */
        if ( function_exists('get_field') ) {
            $contacts = get_field("countries", "options");
            if ( $contacts[$clean_elements['country']]["contact"] ) {
                $user = get_user_by("id", $contacts[$country]["contact"]);
                $send_to[] = $user->data->user_email;
            }
        }

        $send_mail = wp_mail($send_to, 'DFDL ' . esc_attr(ucwords($clean_elements['country'])) . ' | Contact Form Submission', $message, $headers);

    }

}

?>

<?php if ( isset($submitted) && true === $submitted ) : ?>
    
    <div class="contact-form submitted">
        <h2>Thanks!</h2>
        <p>We have received your submission. We will follow up shortly.</p>
    </div>

<?php else : ?>

    <script src="https://www.google.com/recaptcha/api.js"></script>

    <div class="contact-form">

    <?php if ( isset($errors['count']) && $errors['count'] > 0 ) : ?>
    
        <input type="hidden" name="contact_form_submitted" id="contact_form_submitted" value="true">
        <div class='error-banner'>
            <p>You missed some fields. Please review the form and try again.</p>
            <?php
                if ( isset($error_messages) && count($error_messages) > 0 ) {
                    echo '<ul>';
                    foreach ($error_messages as $message) {
                        echo '<li>' . $message . '</li>';
                    }
                    echo '</ul>';
                }

            ?>
        </div>

    <?php endif; ?>

        <form id="dfdl-contact" method="post">
            
        <?php
            wp_nonce_field( 'contact-form_malaysia' );
        ?>
        <input type="hidden" id="form_country" name="form_country" value="regional">
        <input type="hidden" name="contact_form_submitted" id="contact_form_submitted" value="false">
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
                $checked = "";
                if ( isset($_POST['solutions']) && ! empty($_POST['solutions']) ) {
                    if ( in_array( "other", $_POST['solutions'])) {
                        $checked = "checked";
                    }
                }
                echo '<label class="checkbox-control"><input type="checkbox" id="solutions_other" name="solutions[]" value="other" class="checkbox" ' .  $checked . '>Other</label>';
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
        <button id="contact-submit"
                name="contact-submit"
                type="submit"
                value="contact-form-submit"
                data-sitekey="6LcuMQIoAAAAALi5Rjl6ZBLemCnl9KHpfTPwih1A" 
                data-callback='onSubmit' 
                data-action='submit'
                class="button green solid submit disabled g-recaptcha">Submit</button>
    </div>

    <script>function onSubmit(token) { document.getElementById("dfdl-contact").submit(); }</script>
    <style>
        .grecaptcha-badge { visibility: hidden; }
        .recaptcha-notice {
            color: #999;
            font-size: x-small;
            margin-top: 2em;
            max-width: 16em;
        }
        .recaptcha-notice a {
            color: var(--color-dfdl-green);
        }
    </style>
    <div class="recaptcha-notice notice">This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.</div>


<?php endif; ?>