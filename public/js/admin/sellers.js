$(document).ready(function() {
    seller_table = $('#seller_table');
    SellerTable = seller_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#seller_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    var seller_id, nRow=null;

    seller_table.on('click', '.btn_edit', function() {
        seller_id = $(this).attr('seller_id');
        nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/admin/sellers/get',
            method:'post',
            data : {seller_id : seller_id},
            success : function(data) {
                var seller = data.seller;
                $('#username').val(seller.sellername);
                $('#sold_balance').val(seller.sold_btc);
                $('#unsold_balance').val(seller.unsold_btc);
                $('#items_sold').val(seller.item_sold_btc);
                $('#items_unsold').val(seller.item_unsold_btc);
                $('#btc_address').val(seller.btc_address);
                $('#NewModal').modal('show');
            },
            error : function() {
                toastr['error']('Happening any errors on getting data.');
            }
        });
    });

    var profile_form = $('#profile_form');

    profile_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            sold_balance : {
                required : true
            },
            unsold_balance: {
                required: true
            },
            items_sold : {
                required : true
            },
            items_unsold : {
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

    $('#btn_save').click(function() {
        var sold_balance = $('#sold_balance').val();
        var unsold_balance = $('#unsold_balance').val();
        var items_sold = $('#items_sold').val();
        var items_unsold = $('#items_unsold').val();

        if(profile_form.valid()){
            Metronic.blockUI({
                target: '.modal-content',
                animate: true
            });
            $.ajax({
                url : '/admin/sellers/save',
                method : 'post',
                data : {
                    seller_id : seller_id,
                    sold_balance : sold_balance,
                    unsold_balance : unsold_balance,
                    items_sold : items_sold,
                    items_unsold : items_unsold
                },
                success : function(data) {
                    toastr['success']('Profile is saved successfully !');
                    var sold_balance_html = `<label class="text-primary">$</label><label class="text-danger bold">${sold_balance}</label>`;
                    SellerTable.fnUpdate(sold_balance_html, nRow, 3, false);
                    Metronic.unblockUI('.modal-content');
                    $('#NewModal').modal('hide');
                },
                error : function() {
                    toastr['error']('Happening any errors on saving profile!');
                    Metronic.unblockUI('.modal-content');
                }
            })
        }
    });

    $('#btn_delete').click(function() {
        Metronic.blockUI({
            target: '.modal-content',
            animate: true
        });
        $.ajax({
            url : '/admin/sellers/delete',
            method : 'post',
            data : {
                seller_id : seller_id
            },
            success : function(data) {
                toastr['success']('This seller is deleted successfully!');
                SellerTable.fnDeleteRow(nRow);
                Metronic.unblockUI('.modal-content');
                $('#NewModal').modal('hide');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting seller!');
                Metronic.unblockUI('.modal-content');
            }
        })
    });
});
