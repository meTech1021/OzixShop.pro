$(document).ready(function() {
    $('.forget_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {
            email: {
                required: true,
                email: true
            }
        },

        messages: {
            email: {
                required: "Email is required."
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
            error.insertAfter(element.closest('.input-icon'));
        },

        submitHandler: function(form) {
            form.submit();
        }
    });

    $('.forget_form input').keypress(function(e) {
        if (e.which == 13) {
            if ($('.forget_form').validate().form()) {
                $('.forget_form').submit();
            }
            return false;
        }
    });

    jQuery('#back-btn').click(function() {
        window.history.back();
    });

});
