$(document).ready(function() {

    seller_table = $('#snew_table');
    sTable = seller_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#snew_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    buyer_table = $('#bnew_table');
    bTable = buyer_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#bnew_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    var form_reset = function() {
        $('#title').val('');
        $('#content').val('');
    }

    var news_form = $('#news_form');

    news_form.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input
        rules: {
            title: {
                required: true
            },
            content : {
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
    $('#btn_buyer_new').click(function() {
        form_reset();
        $('#type').val(2);
        $('#NewModal').modal('show');
    });

    $('#btn_seller_new').click(function() {
        form_reset();
        $('#type').val(1);
        $('#NewModal').modal('show');
    });

    $('#btn_save').click(function() {
        if(news_form.valid()) {
            Metronic.blockUI({
                target: '.modal-content',
                animate: true
            });
            var type = $('#type').val();
            var title = $('#title').val();
            var content = $('#content').val();
            $.ajax({
                url : '/admin/news_save',
                method : 'post',
                data : {
                    type : type,
                    title : title,
                    content : content
                },
                success : function (data) {
                    toastr['success']('News is saved successfully !');
                    var news = data.news;
                    var button_html = `<button type="button" class="btn btn-sm btn-danger btn_delete" news_id="${news.id}"><i class="fa fa-trash"></i> Delete</button>`;
                    if(news.type == 1) {
                        sTable.fnAddData([news.id, news.created_at, news.title, news.content.substr(0, 50), button_html]);
                    } else {
                        bTable.fnAddData([news.id, news.created_at, news.title, news.content.substr(0, 50), button_html]);
                    }
                    Metronic.unblockUI('.modal-content');
                    $('#NewModal').modal('hide');
                },
                error : function() {
                    toastr['error']('Happening any errors on saving news !');
                    Metronic.unblockUI('.modal-content');
                }
            });
        }
    });

    seller_table.on('click', '.btn_delete', function() {
        var news_id = $(this).attr('news_id');
        var nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/admin/news_delete',
            method: 'post',
            data : { news_id : news_id },
            success : function(data) {
                toastr['success']('This news is deleted successfully !');
                sTable.fnDeleteRow(nRow);
            },
            error : function() {
                toastr['error']('Happening any errors on deleting news !');
            }
        })
    });

    buyer_table.on('click', '.btn_delete', function() {
        var news_id = $(this).attr('news_id');
        var nRow = $(this).parents('tr')[0];

        $.ajax({
            url : '/admin/news_delete',
            method: 'post',
            data : { news_id : news_id },
            success : function(data) {
                toastr['success']('This news is deleted successfully !');
                bTable.fnDeleteRow(nRow);
            },
            error : function() {
                toastr['error']('Happening any errors on deleting news !');
            }
        })
    });
});
