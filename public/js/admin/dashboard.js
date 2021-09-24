$(document).ready(function() {
    ticket_table = $('#ticket_table');
    tTable = ticket_table.dataTable( { "paging": false, "info": false, "searching" : false, "order": [[ 0, "desc" ]] });

    user_table = $('#user_table');
    uTable = user_table.dataTable( { "paging": false, "info": false, "searching" : false, "order": [[ 2, "desc" ]] });

    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

    function saledrawChart() {
        var saleData = Array();
        saleData.push(['Day', 'Sales $']);
        salesLength = sales.length;
        for(var i = 1 ; i <= salesLength ; i ++) {
            saleData.push([sales[salesLength-i][0].day,  sales[salesLength-i][0].sale]);
        }
        var data = google.visualization.arrayToDataTable(saleData);

        var options = {
          vAxis: {minValue: 0},
		  colors: ['navy','#001f3f'],
		    animation:{
                startup: 'True',
                duration: 1000,
                easing: 'out',
            }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('sale_chart'));
        chart.draw(data, options);
    }
    console.log(users);
    google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(saledrawChart);

    function drawChart() {
        var userData = Array();
        userData.push(['Day', 'New Users']);
        usersLength = users.length;
        for(var i = 1 ; i <= usersLength ; i ++) {
            userData.push([users[usersLength-i][0].day,  users[usersLength-i][0].users]);
        }
        var data = google.visualization.arrayToDataTable(userData);

        var options = {
          vAxis: {minValue: 0},
		  colors: ['orange','#FFA500'],
		    animation:{
                startup: 'True',
                duration: 1000,
                easing: 'out',
            }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('user_chart'));
        chart.draw(data, options);
    }
    var nRow = null;

    ticket_table.on('click', '.btn_view', function() {
        var ticket_number = $(this).attr('ticket_id');
        $('#ticket_number').text(ticket_number);
        $('#btn_close').attr('ticket_id', ticket_number);
        nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/admin/tickets/get',
            method : 'post',
            data : {
                ticket_id : ticket_number
            },
            success : function(data) {
                var ticket = data.ticket;
                var date = new Date(ticket.created_at);
                if(ticket.status == 0) {
                    $('#btn_close').addClass('hidden');
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
        console.log(e.which);
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
                        tTable.fnUpdate(data.date, nRow, 3, false);
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
        var ticket_id = $('#btn_close').attr('ticket_id');
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
                    tTable.fnUpdate(data.date, nRow, 3, false);
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

});
