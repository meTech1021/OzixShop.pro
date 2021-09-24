$(document).ready(function() {
    ticket_table = $('#ticket_table');
    TicketTable = ticket_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#ticket_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    var form_reset = function() {
        $('#title').val('');
        $('#username').val('');
    }

    var nRow = null;
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
            username : {
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

    $('#btn_new').click(function() {
        form_reset();
        $('#NewModal').modal('show');
    });

    $('#btn_save').click(function() {
        var title = $('#title').val();
        var username = $('#username').val();

        if(ticket_form.valid()) {
            Metronic.blockUI({
                target: '.modal-content',
                animate: true
            });
            $.ajax({
                url : '/admin/tickets/insert',
                method : 'post',
                data : {
                    title : title,
                    username : username
                },
                success : function(data) {
                    var ticket = data.ticket;
                    toastr['success']('Ticket is inserted successfully !');
                    var status_html, view_html;
                    if(ticket.status == 0) {
                        status_html = '<label class="text-danger bold"><i class="fa fa-times-circle"></i> Closed</label>';
                    } else {
                        status_html = '<label class="text-primary bold"><i class="fa fa-check-circle"></i> Open</label>';
                    }
                    view_html = `<button type="button" class="btn btn-primary btn-sm btn_view" ticket_id="${ticket.id}"><i class="fa fa-eye"></i> View</button>`;
                    nRow = TicketTable.fnAddData([ticket.id, ticket.user, ticket.created_at, ticket.subject, status_html, ticket.last_reply, ticket.updated_at, view_html]);
                    Metronic.unblockUI('.modal-content');
                    $('#NewModal').modal('hide');
                },
                error : function() {
                    toastr['error']('Happening any errors on inserting ticket !');
                    Metronic.unblockUI('.modal-content');
                }
            });
        }
    });

    ticket_table.on('click', '.btn_view', function() {
        ticket_id = $(this).attr('ticket_id');
        $('#ticket_number').text(ticket_id);
        nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/admin/tickets/get',
            method : 'post',
            data : {
                ticket_id : ticket_id
            },
            success : function(data) {
                var ticket = data.ticket;
                if(ticket.status == 0) {
                    $('#btn_close').addClass('hidden');
                }
                var date = new Date(ticket.created_at);
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
                url : '/admin/tickets/reply',
                method : 'post',
                data : {
                    ticket_id : ticket_id,
                    message : msg
                },
                success : function(data) {
                    toastr['success']('Replied successfully !');
                    var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="ticket"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - ${data.date}</div></div>`
                    $('#ticket_history').append(msg_html);
                    $('#ticket_history').scrollTop( $('#ticket_history')[0].scrollHeight );
                    TicketTable.fnUpdate(data.date, nRow, 6, false);
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
    })

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
                    url : '/admin/tickets/reply',
                    method : 'post',
                    data : {
                        ticket_id : ticket_id,
                        message : msg
                    },
                    success : function(data) {
                        toastr['success']('Replied successfully !');
                        var msg_html = `<div class="panel panel-default" style="border : 1px solid #00bbb1!important;"><div class="panel-body"><div class="ticket"><b>${msg}</b></div></div><div class="panel-footer"><div class="label label-danger">Admin</div> - ${data.date}</div></div>`
                        $('#ticket_history').append(msg_html);
                        $('#ticket_history').scrollTop( $('#ticket_history')[0].scrollHeight );
                        TicketTable.fnUpdate(data.date, nRow, 6, false);
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

    $('#btn_close').click(function() {
        Metronic.blockUI({
            target: '.modal-content',
            animate: true
        });
        $.ajax({
            url : '/admin/tickets/close',
            method : 'post',
            data : { ticket_id : ticket_id },
            success : function(data) {
                toastr['success']('Ticket is closed successfully !');
                TicketTable.fnDeleteRow(nRow);
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
