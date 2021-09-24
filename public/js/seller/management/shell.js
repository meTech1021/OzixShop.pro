$(document).ready(function() {
    var TableAdvanced = function () {

        var shell_table = function () {

            var table = $('#shell_table');

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

            var tableWrapper = $('#shell_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            var reset_form = function() {
                $('#shell_host').val('');
                $('#source').val('Hacked');
                $('#price').val('');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var shell_form = $('#shell_form');

            shell_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    shell_host: {
                        required: true
                    },
                    source : {
                        required : true
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

            $('#btn_save').click(function() {
                var shell_host = $('#shell_host').val();
                var price = $('#price').val();

                if(shell_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/shell_save',
                        method : 'post',
                        data : {
                            shell_host : shell_host,
                            source : $('#source').val(),
                            price : price
                        },
                        success : function(data) {
                            // alert(data.msg);
                            if(data.msg === 'success') {
                                toastr['success']('New Shell is saved successfully.');
                                var shell = data.shell;
                                var source_html, ssl_html;
                                if(shell.source === 'Hacked') {
                                    source_html = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                                } else {
                                    source_html = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`
                                }
                                console.log(source_html)

                                if(shell.ssl_status === 'HTTPS') {
                                    ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                                } else {
                                    ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`;
                                }
                                oTable.fnAddData([shell.id, shell.acctype, `<i class="flag-icon flag-icon-${shell.country.toLowerCase()}"></i>${shell.country}`, source_html, ssl_html, shell.url, `<label class="text-danger bold">${shell.price}</label><label class="text-primary">$</label>`, shell.created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" shell_id="${shell.id}"><i class="fa fa-trash"></i> Remove</button>`])
                                $('#NewModal').modal('hide');

                                $('#shells_badge').text(data.shell_cnt);
                                $('#shell_cnt').text(data.shell_cnt);
                                var unsold_cnt = Number($('#unsold_cnt').text());
                                $('#unsold_cnt').text(unsold_cnt+1);
                            } else if(data.msg === 'not working') {
                                toastr['error']('This Host URL is not working !');
                                $('#shell_host').closest('.form-group').addClass('has-error');
                            } else {
                                toastr['error']('This Host URL already exists.');
                                $('#shell_host').closest('.form-group').addClass('has-error');
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Host URL is invalid.');
                            $('#shell_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#shell_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });

            table.on('click', '.btn_remove', function() {
                nRow = $(this).parents('tr')[0];
                Metronic.blockUI({
                    target: '#shell_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/shell_delete',
                    method : 'post',
                    data : {
                        shell_id : $(this).attr('shell_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This Shell is deleted successfully !');
                        Metronic.unblockUI('#shell_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting Shell !');
                        Metronic.unblockUI('#shell_table');
                    }
                })
            });


            var reset_mass_form = function() {
                $('#shell_mass_host').val('');
                $('#mass_source').val('Hacked');
                $('#mass_price').val('');
            }

            $('#btn_mass_new').click(function() {
                reset_mass_form();
                $('#NewMassModal').modal('show');
            });

            var shell_mass_form = $('#shell_mass_form');

            shell_mass_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    shell_mass_host: {
                        required: true
                    },
                    mass_source : {
                        required : true
                    },
                    mass_price: {
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

            $('#btn_mass_save').click(function() {
                var shell_mass_host = $('#shell_mass_host').val();
                var mass_price = $('#mass_price').val();

                if(shell_mass_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/shell_mass_save',
                        method : 'post',
                        data : {
                            shell_mass_host : shell_mass_host,
                            mass_source : $('#mass_source').val(),
                            mass_price : mass_price
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

                                        if(add_hosts[i].ssl_status === 'HTTPS') {
                                            ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                                        } else {
                                            ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`
                                        }
                                        oTable.fnAddData([add_hosts[i].id, add_hosts[i].acctype, `<i class="flag-icon flag-icon-${add_hosts[i].country.toLowerCase()}"></i>${add_hosts[i].country}`, source_html, ssl_html, add_hosts[i].url, `<label class="text-danger bold">${add_hosts[i].price}</label><label class="text-primary">$</label>`, add_hosts[i].created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" shell_id="${add_hosts[i].id}"><i class="fa fa-trash"></i> Remove</button>`])

                                        var unsold_cnt = Number($('#unsold_cnt').text());
                                        $('#unsold_cnt').text(unsold_cnt+1);
                                    }
                                    toastr['success']('New Shell is saved successfully.');
                                    $('#NewMassModal').modal('hide');
                                }

                                if(data.not_working_hosts.length > 0) {
                                    for(var i = 0 ; i < data.not_working_hosts.length; i ++) {
                                        toastr['error'](`${data.not_working_hosts[i]} is not working !`);
                                        $('#shell_mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }

                                if(data.exist_hosts.length > 0) {
                                    for(var i = 0 ; i < data.exist_hosts.length; i ++) {
                                        toastr['error'](`${data.exist_hosts[i]} already exists !`);
                                        $('#shell_mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }


                                $('#shells_badge').text(data.shell_cnt);
                                $('#shell_cnt').text(data.shell_cnt);
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Host URL is invalid.');
                            $('#shell_mass_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#shell_mass_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                shell_table();
            }

        };

    }();

    TableAdvanced.init();
})
