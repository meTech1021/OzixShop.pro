$(document).ready(function() {
    var Tabaccountvanced = function () {

        var account_table = function () {

            var table = $('#account_table');

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

                "order": [
                    [0, "asc"]
                ]
            });

            var nRow;

            var oTableColReorder = new $.fn.dataTable.ColReorder( oTable );

            var tableWrapper = $('#account_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown


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


                $('#country').change(function() {
                    $('#account_form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
                });
            }

            var reset_form = function() {
                $('#sitename').val('');
                $('#url').val('');
                $('#infos').val('');
                $('#type').val('');
                $('#country').select2('val', '');
                $('#source').val('Hacked');
                $('#price').val('');
                $('#screenshot').val('');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var account_form = $('#account_form');

            account_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    sitename: {
                        required: true
                    },
                    url : {
                        required : true
                    },
                    infos : {
                        required : true
                    },
                    country : {
                        required : true
                    },
                    type : {
                        required : true
                    },
                    screenshot: {
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
                    target: '#account_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/account_delete',
                    method : 'post',
                    data : {
                        account_id : $(this).attr('account_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This account is deleted successfully !');
                        Metronic.unblockUI('#account_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting account !');
                        Metronic.unblockUI('#account_table');
                    }
                })
            });

            $('#btn_save').click(function() {
                var sitename = $('#sitename').val();
                var url = $('#url').val();
                var infos = $('#infos').val();
                var country = $('#country').val();
                var type = $('#type').val();
                var price = $('#price').val();
                var screenshot = $('#screenshot').val();
                var source = $('#source').val();

                if(account_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/account_save',
                        method : 'post',
                        data : {
                            sitename : sitename,
                            price : price,
                            url : url,
                            infos : infos,
                            country : country,
                            type : type,
                            screenshot : screenshot,
                            source : source
                        },
                        success : function(data) {
                            if(data.msg === 'success') {

                                var account = data.account;
                                var country_html = `<i class="flag-icon flag-icon-${account.country.toLowerCase()}"></i>${account.country}`;
                                var proof_html = `<button type="button" class="btn btn-sm btn-primary btn_proof" screenshot="${account.screenshot}">Proof</button>`;
                                var price_html = `<label class="text-danger bold">${account.price}</label><label class="text-primary">$</label>`;
                                var action_html = `<button class="btn btn-sm btn-danger btn_remove" type="button" account_id="${account.id}"><i class="fa fa-trash"></i> Remove</button>`;
                                var source_html;
                                if(account.source === 'Hacked') {
                                    source_html = `<label class="text-danger"><i class="fa fa-circle"></i> Hacked</label>`;
                                } else {
                                    source_html = `<label class="text-primary"><i class="fa fa-plus-circle"></i> Created</label>`
                                }
                                oTable.fnAddData([account.id, account.acctype, account.sitename, country_html, account.infos, price_html, source_html, proof_html, account.created_at, action_html]);
                                $('#NewModal').modal('hide');

                                $('#accounts_badge').text(data.account_cnt);
                                $('#account_cnt').text(data.account_cnt);
                                var unsold_cnt = Number($('#unsold_cnt').text());
                                $('#unsold_cnt').text(unsold_cnt+1);
                            } else {
                                toastr['error']('This Website already exists !');
                                $('#link').closest('.form-group').addClass('has-error');
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Happening any errors on saving account !');
                            $('#account_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            table.on('click', '.btn_proof', function() {
                var screenshot = $(this).attr('screenshot');
                window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
            })

            $('#account_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                account_table();
            }

        };

    }();

    Tabaccountvanced.init();
})
