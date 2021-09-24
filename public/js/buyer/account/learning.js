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

    var account_table = $('#account_table');
    var AccountTable = account_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#account_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    account_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    });

    account_table.on('click', '.btn_buy', function() {
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
                            table : 'accounts',
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
        var seller = $('#seller').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        var domain = $('#domain').val();
        Metronic.blockUI({
            target: '#account_table',
            animate: true
        });
        $.ajax({
            url : 'filter/learning',
            method : 'post',
            data : {
                infos : infos,
                country : country,
                seller : seller,
                min_price : min_price,
                max_price : max_price,
                domain : domain
            },
            success : function(data) {
                var accounts = data.accounts;
                console.log(accounts.length);
                AccountTable.fnClearTable();
                if(accounts.length > 0) {
                    accounts.forEach(account => {
                        var location = `<i class="flag-icon flag-icon-${account.country.toLowerCase()}"></i>${account.country}`;

                        var price = `<label class="text-danger bold">${account.price}</label><label class="text-primary">$</label>`;
                        var proof_btn = `<button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="${account.screenshot}">View Proof</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${account.id}">Buy</button>`;
                        if(account.source == 'Hacked') {
                            var source = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                        } else {
                            var source = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`;
                        }
                        var sitename = account.sitename;
                        var domain = (new URL(sitename));
                        AccountTable.fnAddData([domain.hostname, location, account.infos, price, `seller${account.seller_id}`, source, proof_btn, buy_btn, account.created_at]);
                        Metronic.unblockUI('#account_table');
                    });
                } else {
                    Metronic.unblockUI('#account_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#account_table');
            }
        })
    });
});
