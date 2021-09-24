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

    var cpanel_table = $('#cpanel_table');
    var CpanelTable = cpanel_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#cpanel_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    cpanel_table.on('click', '.btn_chk', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('Checking...');
        nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/hosts/cpanels/check',
            method : 'post',
            data : {
                cpanel_id : $(this).attr('cpanel_id')
            },
            success : function(data) {
                var button_html;
                var msg = data.msg;
                if(msg == 'working') {
                    button_html = '<button class="btn btn-sm btn-success" type="button">Working</button>';
                } else {
                    button_html = '<button class="btn btn-sm btn-danger" type="button">Not Working</button>';
                }

                CpanelTable.fnUpdate(button_html, nRow, 7, false);
            },
            error : function() {
                toastr['error']('Happening any errors on checking !');
            }
        })
    });

    cpanel_table.on('click', '.btn_buy', function() {
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
                            table : 'cpanels',
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
            target: '#cpanel_table',
            animate: true
        });
        $.ajax({
            url : 'cpanels/filter',
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
                var cpanels = data.cpanels;
                console.log(cpanels.length);
                CpanelTable.fnClearTable();
                if(cpanels.length > 0) {
                    cpanels.forEach(cpanel => {
                        var location = `<i class="flag-icon flag-icon-${cpanel.country.toLowerCase()}"></i>${cpanel.country}-${cpanel.country_full}-${cpanel.city}`;
                        var infos = cpanel.url.split('|');
                        if(cpanel.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }
                        if(cpanel.ssl_status === 'HTTPS') {
                            ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                        } else {
                            ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`
                        }
                        var url = cpanel.url.split('|');
                        var domain = url[0];
                        domain = domain.split('.');
                        var tld = domain[domain.length - 1];
                        tld = '.'+tld.split(':')[0];

                        var price = `<label class="text-danger bold">${cpanel.price}</label><label class="text-primary">$</label>`;
                        var chk_btn = `<button type="button" class="btn btn-sm btn-primary btn_chk" cpanel_id="${cpanel.id}">Check</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${cpanel.id}">Buy</button>`
                        CpanelTable.fnAddData([location, ssl_html, source, tld, cpanel.infos, price, `seller${cpanel.seller_id}`, chk_btn, buy_btn, cpanel.created_at]);
                        Metronic.unblockUI('#cpanel_table');
                    });
                } else {
                    Metronic.unblockUI('#cpanel_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#cpanel_table');
            }
        })
    });
});
