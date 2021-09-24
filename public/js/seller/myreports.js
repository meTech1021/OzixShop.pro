$(document).ready(function() {
    var pending_table = $('#pending_report_table');

    /* Fixed header extension: http://datatables.net/extensions/keytable/ */

    var pTable = pending_table.dataTable({
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
            [0, "desc"]
        ]
    });

    var pTableColReorder = new $.fn.dataTable.ColReorder( pTable );

    var tableWrapper = $('#pending_report_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    var report_table = $('#report_table');

    /* Fixed header extension: http://datatables.net/extensions/keytable/ */

    var rTable = report_table.dataTable({
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
            [0, "desc"]
        ]
    });

    var rTableColReorder = new $.fn.dataTable.ColReorder( report_table );

    var tableWrapper = $('#report_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    report_table.on('click', '.report_tr', function() {
        var report_id = $(this).attr('report_id');
        console.log(report_id);
        window.location.assign(`/seller/main/myreports/${report_id}`);
    });

    pending_table.on('click', '.report_tr', function() {
        var report_id = $(this).attr('report_id');
        console.log(report_id);
        window.location.assign(`/seller/main/myreports/${report_id}`);
    });
})
