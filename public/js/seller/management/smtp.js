$(document).ready(function() {
    var TableAdvanced = function () {

        var smtp_table = function () {

            var table = $('#smtp_table');

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
                    [0, "asc"]
                ]
            });

            var nRow;

            var oTableColReorder = new $.fn.dataTable.ColReorder( oTable );

            var tableWrapper = $('#smtp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            var reset_form = function() {
                $('#smtp_host').val('');
                $('#price').val('');
                $('#source').val('Hacked');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var smtp_form = $('#smtp_form');

            smtp_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    smtp_host: {
                        required: true
                    },
                    source: {
                        required: true,
                    },
                    price: {
                        required: true,
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

            table.on('click', '.btn_remove', function() {
                nRow = $(this).parents('tr')[0];
                Metronic.blockUI({
                    target: '#smtp_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/smtp_delete',
                    method : 'post',
                    data : {
                        smtp_id : $(this).attr('smtp_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 7, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This SMTP is deleted successfully !');
                        Metronic.unblockUI('#smtp_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting SMTP !');
                        Metronic.unblockUI('#smtp_table');
                    }
                })
            });

            $('#btn_save').click(function() {
                var smtp_host = $('#smtp_host').val();
                var price = $('#price').val();

                if(smtp_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/smtp_save',
                        method : 'post',
                        data : {
                            smtp_host : smtp_host,
                            price : price,
                            source : $('#source').val()
                        },
                        success : function(data) {
                            if(data.msg === 'success') {
                                var add_hosts = data.add_hosts;
                                if(add_hosts.length > 0) {
                                    for(var i = 0 ; i < add_hosts.length ; i ++) {
                                        var source_html, ssl_html;
                                        if(add_hosts[i].source === 'Hacked') {
                                            source_html = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                                        } else {
                                            source_html = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`
                                        }
                                        oTable.fnAddData([add_hosts[i].id, add_hosts[i].acctype, `<i class="flag-icon flag-icon-${add_hosts[i].country.toLowerCase()}"></i> ${add_hosts[i].country}`, source_html, add_hosts[i].url, `<label class="text-danger bold">${add_hosts[i].price}</label><label class="text-primary">$</label>`, add_hosts[i].created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" smtp_id="${add_hosts[i].id}"><i class="fa fa-trash"></i> Remove</button>`])

                                        var unsold_cnt = Number($('#unsold_cnt').text());
                                        $('#unsold_cnt').text(unsold_cnt+1);
                                    }
                                    toastr['success']('New SMTP is saved successfully.');
                                    $('#NewModal').modal('hide');
                                }

                                if(data.exist_hosts.length > 0) {
                                    for(var i = 0 ; i < data.exist_hosts.length; i ++) {
                                        toastr['error'](`${data.exist_hosts[i]} already exists !`);
                                        $('#smtp_host').closest('.form-group').addClass('has-error');
                                    }
                                }


                                $('#smtps_badge').text(data.smtp_cnt);
                                $('#smtp_cnt').text(data.smtp_cnt);
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Host is invalid.');
                            $('#smtp_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#smtp_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                smtp_table();
            }

        };

    }();

    TableAdvanced.init();
})
