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

    var rdp_table = $('#rdp_table');
    var RdpTable = rdp_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#rdp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    rdp_table.on('click', '.btn_chk', function() {
        $(this).attr('disabled', 'disabled');
        $(this).text('Checking...');
        nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/hosts/rdps/check',
            method : 'post',
            data : {
                rdp_id : $(this).attr('rdp_id')
            },
            success : function(data) {
                var button_html;
                var msg = data.msg;
                if(msg == 'working') {
                    button_html = '<button class="btn btn-sm btn-success" type="button">Working</button>';
                } else {
                    button_html = '<button class="btn btn-sm btn-danger" type="button">Not Working</button>';
                }

                RdpTable.fnUpdate(button_html, nRow, 10, false);
            },
            error : function() {
                toastr['error']('Happening any errors on checking !');
            }
        })
    });

    rdp_table.on('click', '.btn_buy', function() {
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
                            table : 'rdps',
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
        var ram = $('#ram').val();
        var windows = $('#windows').val();
        var country = $('#country').val();
        var source = $('#source').val();
        var access = $('#access').val();
        var seller = $('#seller').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        Metronic.blockUI({
            target: '#rdp_table',
            animate: true
        });
        $.ajax({
            url : 'rdps/filter',
            method : 'post',
            data : {
                infos : infos,
                ram : ram,
                windows : windows,
                country : country,
                source : source,
                access : access,
                seller : seller,
                min_price : min_price,
                max_price : max_price
            },
            success : function(data) {
                var rdps = data.rdps;
                console.log(rdps.length);
                RdpTable.fnClearTable();
                if(rdps.length > 0) {
                    rdps.forEach(rdp => {
                        var location = `<i class="flag-icon flag-icon-${rdp.country.toLowerCase()}"></i>${rdp.country}-${rdp.country_full}-${rdp.city}`;
                        var infos = rdp.url.split('|');
                        var ip = infos[0];
                        var username = infos[1];
                        var password = infos[2];
                        username = username.slice(0,2)+'*****';
                        ip = ip.split('.');
                        ip = ip[0]+'.'+ip[1]+'.*.*';
                        if(rdp.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }
                        var price = `<label class="text-danger bold">${rdp.price}</label><label class="text-primary">$</label>`;
                        var chk_btn = `<button type="button" class="btn btn-sm btn-primary btn_chk" rdp_id="${rdp.id}">Check</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${rdp.id}">Buy</button>`
                        RdpTable.fnAddData([location, rdp.infos, username, ip, source, rdp.ram, rdp.windows, rdp.access, price, `seller${rdp.seller_id}`, chk_btn, buy_btn, rdp.created_at]);
                        Metronic.unblockUI('#rdp_table');
                    });
                } else {
                    Metronic.unblockUI('#rdp_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#rdp_table');
            }
        })
    });
});
