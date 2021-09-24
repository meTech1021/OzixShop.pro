$(document).ready(function() {
    var ticket_table = $('#ticket_table');
    var TicketTable = ticket_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']]
    });
    var tableWrapper = $('#ticket_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2
    var ticket_id = null;
    var ticket_form = $('#ticket_form');
    ticket_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            title: {
                required: true
            },
            message : {
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
        if(ticket_form.valid()){
            ticket_form.submit();
        }
    });

    ticket_table.on('click', '.btn_view', function() {
        ticket_id = $(this).attr('ticket_id');
        $('#reply').attr('ticket_id', ticket_id);
        console.log(ticket_id)
        $('#ticket_number').text(ticket_id);
        nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/ticket/get',
            method : 'post',
            data : {
                ticket_id : ticket_id
            },
            success : function(data) {
                var ticket = data.ticket;
                console.log(ticket)
                var date = new Date(ticket.created_at);
                if(ticket.status == 0) {
                    $('#btn_close').addClass('hidden');
                    $('#reply').attr('disabled', 'disabled');
                    $('#btn_send').attr('disabled', 'disabled');
                }
                date = `${date.getFullYear()}-${date.getMonth()}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
                $('#ticket_title').text(ticket.subject);
                $('#ticket_user').text(ticket.user);
                $('#ticket_date').text(date);
                $('#ticket_history').html(ticket.memo);
                $('#TicketModal').modal('show');
            },
            error : function() {
                toastr['error']('Happening any errors on getting ticket!');
            }
        })
    });

    $('#reply').keydown(function(e) {
        ticket_id = $(this).attr('ticket_id');
        console.log(ticket_id);
        $(this).closest('.input-group').removeClass('has-error');
        const keyCode = e.which || e.keyCode;
        var ticket_id = $('#btn_close').attr('ticket_id');
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
                    url : '/ticket/reply',
                    method : 'post',
                    data : {
                        ticket_id : ticket_id,
                        message : msg
                    },
                    success : function(data) {
                        toastr['success']('Replied successfully !');
                        var user = $('#ticket_user').text();
                        var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="ticket"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                        $('#ticket_history').append(msg_html);
                        $('#ticket_history').scrollTop( $('#ticket_history')[0].scrollHeight );
                        TicketTable.fnUpdate(data.date, nRow, 5, false);
                        TicketTable.fnUpdate(user, nRow, 4, false);
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
        console.log(ticket_id)
        $('#reply').closest('.input-group').removeClass('has-error');
        if($('#reply').val() != ''){
            Metronic.blockUI({
                target: '.modal-content',
                animate: true
            });
            var msg = $('#reply').val();
            $('#reply').val('');
            $.ajax({
                url : '/ticket/reply',
                method : 'post',
                data : {
                    ticket_id : ticket_id,
                    message : msg
                },
                success : function(data) {
                    toastr['success']('Replied successfully !');
                    var user = $('#ticket_user').text();
                    var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="ticket"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-primary">${user}</div> - ${data.date}</div></div>`
                    $('#ticket_history').append(msg_html);
                    $('#ticket_history').scrollTop( $('#ticket_history')[0].scrollHeight );
                    TicketTable.fnUpdate(data.date, nRow, 5, false);
                    TicketTable.fnUpdate(user, nRow, 4, false);
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
        Metronic.blockUI({
            target: '.modal-content',
            animate: true
        });
        $.ajax({
            url : '/ticket/close',
            method : 'post',
            data : { ticket_id : ticket_id },
            success : function(data) {
                toastr['success']('Ticket is closed successfully !');
                TicketTable.fnUpdate(`<label class="text-danger bold"><i class="fa fa-times"></i> Closed</label>`, nRow, 3, false);
                $(this).addClass('hidden');
                var ticket_cnt = Number($('#ticket_cnt').text());
                $('#ticket_cnt').text(ticket_cnt-1);
                Metronic.unblockUI('.modal-content');
            },
            error : function() {
                toastr['error']('Happening any errors on closing ticket !');
                Metronic.unblockUI('.modal-content');
            }
        })
    });
});
