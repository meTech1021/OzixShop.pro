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

    var other_table = $('#other_table');
    var OtherTable = other_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1, 'order' : [[0, 'desc']],"paging": false, "info": false, "searching" : false
    });
    var tableWrapper = $('#other_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2

    var nRow = null;
    other_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    });

    other_table.on('click', '.btn_buy', function() {
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
                            table : 'scams',
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
        var infos = $('#infos').val()
        var seller = $('#seller').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        var scriptname = $('#scriptname').val();
        var country = $('#country').val();

        Metronic.blockUI({
            target: '#other_table',
            animate: true
        });
        $.ajax({
            url : 'scampage/filter',
            method : 'post',
            data : {
                infos : infos,
                seller : seller,
                min_price : min_price,
                max_price : max_price,
                scriptname : scriptname,
                country : country
            },
            success : function(data) {
                var datas = data.scams;
                console.log(datas.length);
                OtherTable.fnClearTable();
                if(datas.length > 0) {
                    datas.forEach(data => {
                        var location = `<i class="flag-icon flag-icon-${data.country.toLowerCase()}"></i>${data.country}`;
                        var price = `<label class="text-danger bold">${data.price}</label><label class="text-primary">$</label>`;
                        var proof_btn = `<button class="btn btn-sm btn-primary btn_proof" type="button" screenshot="${data.screenshot}">View Proof</button>`;
                        var buy_btn = `<button type="button" class="btn btn-sm btn-danger btn_buy" data_id="${data.id}">Buy</button>`;

                        OtherTable.fnAddData([location, data.scam_name, data.infos, price, `seller${data.seller_id}`, proof_btn, buy_btn, data.created_at]);
                        Metronic.unblockUI('#other_table');
                    });
                } else {
                    Metronic.unblockUI('#other_table');
                }

            },
            error : function() {
                toastr['error']('Happening any errors on filtering!');
                Metronic.unblockUI('#other_table');
            }
        })
    });
});
