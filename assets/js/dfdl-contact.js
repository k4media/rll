var firstname = document.getElementById("firstname");
firstname && firstname.addEventListener("keyup", function() {
    firstname.parentElement.classList.add("dirty");
    if ( ! firstname.value ) {
        set_input_status(firstname, "invalid");
    } else {
        set_input_status(firstname, "valid");
    }
});
var lastname = document.getElementById("lastname");
lastname && lastname.addEventListener("keyup", function() {
    lastname.parentElement.classList.add("dirty");
    if ( ! lastname.value ) {
        set_input_status(lastname, "invalid");
    } else {
        set_input_status(lastname, "valid");
    }
});
var email = document.getElementById("email");
email && email.addEventListener("keyup", function() {
    email.parentElement.classList.add("dirty");
    if ( validateEmail(email.value) == false || ! email.value ) {
        set_input_status(email, "invalid");
    } else {
        set_input_status(email, "valid");
    }
});
var telephone = document.getElementById("telephone");
telephone && telephone.addEventListener("keyup", function() {
    telephone.parentElement.classList.add("dirty");
    if ( ! telephone.value ) {
        set_input_status(telephone, "invalid");
    } else {
        set_input_status(telephone, "valid");
    }
});
var company = document.getElementById("company");
company && company.addEventListener("keyup", function() {
    company.parentElement.classList.add("dirty");
    if ( ! company.value ) {
        set_input_status(company, "invalid");
    } else {
        set_input_status(company, "valid");
    }
});
var position = document.getElementById("position");
position && position.addEventListener("keyup", function() {
    position.parentElement.classList.add("dirty");
    if ( ! position.value ) {
        set_input_status(position, "invalid");
    } else {
        set_input_status(position, "valid");
    }
});
var message = document.getElementById("message");
message && message.addEventListener("keyup", function() {
    message.parentElement.classList.add("dirty");
    if ( ! message.value || parseInt(message.value.length) < 16 ) {
        set_input_status(message, "invalid");
    } else {
        set_input_status(message, "valid");
    }
});
var form_inputs = document.querySelectorAll("#dfdl-contact input[type=text], #dfdl-contact input[type=email], #dfdl-contact textarea");
form_inputs.forEach((el) => {
    el.addEventListener('keyup', () => {
        form_validate();
    });
    el.classList.add("clean");
});
function set_input_status(el, status) {
    el.classList.remove("clean", "valid", "invalid");
    el.parentElement.classList.remove("valid", "invalid");
    el.classList.add(status);
    el.parentElement.classList.add(status);
}
function form_validate() {
    var invalid = 0;
    form_inputs.forEach((el) => {
        if ( el.classList.contains("invalid") || el.classList.contains("clean") ) {
            invalid++;
        }
    });
    if ( invalid == 0 ) {
        document.getElementById("contact-submit").classList.remove("disabled");
    } else {
        document.getElementById("contact-submit").classList.add("disabled");
    }
}
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }
