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

    var shell_table = $('#shell_table');
    var shellTable = shell_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#shell_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    shell_table.on('click', '.btn_chk', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('Checking...');
        nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/hosts/shells/check',
            method : 'post',
            data : {
                shell_id : $(this).attr('shell_id')
            },
            success : function(data) {
                var button_html;
                var msg = data.msg;
                if(msg == 'working') {
                    button_html = '<button class="btn btn-sm btn-success" type="button">Working</button>';
                } else {
                    button_html = '<button class="btn btn-sm btn-danger" type="button">Not Working</button>';
                }

                shellTable.fnUpdate(button_html, nRow, 8, false);
            },
            error : function() {
                toastr['error']('Happening any errors on checking !');
            }
        })
    });

    shell_table.on('click', '.btn_buy', function() {
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
                            table : 'stufs',
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
        var tld = $('#tld').val();
        var country = $('#country').val();
        var source = $('#source').val();
        var seller = $('#seller').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        Metronic.blockUI({
            target: '#shell_table',
            animate: true
        });
        $.ajax({
            url : 'shells/filter',
            method : 'post',
            data : {
                infos : infos,
                tld : tld,
                country : country,
                source : source,
                seller : seller,
                min_price : min_price,
                max_price : max_price
            },
            success : function(data) {
                var shells = data.shells;
                console.log(shells.length);
                shellTable.fnClearTable();
                if(shells.length > 0) {
                    shells.forEach(shell => {
                        var location = `<i class="flag-icon flag-icon-${shell.country.toLowerCase()}"></i>${shell.country}`;
                        var infos = shell.url.split('|');
                        if(shell.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }
                        if(shell.ssl_status === 'HTTPS') {
                            ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                        } else {
                            ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`
                        }
                        var domain = shell.domain;
                        domain = domain.split('.');
                        var tld = domain[domain.length - 1];
                        tld = '.'+tld;

                        if(shell.hosting_detect == null) {
                            hosting_detect = 'N/A';
                        } else {
                            hosting_detect = shell.hosting_detect;
                        }
                        var price = `<label class="text-danger bold">${shell.price}</label><label class="text-primary">$</label>`;
                        var chk_btn = `<button type="button" class="btn btn-sm btn-primary btn_chk" shell_id="${shell.id}">Check</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${shell.id}">Buy</button>`
                        shellTable.fnAddData([location, ssl_html, source, tld, shell.infos, hosting_detect, price, `seller${shell.seller_id}`, chk_btn, buy_btn, shell.created_at]);
                        Metronic.unblockUI('#shell_table');
                    });
                } else {
                    Metronic.unblockUI('#shell_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#shell_table');
            }
        })
    });
});
