$(document).ready(function() {
    var order_table = $('#order_table');
    var OrderTable = order_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']]
    });
    var tableWrapper = $('#order_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var purchase_id = null;
    var user;

    order_table.on('click', '.btn_open', function() {
        purchase_id = $(this).attr('purchase_id');
        $.ajax({
            url : '/orders/get_order',
            method : 'post',
            data : {
                purchase_id : purchase_id
            },
            success : function(data) {
                $('#order_number').text(purchase_id);
                var purchase = data.purchase;
                $('#country_span').html(`<i class="flag-icon flag-icon-${purchase.country.toLowerCase()}"></i> ${purchase.country_full}`);
                $('#type_span').text(purchase.type);
                $('#price_span').html(`<b class="text-primary">$</b><b class="text-danger">${purchase.price}</b>`);
                $('#seller_span').html(`seller ${purchase.id}`);
                $('#info_span').html(purchase.url);
                $('#description_span').html(purchase.infos);
                $('#OrderModal').modal('show');
            },
            error : function() {
                toastr['error']('Happening any errors on getting order');
            }
        });
    });

    var report_id;

    order_table.on('click', '.btn_report', function() {
        purchase_id = $(this).attr('purchase_id');
        user = $(this).attr('user');
        report_id = $(this).attr('report_id');

        if(report_id != '') {
            $.ajax({
                url : '/orders/get_report',
                method : 'post',
                data : {
                    report_id : report_id
                },
                success : function(data) {
                    var report = data.report;
                    $('#report_history').html(report.memo);
                    $('#order_id').text(purchase_id);
                    $('#order_type').text($(this).attr('purchase_type'));
                    $('#ReportModal').modal('show');
                },
                error : function() {
                    toastr['error']('Happening any errors on getting report');
                }
            })
        } else {
            $('#report_history').html('');
            $('#order_id').text(purchase_id);
            $('#order_type').text($(this).attr('purchase_type'));
            $('#ReportModal').modal('show');
        }
    });


    $('#reply').keydown(function(e) {
        console.log(e.which);
        $(this).closest('.input-group').removeClass('has-error');
        const keyCode = e.which || e.keyCode;
        console.log(report_id);
        if (keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            if($(this).val() != ''){
                console.log('send>>>>', $(this).val());
                var msg = $(this).val();
                $(this).val('');
                $.ajax({
                    url : '/orders/report',
                    method : 'post',
                    data : {
                        report_id : report_id,
                        message : msg,
                        purchase_id : purchase_id
                    },
                    success : function(data) {
                        if(data.report) {
                            report_id = data.report.id;
                            $(`#order_${purchase_id}`).attr('report_id', report_id);
                            console.log(report_id);
                        }
                        toastr['success']('Replied successfully !');
                        var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                        $('#report_history').append(msg_html);
                        $('#report_history').scrollTop( $('#report_history')[0].scrollHeight );
                    },
                    error : function() {
                        toastr['error']('Happening any errors on reply');
                    }
                });
            } else {
                toastr['error']('Please enter message!');
                $(this).closest('.input-group').addClass('has-error');
            }

        }
    });

    $('#btn_send').click(function() {
        if($('#reply').val() != ''){
            console.log('send>>>>', $('#reply').val());
            var msg = $('#reply').val();
            $('#reply').val('');
            $.ajax({
                url : '/orders/report',
                method : 'post',
                data : {
                    report_id : report_id,
                    message : msg,
                    purchase_id : purchase_id
                },
                success : function(data) {
                    if(data.report) {
                        report_id = data.report.id;
                        $(`#order_${purchase_id}`).attr('report_id', report_id);
                        console.log(report_id);
                    }
                    toastr['success']('Replied successfully !');
                    var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                    $('#report_history').append(msg_html);
                    $('#report_history').scrollTop( $('#report_history')[0].scrollHeight );
                },
                error : function() {
                    toastr['error']('Happening any errors on reply');
                }
            });
        } else {
            toastr['error']('Please enter message!');
            $('#reply').closest('.input-group').addClass('has-error');
        }
    })
});
