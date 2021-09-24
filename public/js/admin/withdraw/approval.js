$(document).ready(function() {
    withdraw_table = $('#withdraw_table');
    WithdrawTable = withdraw_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1,
        "paging": false, "info": false, "searching" : false, "order": [[ 0, "asc" ]]
    });
    var tableWrapper = $('#withdraw_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    var username = seller_id = receive_usd = receive_btc = pending = btc_address = btc = nRow = btc_fee = estimate_fee = total_amount = null;

    var pay_form_reset = function() {
        $('#username').val('');
        $('#seller_name').text('');
        $('#amount').val('');
        $('#amount_btc').val('')
        $('#btc_address').val('');
        $('#estimate_fee').text('0');
        $('#total_fee').text('0');
    }

    withdraw_table.on('click', '.btn_pay', function() {
        Metronic.blockUI({
            target: '.page-content',
            animate: true
        });
        username = $(this).attr('seller');
        receive_usd = $(this).attr('receive_usd');
        receive_btc = $(this).attr('receive_btc');
        btc_address = $(this).attr('btc_address');
        pending = $(this).attr('pending');
        seller_id = $(this).attr('seller_id');
        nRow = $(this).parents('tr')[0];
        total_amount = $(this).attr('total_amount');

        pay_form_reset();
        $.ajax({
            url : '/admin/withdraw/get_detail',
            method : 'post',
            data : {
                receive_usd : $(this).attr('receive_usd'),
                receive_btc : $(this).attr('receive_btc'),
                btc_address : btc_address
            },
            success : function(data) {
                estimate_fee = data.estimate_fee;
                if(data.estimate_fee == null) {
                    estimate_fee = 0;
                }
                btc = data.btc;

                $('#seller_name').text(username);
                $('#username').val(username);
                $('#amount').val(receive_usd);
                $('#amount_btc').val(data.receive_btc_minus_fee);
                $('#btc_address').val(btc_address);
                $('#estimate_fee').text(estimate_fee);
                $('#total_fee').text(data.fee);
                $('#PayModal').modal('show');
                Metronic.unblockUI('.page-content');
            },
            error : function() {
                toastr['error']('Happening any errors on getting data!');
            }
        })
    });

    $('#btn_send').click(function() {
        Metronic.blockUI({
            target: '.modal-content',
            animate: true
        });
        $.ajax({
            url : '/admin/withdraw/pay',
            method : 'post',
            data : {
                seller_id : seller_id,
                username : username,
                receive_usd : receive_usd,
                receive_btc : receive_btc,
                amount_btc : $('#amount_btc').val(),
                btc_address : btc_address,
                btc : btc,
                estimate_fee : estimate_fee
            },
            success : function(data) {

                if(data.msg === 'success') {
                    WithdrawTable.fnDeleteRow(nRow);
                    var total_seller = Number($('#total_seller').text());
                    var total_fee = Number($('#total_fee').text());

                    $('#total_seller').text(total_seller - receive_usd);
                    $('#total_fee').text(total_fee - (receive_usd * 0.65));
                    $('#PayModal').modal('hide');
                    bootbox.dialog({
                        message: `<h4>Withdraw is paid successfully!</h4> <h4>Payment sent Proof : ${data.urlbtc}</h4>`,
                        title: "<h4 class='text-success bold'>Success !</h4>",
                        buttons: {
                          main: {
                            label: "OK!",
                            className: "blue",
                            callback: function() {

                            }
                          }
                        }
                    });
                } else {
                    WithdrawTable.fnDeleteRow(nRow);
                    toastr['error'](data.msg);
                }
                Metronic.unblockUI('.modal-content');
            },
            error : function(data) {

                console.log(data);
                toastr['error']('Happening any errors on payment !');
                Metronic.unblockUI('.modal-content');
            }
        })
    });

    var manual_form_reset = function() {
        $('#manual_username').val('');
        $('#seller_manual_name').text('');
        $('#manual_amount').val('');
        $('#manual_amount_btc').val('')
        $('#manual_btc_address').val('');
        $('#manual_pending').val('');
        $('#manual_fee_rate').val('');
    }

    var manual_form = $('#manual_form');

    manual_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            manual_username: {
                required: true
            },
            manual_amount : {
                required : true
            },
            manual_amount_btc: {
                required: true
            },
            manual_btc_address : {
                required : true
            },
            manual_pending: {
                required: true
            },
            manual_fee_rate : {
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

    withdraw_table.on('click', '.btn_pay_manual', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '.page-content',
            animate: true
        });
        username = $(this).attr('seller');
        receive_usd = $(this).attr('receive_usd');
        receive_btc = $(this).attr('receive_btc');
        btc_address = $(this).attr('btc_address');
        pending = $(this).attr('pending');
        seller_id = $(this).attr('seller_id');
        total_amount = $(this).attr('total_amount');

        manual_form_reset();
        $.ajax({
            url : '/admin/withdraw/get_detail',
            method : 'post',
            data : {
                receive_usd : $(this).attr('receive_usd'),
                receive_btc : $(this).attr('receive_btc'),
                btc_address : btc_address
            },
            success : function(data) {
                estimate_fee = data.estimate_fee;
                if(data.estimate_fee == null) {
                    estimate_fee = 0;
                }
                console.log('estimate_fee>>>>>>>>>', estimate_fee);

                btc = data.btc;
                btc_fee = data.btc_fee;

                $('#seller_manual_name').text(username);
                $('#manual_username').val(username);
                $('#manual_amount').val(receive_usd);
                $('#manual_amount_btc').val(data.receive_btc_minus_fee);
                $('#manual_btc_address').val(btc_address);
                $('#ManualPayModal').modal('show');

                Metronic.unblockUI('.page-content');
            },
            error : function() {
                toastr['error']('Happening any errors on getting data!');
            }
        });
    });

    $('#manual_amount').keyup(function(){
        var amount = $(this).val();
        var amount_btc = (amount*0.65 / btc) - btc_fee;

        $('#manual_amount_btc').val(amount_btc);
        $('#manual_pending').val(receive_usd - amount);
    });

    $('#manual_amount').keyup(function() {
        $('#manual_amount').closest('.form-group').removeClass('has-warning');
    })

    $('#btn_manual_send').click(function() {
        var total_fee = Number($('#total_fee').text());
        console.log(total_fee - (total_amount * 0.35))
        if(manual_form.valid()) {

            if($('#manual_amount').val() > receive_usd*0.65){
                toastr['warning']('Amount USD must be less more than $'+receive_usd*0.65);
                $('#manual_amount').closest('.form-group').addClass('has-warning');
            } else {
                Metronic.blockUI({
                    target: '.modal-content',
                    animate: true
                });
                $.ajax({
                    url : '/admin/withdraw/manual_pay',
                    method : 'post',
                    data : {
                        seller_id : seller_id,
                        username : $('#manual_username').val(),
                        receive_usd : $('#manual_amount').val(),
                        receive_btc : receive_btc,
                        amount_btc : $('#manual_amount_btc').val(),
                        btc_address : $('#manual_btc_address').val(),
                        pending : $('#manual_pending').val(),
                        fee_rate : $('#manual_fee_rate').val(),
                        btc : btc
                    },
                    success : function(data) {

                        if(data.msg === 'success') {
                            WithdrawTable.fnDeleteRow(nRow);
                            var total_seller = Number($('#total_seller').text());
                            var total_fee = Number($('#total_fee').text());

                            $('#total_seller').text(total_seller - receive_usd*0.65);
                            $('#total_fee').text(total_fee - (total_amount * 0.35));

                            $('#ManualPayModal').modal('hide');
                            bootbox.dialog({
                                message: `<h4 class="text-muted bold">Payment sent Proof </h4><a href="${data.urlbtc}" class="text-primary"> ${data.urlbtc}</a>`,
                                title: "<h4 class='text-success bold'>Success !</h4>",
                                buttons: {
                                  main: {
                                    label: "OK!",
                                    className: "blue",
                                    callback: function() {

                                    }
                                  }
                                }
                            });
                        } else {
                            WithdrawTable.fnDeleteRow(nRow);
                            toastr['error'](data.msg);
                        }
                        Metronic.unblockUI('.modal-content');
                    },
                    error : function(data) {

                        console.log(data);
                        toastr['error']('Happening any errors on payment !');
                        Metronic.unblockUI('.modal-content');
                    }
                })
            }

        }
    });
});
