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

    var mailer_table = $('#mailer_table');
    var mailerTable = mailer_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#mailer_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    var test_email = $('#test_email_span').text();
    mailer_table.on('click', '.btn_chk', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('loading...');
        nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/send/mailers/check',
            method : 'post',
            data : {
                mailer_id : $(this).attr('mailer_id')
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

                mailerTable.fnUpdate(button_html, nRow, 7, false);
            },
            error : function() {
                toastr['error']('Happening any errors on checking !');
            }
        })
    });

    mailer_table.on('click', '.btn_buy', function() {
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
                            table : 'mailers',
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
            target: '#mailer_table',
            animate: true
        });
        $.ajax({
            url : 'mailers/filter',
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
                var mailers = data.mailers;
                console.log(mailers.length);
                mailerTable.fnClearTable();
                if(mailers.length > 0) {
                    mailers.forEach(mailer => {
                        var location = `<i class="flag-icon flag-icon-${mailer.country.toLowerCase()}"></i>${mailer.country}`;
                        var infos = mailer.url.split('|');
                        if(mailer.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }
                        if(mailer.ssl_status === 'HTTPS') {
                            ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                        } else {
                            ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`
                        }

                        if(mailer.hosting_detect == null) {
                            hosting_detect = 'N/A';
                        } else {
                            hosting_detect = mailer.hosting_detect;
                        }
                        var price = `<label class="text-danger bold">${mailer.price}</label><label class="text-primary">$</label>`;
                        var chk_btn = `<button type="button" class="btn btn-sm btn-primary btn_chk" mailer_id="${mailer.id}">Check</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${mailer.id}">Buy</button>`
                        mailerTable.fnAddData([mailer.id, location, ssl_html, source, mailer.infos, price, `seller${mailer.seller_id}`, chk_btn, buy_btn, mailer.created_at]);
                        Metronic.unblockUI('#mailer_table');
                    });
                } else {
                    Metronic.unblockUI('#mailer_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#mailer_table');
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
