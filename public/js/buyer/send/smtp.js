$(document).ready(function() {
    function format(state) {
        if (!state.id) return state.text; // optgroup
        return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
    }

    if (jQuery().select2) {
        $("#country").select2({
            placeholder: '<i class="fa fa-map-marker"></i>&nbsp;Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function(m) {
                return m;
            }
        });
    }

    var smtp_table = $('#smtp_table');
    var smtpTable = smtp_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#smtp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    var test_email = $('#test_email_span').text();
    smtp_table.on('click', '.btn_chk', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('loading...');
        nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/send/smtps/check',
            method : 'post',
            data : {
                smtp_id : $(this).attr('smtp_id')
            },
            success : function(data) {
                var button_html;
                var msg = data.msg;
                if(msg == 'working') {
                    button_html = `<button class="btn btn-sm green-meadow" style="text-transform:none;" type="button">Send to ${test_email}</button>`;
                } else if(msg == 'not working') {
                    button_html = '<button class="btn btn-sm btn-danger" type="button">Not Working</button>';

                } else {
                    button_html = '<button type="button" class="btn btn-sm btn-warning">Invalid Email</button>';
                }

                smtpTable.fnUpdate(button_html, nRow, 6, false);
            },
            error : function() {
                toastr['error']('Happening any errors on checking !');
            }
        })
    });

    smtp_table.on('click', '.btn_buy', function() {
        var data_id = $(this).attr('data_id');
        bootbox.dialog({
            message: "<h4>Are you sure?</h4>",
            title: "Buy",
            buttons: {
              success: {
                label: "Yes!",
                className: "green",
                callback: function() {
                    $.ajax({
                        url : '/buy',
                        method : 'post',
                        data : {
                            table : 'smtps',
                            data_id : data_id
                        },
                        success : function(data) {
                            var msg = data.msg;
                            if(msg == 'no balance') {
                                bootbox.alert(`<center><img src="../../imgs/balance.png"><h2><b>No enough balance !</b></h2><h4>Please refill your balance <a class="btn btn-primary btn-xs"  href="/balance" >Add Balance <span class="glyphicon glyphicon-plus"></span></a></h4></center>`);
                            } else if(msg == 'success') {
                                toastr['success']('Successfully buyed.');
                            } else {
                                toastr['info']('Already sold or deleted!');
                            }
                        },
                        error : function() {
                            toastr['error']('Happening any errors on buying !');
                        }
                    })
                }
              },
              danger: {
                label: "No!",
                className: "red",
                callback: function() {
                }
              }
            }
        });
    });

    $('#btn_filter').click(function() {
        var infos = $('#infos').val();
        var country = $('#country').val();
        var source = $('#source').val();
        var seller = $('#seller').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        Metronic.blockUI({
            target: '#smtp_table',
            animate: true
        });
        $.ajax({
            url : 'smtps/filter',
            method : 'post',
            data : {
                infos : infos,
                country : country,
                source : source,
                seller : seller,
                min_price : min_price,
                max_price : max_price
            },
            success : function(data) {
                var smtps = data.smtps;
                console.log(smtps.length);
                smtpTable.fnClearTable();
                if(smtps.length > 0) {
                    smtps.forEach(smtp => {
                        var location = `<i class="flag-icon flag-icon-${smtp.country.toLowerCase()}"></i>${smtp.country}`;
                        var infos = smtp.url.split('|');
                        if(smtp.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }

                        if(smtp.hosting_detect == null) {
                            hosting_detect = 'N/A';
                        } else {
                            hosting_detect = smtp.hosting_detect;
                        }
                        var price = `<label class="text-danger bold">${smtp.price}</label><label class="text-primary">$</label>`;
                        var chk_btn = `<button type="button" class="btn btn-sm btn-primary btn_chk" smtp_id="${smtp.id}">Check</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${smtp.id}">Buy</button>`
                        smtpTable.fnAddData([smtp.id, location, source, smtp.infos, price, `seller${smtp.seller_id}`, chk_btn, buy_btn, smtp.created_at]);
                        Metronic.unblockUI('#smtp_table');
                    });
                } else {
                    Metronic.unblockUI('#smtp_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#smtp_table');
            }
        })
    });

    $('#btn_save').click(function() {
        test_email = $('#test_email').val();
        $.ajax({
            url : 'mailers/change_test_email',
            method : 'post',
            data : {
                test_email : test_email
            },
            success : function(data) {
                toastr['success']('Test Email is changed successfully!');
                $('#test_email_span').text(test_email);
            },
            error : function() {
                toastr['error']('Happening any errors on changing Test Email !');
            }
        });
    });
});
