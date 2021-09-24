$(document).ready(function() {
    var TableAdvanced = function () {

        var lead_table = function () {

            var table = $('#lead_table');

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

            var tableWrapper = $('#lead_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
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
                    $('#lead_form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
                });
            }

            var reset_form = function() {
                $('#link').val('');
                $('#number').val('');
                $('#infos').val('');
                $('#type').val('');
                $('#country').select2('val', '');

                $('#price').val('');
                $('#screenshot').val('');
            }

            $('#btn_new').click(function() {
                reset_form();
                $('#NewModal').modal('show');
            });

            var lead_form = $('#lead_form');

            lead_form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    link: {
                        required: true
                    },
                    number : {
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
                    target: '#lead_table',
                    animate: true
                });
                $.ajax({
                    url : '/seller/management/lead_delete',
                    method : 'post',
                    data : {
                        lead_id : $(this).attr('lead_id')
                    },
                    success : function(data) {
                        oTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                        var deleted_cnt = Number($('#deleted_cnt').text());
                        var unsold_cnt = Number($('#unsold_cnt').text());
                        $('#unsold_cnt').text(unsold_cnt - 1);
                        $('#deleted_cnt').text(deleted_cnt + 1);
                        toastr['success']('This lead is deleted successfully !');
                        Metronic.unblockUI('#lead_table');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on deleting lead !');
                        Metronic.unblockUI('#lead_table');
                    }
                })
            });

            $('#btn_save').click(function() {
                var link = $('#link').val();
                var number = $('#number').val();
                var infos = $('#infos').val();
                var country = $('#country').val();
                var type = $('#type').val();
                var price = $('#price').val();
                var screenshot = $('#screenshot').val();

                if(lead_form.valid()) {
                    Metronic.blockUI({
                        target: '.modal-content',
                        animate: true
                    });
                    $.ajax({
                        url : '/seller/management/lead_save',
                        method : 'post',
                        data : {
                            link : link,
                            price : price,
                            number : number,
                            infos : infos,
                            country : country,
                            type : type,
                            screenshot : screenshot
                        },
                        success : function(data) {
                            if(data.msg === 'success') {

                                var lead = data.lead;
                                var country_html = `<i class="flag-icon flag-icon-${lead.country.toLowerCase()}"></i>${lead.country}`;
                                var proof_html = `<button type="button" class="btn btn-sm btn-primary btn_proof" screenshot="${lead.screenshot}">Proof</button>`;
                                var price_html = `<label class="text-danger bold">${lead.price}</label><label class="text-primary">$</label>`;
                                var action_html = `<button class="btn btn-sm btn-danger btn_remove" type="button" lead_id="${lead.id}"><i class="fa fa-trash"></i> Remove</button>`;
                                oTable.fnAddData([lead.id, lead.acctype, country_html, lead.infos, lead.number, price_html, proof_html, lead.created_at, action_html]);
                                $('#NewModal').modal('hide');

                                $('#leads_badge').text(data.lead_cnt);
                                $('#lead_cnt').text(data.lead_cnt);
                                var unsold_cnt = Number($('#unsold_cnt').text());
                                $('#unsold_cnt').text(unsold_cnt+1);
                            } else {
                                toastr['error']('This Download Link already exists !');
                                $('#link').closest('.form-group').addClass('has-error');
                            }
                            Metronic.unblockUI('.modal-content');

                        },
                        error : function () {
                            toastr['error']('Happening any errors on saving Lead !');
                            $('#lead_host').closest('.form-group').addClass('has-error');
                            Metronic.unblockUI('.modal-content');
                        }
                    });
                }
            });

            table.on('click', '.btn_proof', function() {
                var screenshot = $(this).attr('screenshot');
                window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
            })

            $('#lead_host').keydown(function() {
                $(this).closest('.form-group').removeClass('has-error');
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                lead_table();
            }

        };

    }();

    TableAdvanced.init();
})
