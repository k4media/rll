var firstname   = document.getElementById("firstname");
var lastname    = document.getElementById("lastname");
var email       = document.getElementById("email");
var telephone   = document.getElementById("telephone");
var company     = document.getElementById("company");
var position    = document.getElementById("position");
var message     = document.getElementById("message");
var form_inputs = document.querySelectorAll("#dfdl-contact input[type=text], #dfdl-contact input[type=email], #dfdl-contact textarea");
let count = 0;
form_inputs.forEach((el) => {
    if( count == 0 ) {
        el.classList.add("clean");
    }
    el && el.addEventListener('keyup', () => {
        set_clean_dirty(el,"dirty");
        form_validate();
    });
    el && el.addEventListener('blur', () => {
        set_clean_dirty(el,"dirty");
        form_validate();
    });
    if ( document.getElementById("contact_form_submitted").value == "false" ) {
        el.classList.add("clean");
    } else if ( document.getElementById("contact_form_submitted").value == "true" ) {
        el.classList.remove("clean");
        el.classList.add("dirty");
    }
});
count++;
function set_clean_dirty(el, status) {
    el.classList.remove("clean", "dirty");
    el.classList.add(status);
    el.parentElement.classList.remove("clean", "dirty");
    el.parentElement.classList.add(status);
}
function set_input_status(el, status) {
    el.classList.remove("valid", "invalid");
    el.classList.add(status);
    el.parentElement.classList.remove("valid", "invalid");
    el.parentElement.classList.add(status);
}
function form_validate() {
    var invalid = 0;
    var valid   = 0;
    form_inputs.forEach((el) => {
        if ( el.classList.contains("dirty") && el.value ) {
            if ( el.id === "email" ) {
                if ( validateEmail(el.value) == true  ) {
                    set_input_status(email, "valid");
                    valid++;
                } else {
                    set_input_status(email, "invalid");
                    invalid++;
                }
            } else if ( el.id === "telephone" ) {
                if ( el.value.length > 8  ) {
                    set_input_status(telephone, "valid");
                    valid++;
                } else {
                    set_input_status(telephone, "invalid");
                    invalid++;
                }
            } else if ( el.id === "message" ) {
                if ( el.value.length > 16  ) {
                    set_input_status(message, "valid");
                    valid++;
                } else {
                    set_input_status(message, "invalid");
                    invalid++;
                }
            } else if ( el.value.length > 1 ) {
                set_input_status(el, "valid");
                valid++;
            } else {
                set_input_status(el, "invalid");
                invalid++;
            }
        } if ( el.classList.contains("dirty") && ! el.value ) {
            el.classList.remove("valid", "invalid");
            el.parentElement.classList.remove("valid", "invalid");
            invalid++;
        }
    });
    if ( invalid == 0 && valid == 7 ) {
        document.getElementById("contact-submit").classList.remove("disabled");
    } else {
        document.getElementById("contact-submit").classList.add("disabled");
    }
}
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
var form_status = document.getElementById("contact_form_submitted");
if ( form_status && form_status.value == "true" ) {
    document.getElementById("contact-submit").classList.remove("disabled");
}