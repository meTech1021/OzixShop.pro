$(document).ready(function() {
    var Login = function() {
    
        var handleRegister = function() {
    
    
            $('#signup_form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    password_confirmation: {
                        required : true,
                        equalTo: "#password"
                    },
                },
    
                messages: { // custom messages for radio buttons and checkboxes
                    username: {
                        required: "Username is required."
                    },
                    email : {
                        required : 'Email is required.'
                    },
                    password : {
                        required : 'Password is required.'
                    },
                    password_confirmation : {
                        required : "Confirm password."
                    }
                },
    
                invalidHandler: function(event, validator) { //display error alert on form submit   
    
                },
    
                highlight: function(element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },
    
                success: function(label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },
    
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
                        error.insertAfter($('#register_tnc_error'));
                    } else if (element.closest('.input-icon').size() === 1) {
                        error.insertAfter(element.closest('.input-icon'));
                    } else {
                        error.insertAfter(element);
                    }
                },
    
                submitHandler: function(form) {
                    form.submit();
                }
            });
    
            $('#signup_form input').keypress(function(e) {
                if (e.which == 13) {
                    if ($('#signup_form').validate().form()) {
                        $('#signup_form').submit();
                    }
                    return false;
                }
            });
            
            $('#username').keydown(function() {
                $('#username_error').hide();
            });
    
            $('#email').keydown(function() {
                $('#email_error').hide();
            });
    
            $('#password').keydown(function() {
                $('#password_error').hide();
            });
        }
    
        return {
            //main function to initiate the module
            init: function() {

                handleRegister();
    
            }
    
        };
    
    }();

    Login.init();
})