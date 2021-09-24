$(document).ready(function() {
    var balance_table = $('#balance_table');
    var BalanceTable = balance_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']]
    });
    var tableWrapper = $('#balance_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var balance_form = $('#balance_form');
    balance_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            method: {
                required: true
            },
            amount : {
                required : true
            },
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
    $('#btn_submit').click(function() {
        if(balance_form.valid()){
            if($('#amount').val() < 5) {
                toastr['error']('Please enter a valid amount and Minimum of 5$ for Bitcoin And Perfect Money');
                $('#amount').closest('.form-group').addClass('has-error');
            } else {
                balance_form.submit();
            }
        }
    });

    $('#amount').keyup(function() {
        $('#amount').closest('.form-group').removeClass('has-error');
    });
    
    $('#method').change(function(){
        var method = $(this).val();
        if(method == 'PerfectMoneyPayment') {
            $('#btn_submit').attr('disabled', 'disabled');
        } else {
            $('#btn_submit').removeAttr('disabled', 'disabled');
        }
    })
});
