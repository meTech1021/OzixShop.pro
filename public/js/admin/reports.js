$(document).ready(function() {
    report_table = $('#report_table');
    ReportTable = report_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#report_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    report_table.on('click', '.report_tr', function() {
        var report_id = $(this).attr('report_id');
        console.log(report_id);
        window.location.assign(`/admin/reports/${report_id}`);
    });
});
