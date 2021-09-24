$(document).ready(function() {
    var TableAdvanced = function () {

        var initTable6 = function () {

            var table = $('#top_seller_table');

            /* Fixed header extension: http://datatables.net/extensions/keytable/ */

            var oTable = table.dataTable({
                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found"
                },
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                "pageLength": 10, // set the initial value,
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [2, "desc"]
                ]
            });

            var oTableColReorder = new $.fn.dataTable.ColReorder( oTable );

            var tableWrapper = $('#top_seller_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            var form_reset = function() {
                $('#title').val('');
                $('#username').val('');
            }

            var nRow = null;

            var ticket_form = $('#ticket_form');

            ticket_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    title: {
                        required: true
                    },
                    username : {
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

            $('#btn_new_ticket').click(function() {
                form_reset();
                $('#NewModal').modal('show');
            });

            $('#btn_save').click(function() {
                var title = $('#title').val();
                var username = $('#username').val();

                if(ticket_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/admin/tickets/insert',
                        method : 'post',
                        data : {
                            title : title,
                            username : username
                        },
                        success : function(data) {
                            var ticket = data.ticket;
                            toastr['success']('Ticket is inserted successfully !');
                            Metronic.unblockUI('.modal-content');
                            $('#NewModal').modal('hide');
                        },
                        error : function() {
                            toastr['error']('Happening any errors on inserting ticket !');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                initTable6();
            }

        };

    }();

    TableAdvanced.init();
})
