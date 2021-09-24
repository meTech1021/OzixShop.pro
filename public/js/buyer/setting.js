$(document).ready(function() {
    var setting_form = $('#setting_form');
    setting_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            current_password: {
                required: true
            },
            password : {
                required : true
            },
            password_confirmation : {
                required : true,
                equalTo: "#password"
            },
            email : {
                required : true
            }
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error'); // set success class to the control group
        },
    });

    var initialized = false;
    var input = $("#password");

    input.keydown(function () {
        if (initialized === false) {
            // set base options
            input.pwstrength({
                raisePower: 1.4,
                minChar: 8,
                scores: [17, 26, 40, 50, 60]
            });

            // add your own rule to calculate the password strength
            input.pwstrength("addRule", "demoRule", function (options, word, score) {
                return word.match(/[a-z].[0-9]/) && score;
            }, 10, true);

            // set as initialized
            initialized = true;
        }
    });

    $('#btn_submit').click(function() {
        if(setting_form.valid()) {
            $.ajax({
                url : 'setting/save',
                method : 'post',
                data : {
                    current_password : $('#current_password').val(),
                    password : $('#password').val(),
                    password_confirmation : $('#password_confirmation').val(),
                    email : $('#email').val()
                },
                success : function(data) {
                    var msg = data.msg;
                    if(msg === 'Incorrect current password') {
                        toastr['error'](msg);
                        $('#current_password').closest('.form-group').addClass('has-error');
                    } else if(msg === 'New password is not equal to confirm password') {
                        toastr['error'](msg);
                        $('#password').closest('.form-group').addClass('has-error');
                        $('#password_confirmation').closest('.form-group').addClass('has-error');
                    } else if(msg === 'This email already exists') {
                        toastr['error'](msg);
                        $('#email').closest('.form-group').addClass('has-error');
                    } else if(msg === 'Successfully changed') {
                        toastr['success'](msg);
                        $('#current_password').val('');
                        $('#password').val('');
                        $('#password_confirmation').val('');
                        $('.progress').hide();
                        $('.password-verdict').hide();
                    }

                },
                error : function() {
                    toastr['error']('Happening any errors on changing account.');
                }
            })
        }
    });

    $('#current_password').keyup(function() {
        $('#current_password').closest('.form-group').removeClass('has-error');
    });

    $('#password').keyup(function() {
        $('#password').closest('.form-group').removeClass('has-error');
        $('.progress').show();
        $('.password-verdict').show();
    });

    $('#password_confirmation').keyup(function() {
        $('#password_confirmation').closest('.form-group').removeClass('has-error');
    });

    $('#email').keyup(function() {
        $('#email').closest('.form-group').removeClass('has-error');
    });

});
