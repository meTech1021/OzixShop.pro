$(document).ready(function() {
    user_table = $('#user_table');
    UserTable = user_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#user_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    user_table.on('click', '.btn_make', function() {
        var user_id = $(this).attr('user_id');
        var nRow = $(this).parents('tr')[0];
        $.ajax({
            url : '/admin/users/make_seller',
            method : 'post',
            data : { user_id : user_id },
            success : function(data) {
                toastr['success'](`This user's information is changed successfully !`);
                var btn_html = '<button type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Seller</button>';
                UserTable.fnUpdate(btn_html, nRow, 7, false);
            },
            error : function() {
                toastr['error']('Happening any errors on changing role!');
            }
        })
    });
});
