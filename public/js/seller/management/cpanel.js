$(document).ready(function() {
    var TableAdvanced = function () {

        var cpanel_table = function () {

            var table = $('#cpanel_table');

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

            var tableWrapper = $('#cpanel_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            var reset_form = function() {
                $('#cpanel_host').val('');
                $('#cpanel_username').val('');
                $('#cpanel_password').val('');
                $('#price').val('');
                $('#source').val('Hacked');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var cpanel_form = $('#cpanel_form');

            cpanel_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    cpanel_host: {
                        required: true
                    },
                    cpanel_username: {
                        required: true,
                    },
                    cpanel_password: {
                        required: true,
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

            $('#btn_save').click(function() {
                var cpanel_host = $('#cpanel_host').val();
                var cpanel_username = $('#cpanel_username').val();
                var cpanel_password = $('#cpanel_password').val();
                var price = $('#price').val();

                if(cpanel_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/cpanel_save',
                        method : 'post',
                        data : {
                            cpanel_host : cpanel_host,
                            cpanel_username : cpanel_username,
                            cpanel_password : cpanel_password,
                            price : price,
                            source : $('#source').val()
                        },
                        success : function(data) {
                            if(data.msg === 'success') {
                                toastr['success']('New cPanel is saved successfully.');
                                var cpanel = data.cpanel;
                                var source_html, ssl_html;
                                if(cpanel.source === 'Hacked') {
                                    source_html = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                                } else {
                                    source_html = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`
                                }

                                if(cpanel.ssl_status === 'HTTPS') {
                                    ssl_html = `<label class="text-success"><i class="fa fa-lock"></i> HTTPS</label>`;
                                } else {
                                    ssl_html = `<label class="text-muted"><i class="fa fa-unlock"></i> HTTP</label>`
                                }
                                oTable.fnAddData([cpanel.id, cpanel.acctype, `<i class="flag-icon flag-icon-${cpanel.country.toLowerCase()}"></i>${cpanel.country}`, source_html, ssl_html, cpanel.url, `<label class="text-danger bold">${cpanel.price}</label><label class="text-primary">$</label>`, cpanel.created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" cpanel_id="${cpanel.id}"><i class="fa fa-trash"></i> Remove</button>`])
                                $('#NewModal').modal('hide');

                                $('#cpanels_badge').text(data.cpanel_cnt);
                                $('#cpanel_cnt').text(data.cpanel_cnt);
                                var unsold_cnt = Number($('#unsold_cnt').text());
                                $('#unsold_cnt').text(unsold_cnt+1);
                            } else if(data.msg = 'not working') {
                                toastr['error']('This Host is not working.');
                                $('#cpanel_host').closest('.form-group').addClass('has-error');
                            } else {
                                toastr['error']('This Host already exists.');
                                $('#cpanel_host').closest('.form-group').addClass('has-error');
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('IP Address is invalid.');
                            $('#cpanel_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#cpanel_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });

            table.on('click', '.btn_remove', function() {
                nRow = $(this).parents('tr')[0];
                Metronic.blockUI({
                    target: '#cpanel_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/cpanel_delete',
                    method : 'post',
                    data : {
                        cpanel_id : $(this).attr('cpanel_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This cPanel is deleted successfully !');
                        Metronic.unblockUI('#cpanel_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting cPanel !');
                        Metronic.unblockUI('#cpanel_table');
                    }
                })
            });


            var reset_mass_form = function() {
                $('#cpanel_mass_host').val('');
                $('#mass_price').val('');
                $('#mass_source').val('Hacked');
            }

            $('#btn_mass_new').click(function() {
                reset_mass_form();
                $('#NewMassModal').modal('show');
            });

            var cpanel_mass_form = $('#cpanel_mass_form');

            cpanel_mass_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    cpanel_mass_host: {
                        required: true
                    },
                    mass_source: {
                        required: true,
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
                var cpanel_mass_host = $('#cpanel_mass_host').val();
                var mass_price = $('#mass_price').val();

                if(cpanel_mass_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/cpanel_mass_save',
                        method : 'post',
                        data : {
                            cpanel_mass_host : cpanel_mass_host,
                            mass_price : mass_price,
                            source : $('#mass_source').val()
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
                                        oTable.fnAddData([add_hosts[i].id, add_hosts[i].acctype, `<i class="flag-icon flag-icon-${add_hosts[i].country.toLowerCase()}"></i>${add_hosts[i].country}`, source_html, ssl_html, add_hosts[i].url, `<label class="text-danger bold">${add_hosts[i].price}</label><label class="text-primary">$</label>`, add_hosts[i].created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" cpanel_id="${add_hosts[i].id}"><i class="fa fa-trash"></i> Remove</button>`])

                                        var unsold_cnt = Number($('#unsold_cnt').text());
                                        $('#unsold_cnt').text(unsold_cnt+1);
                                    }
                                    toastr['success']('New cPanel is saved successfully.');
                                    $('#NewMassModal').modal('hide');
                                }

                                if(data.not_working_hosts.length > 0) {
                                    for(var i = 0 ; i < data.not_working_hosts.length; i ++) {
                                        toastr['error'](`${data.not_working_hosts[i]} is not working !`);
                                        $('#cpanel_mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }

                                if(data.exist_hosts.length > 0) {
                                    for(var i = 0 ; i < data.exist_hosts.length; i ++) {
                                        toastr['error'](`${data.exist_hosts[i]} already exists !`);
                                        $('#cpanel_mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }


                                $('#cpanels_badge').text(data.cpanel_cnt);
                                $('#cpanel_cnt').text(data.cpanel_cnt);
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('IP Address is invalid.');
                            $('#cpanel_mass_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#cpanel_mass_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                cpanel_table();
            }

        };

    }();

    TableAdvanced.init();
})
