$(document).ready(function() {
    history_table = $('#history_table');
    HistoryTable = history_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1,
        "paging": true, "info": true, "searching" : true, "order": [[ 1, "asc" ]]
    });
    var tableWrapper = $('#history_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown


});
