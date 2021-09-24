$(document).ready(function() {
    var report_table = $('#report_table');
    var ReportTable = report_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']]
    });
    var tableWrapper = $('#report_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow, report_number;

    report_table.on('click', '.btn_view', function() {
        report_number = $(this).attr('report_id');
        $('#report_number').text(report_number);
        $('#btn_close').attr('report_id', report_number);
        nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/report/get',
            method : 'post',
            data : {
                report_id : report_number
            },
            success : function(data) {
                var report = data.report;
                var date = new Date(report.created_at);
                $('#btn_close').attr('report_id', report.id);
                if(report.status == 0){
                    $('#btn_close').hide();
                    $('#reply').attr('disabled', 'disabled');
                    $('#btn_send').attr('disabled', 'disabled');
                } else {
                    $('#btn_close').show();
                    $('#reply').removeAttr('disabled', 'disabled');
                    $('#btn_send').removeAttr('disabled', 'disabled');
                }
                $('#report_history').html(report.memo);
                $('#ReportModal').modal('show');
            },
            error : function() {
                toastr['error']('Happening any errors on getting report!');
            }
        })
    });

    $('#reply').keydown(function(e) {
        console.log(e.which);
        $(this).closest('.input-group').removeClass('has-error');
        const keyCode = e.which || e.keyCode;
        if (keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            if($(this).val() != ''){
                Metronic.blockUI({
                    target: '.modal-content',
                    animate: true
                });
                console.log('send>>>>', $(this).val());
                var msg = $(this).val();
                $(this).val('');
                $.ajax({
                    url : '/report/reply',
                    method : 'post',
                    data : {
                        report_id : report_number,
                        message : msg
                    },
                    success : function(data) {
                        toastr['success']('Replied successfully !');
                        var user = $('#report_user').val();
                        var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                        $('#report_history').append(msg_html);
                        $('#report_history').scrollTop( $('#report_history')[0].scrollHeight );
                        ReportTable.fnUpdate(data.date, nRow, 7, false);
                        ReportTable.fnUpdate(user, nRow, 6, false);
                        Metronic.unblockUI('.modal-content');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on reply');
                        Metronic.unblockUI('.modal-content');
                    }
                });
            } else {
                toastr['error']('Please enter message!');
                $(this).closest('.input-group').addClass('has-error');
            }

        }
    });

    $('#btn_send').click(function() {
        $('#reply').closest('.input-group').removeClass('has-error');
        if($('#reply').val() != ''){
            Metronic.blockUI({
                target: '.modal-content',
                animate: true
            });
            console.log('send>>>>', $(this).val());
            var msg = $('#reply').val();
            $('#reply').val('');
            $.ajax({
                url : '/report/reply',
                method : 'post',
                data : {
                    report_id : report_number,
                    message : msg
                },
                success : function(data) {
                    toastr['success']('Replied successfully !');
                    var user = $('#report_user').val();
                    var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                    $('#report_history').append(msg_html);
                    $('#report_history').scrollTop( $('#report_history')[0].scrollHeight );
                    ReportTable.fnUpdate(data.date, nRow, 7, false);
                    ReportTable.fnUpdate(user, nRow, 6, false);
                    Metronic.unblockUI('.modal-content');
                },
                error : function() {
                    toastr['error']('Happening any errors on reply');
                    Metronic.unblockUI('.modal-content');
                }
            });
        } else {
            toastr['error']('Please enter message!');
            $('#reply').closest('.input-group').addClass('has-error');
        }
    });
    
    $('#btn_close').click(function() {
        $.ajax({
            url : '/report/close',
            method : 'post',
            data : {
                report_id : $(this).attr('report_id')
            },
            success : function(data) {
                toastr['success']('Successfully closed report.');
                ReportTable.fnUpdate(`<label class="text-danger"><i class="fa fa-times"></i> Closed</label>`, nRow, 5, false);
            },
            error : function() {
                toastr['error']('Error happens on closing report');
            }
        })
    });
});
