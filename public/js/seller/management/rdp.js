$(document).ready(function() {
    var TableAdvanced = function () {

        var rdp_table = function () {

            var table = $('#rdp_table');

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

            var tableWrapper = $('#rdp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

            var reset_form = function() {
                $('#host').val('');
                $('username').val('');
                $('#password').val('');
                $('#access').val('Admin');
                $('#windows').val('ME');
                $('#ram').val('');
                $('#source').val('Hacked');
                $('#price').val('');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var rdp_form = $('#rdp_form');

            rdp_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    host: {
                        required: true
                    },
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    ram: {
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
                var host = $('#host').val();
                var username = $('#username').val();
                var password = $('#password').val();
                var access = $('#access').val();
                var windows = $('#windows').val();
                var ram = $('#ram').val();
                var source = $('#source').val();
                var price = $('#price').val();

                if(rdp_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/rdp_save',
                        method : 'post',
                        data : {
                            host : host,
                            username : username,
                            password : password,
                            access : access,
                            windows : windows,
                            ram : ram,
                            source : source,
                            price : price
                        },
                        success : function(data) {
                            if(data.msg === 'success') {
                                toastr['success']('New RDP is saved successfully.');
                                var rdp = data.rdp;
                                var source_html;
                                if(rdp.source === 'Hacked') {
                                    source_html = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                                } else {
                                    source_html = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`
                                }
                                oTable.fnAddData([rdp.id, rdp.acctype, `<i class="flag-icon flag-icon-${rdp.country.toLowerCase()}"></i>${rdp.country}`, rdp.city, source_html, rdp.infos, rdp.ram, rdp.url, `<label class="text-danger bold">${rdp.price}</label><label class="text-primary">$</label>`, rdp.created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" rdp_id="${rdp.id}"><i class="fa fa-trash"></i> Remove</button>`])
                                $('#NewModal').modal('hide');

                                $('#rdps_badge').text(data.rdps_cnt);
                                $('#rdp_cnt').text(data.rdps_cnt);
                                var unsold_cnt = Number($('#unsold_cnt').text());
                                $('#unsold_cnt').text(unsold_cnt+1);
                            } else if(data.msg === 'not working') {
                                toastr['error']('This Host is not working');
                                $('#host').closest('.form-group').addClass('has-error');
                            } else {
                                toastr['error']('These Username and password for this IP already exists.');
                                $('#host').closest('.form-group').addClass('has-error');
                                $('#username').closest('.form-group').addClass('has-error');
                                $('#password').closest('.form-group').addClass('has-error');
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Your IP address is banned.');
                            $('#host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
            $('#username').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
            $('#password').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });

            table.on('click', '.btn_remove', function() {
                nRow = $(this).parents('tr')[0];
                Metronic.blockUI({
                    target: '#rdp_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/rdp_delete',
                    method : 'post',
                    data : {
                        rdp_id : $(this).attr('rdp_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 10, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This RDP is deleted successfully !');
                        Metronic.unblockUI('#rdp_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting RDP !');
                        Metronic.unblockUI('#rdp_table');
                    }
                })
            });

            var reset_mass_form = function() {
                $('#mass_host').val('');
                $('#mass_access').val('Admin');
                $('#mass_windows').val('ME');
                $('#mass_ram').val('');
                $('#mass_source').val('Hacked');
                $('#mass_price').val('');
            }

            $('#btn_mass_new').click(function() {
                reset_mass_form();
                $('#NewMassModal').modal('show');
            });

            var rdp_mass_form = $('#rdp_mass_form');

            rdp_mass_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    mass_host: {
                        required: true
                    },
                    mass_ram: {
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
                var mass_host = $('#mass_host').val();
                var mass_access = $('#mass_access').val();
                var mass_windows = $('#mass_windows').val();
                var mass_ram = $('#mass_ram').val();
                var mass_source = $('#mass_source').val();
                var mass_price = $('#mass_price').val();

                if(rdp_mass_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/rdp_mass_save',
                        method : 'post',
                        data : {
                            host : mass_host,
                            access : mass_access,
                            windows : mass_windows,
                            ram : mass_ram,
                            source : mass_source,
                            price : mass_price
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
                                        oTable.fnAddData([add_hosts[i].id, add_hosts[i].acctype, `<i class="flag-icon flag-icon-${add_hosts[i].country.toLowerCase()}"></i>${add_hosts[i].country}`, add_hosts[i].city, source_html, add_hosts[i].infos, add_hosts[i].ram, add_hosts[i].url,  `<label class="text-danger bold">${add_hosts[i].price}</label><label class="text-primary">$</label>`, add_hosts[i].created_at, `<button type="button" class="btn btn-sm btn-danger btn_remove" rdp_id="${add_hosts[i].id}"><i class="fa fa-trash"></i> Remove</button>`])

                                        var unsold_cnt = Number($('#unsold_cnt').text());
                                        $('#unsold_cnt').text(unsold_cnt+1);
                                    }
                                    toastr['success']('New Host/IP is saved successfully.');
                                    $('#NewMassModal').modal('hide');
                                }

                                if(data.not_working_hosts.length > 0) {
                                    for(var i = 0 ; i < data.not_working_hosts.length; i ++) {
                                        toastr['error'](`${data.not_working_hosts[i]} is not working !`);
                                        $('#mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }

                                if(data.exist_hosts.length > 0) {
                                    for(var i = 0 ; i < data.exist_hosts.length; i ++) {
                                        toastr['error'](`${data.exist_hosts[i]} already exists !`);
                                        $('#mass_host').closest('.form-group').addClass('has-error');
                                    }
                                }


                                $('#rdps_badge').text(data.rdps_cnt);
                                $('#rdp_cnt').text(data.rdps_cnt);
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Your IP address is banned.');
                            $('#host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            $('#mass_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                rdp_table();
            }

        };

    }();

    TableAdvanced.init();
})
