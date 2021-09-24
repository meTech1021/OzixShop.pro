$(document).ready(function() {

    $('#report_history').html(memo);

    $('#reply').keydown(function(e) {
        console.log(e.which);
        $(this).closest('.input-group').removeClass('has-error');
        const keyCode = e.which || e.keyCode;
        var report_id = $('#btn_send').attr('report_id');
        if (keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            if($(this).val() != ''){
                console.log('send>>>>', $(this).val());
                var msg = $(this).val();
                $(this).val('');
                $.ajax({
                    url : '/admin/report/reply',
                    method : 'post',
                    data : {
                        report_id : report_id,
                        message : msg
                    },
                    success : function(data) {
                        toastr['success']('Replied successfully !');
                        var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - ${data.date}</div></div>`
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
            var report_id = $('#btn_send').attr('report_id');
            $.ajax({
                url : '/admin/report/reply',
                method : 'post',
                data : {
                    report_id : report_id,
                    message : msg
                },
                success : function(data) {
                    toastr['success']('Replied successfully !');
                    var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="report"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - ${data.date}</div></div>`
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
    });

    $('#btn_refund').click(function() {
        var report_id = $(this).attr('report_id');
        $.ajax({
            url : '/admin/report/refund',
            method : 'post',
            data : {
                report_id : report_id
            },
            success : function(data) {
                $('#btn_refund').hide();
                toastr['success']('Successfully refunded');
                $('#refund_div').removeClass('hide');
            },
            error : function() {
                toastr['error']('Error happens on refunding.');
            }
        })
    });
});
