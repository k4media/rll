<div class="contact-form">
    <form id="dfdl-contact" method="post"action="">
    <?php
        global $post;
        wp_nonce_field( 'comment-form_'.$post->ID );
    ?>
    <div class="details">
        <div>
            <label>Name
                <input type="text" id="firstname" name="firstname">
            </label>
        </div>
        <div>
            <label>Surname
                <input type="text" id="lastname" name="lastname">
            </label>
        </div>
        <div>
            <label>Email
                <input type="email" id="email" name="email">
            </label>
        </div>
        <div>
            <label>Phone Number
                <input type="text" id="telephone" name="telephone">
            </label>
        </div>
        <div>
            <label>Company
                <input type="text" id="company" name="company">
            </label>
        </div>
        <div>
            <label>Position
                <input type="text" id="position" name="position">
            </label>
        </div>
    </div>

    <h4>Solutions</h4>
    <div class="solutions">
        <?php
            $solutions = dfdl_get_solutions_tax();
            foreach( $solutions as $s ) {
                echo '<label class="checkbox-control"><input type="checkbox" id="' . esc_attr($s->name) . '" name="' . esc_attr($s->name) . '" value="' . esc_attr($s->term_id) . '" class="checkbox">' . esc_attr($s->name) . '</label>';
            }
        ?>
    </div>

    <h4>Message</h4>
    <textarea id="message" name="message" class="message"></textarea>
    <button type="submit" name="submit" value="contact-form-submit" class="button submit">Submit</button>

</div>
<script>
(function() {
    var validator = new Validator({
        form: document.getElementById('dfdl-contact'),
        rules: {
            email: {
            validate: (val) => val ? '' : 'Please enter a valid email address',
            },
        }
        });

        validator.form.onsubmit = (evn) => {
        evn.preventDefault();
        const values = validator.getValues();
        console.log(values);
        }

        validator.form.onreset = (evn) => {
        const data = validator.reset();
        console.log(data);
    }
})();
</script>