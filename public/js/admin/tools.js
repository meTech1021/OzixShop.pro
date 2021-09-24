$(document).ready(function() {

    /** RDP Table */
    rdp_table = $('#rdp_table');
    RdpTable = rdp_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#rdp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    rdp_table.on('click', '.btn_remove', function() {
        var nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#rdp_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/rdp_delete',
            method : 'post',
            data : {
                rdp_id : $(this).attr('rdp_id')
            },
            success : function(data) {
                console.log(data.msg)
                RdpTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                toastr['success']('This RDP is deleted successfully !');
                Metronic.unblockUI('#rdp_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting RDP !');
                Metronic.unblockUI('#rdp_table');
            }
        })
    });
    /** RDP Table End */

    /** Shell Table */
    shell_table = $('#shell_table');
    ShellTable = shell_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#shell_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    shell_table.on('click', '.btn_remove', function() {
        var nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#shell_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/shell_delete',
            method : 'post',
            data : {
                shell_id : $(this).attr('shell_id')
            },
            success : function(data) {
                ShellTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                toastr['success']('This Shell is deleted successfully !');
                Metronic.unblockUI('#shell_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting Shell !');
                Metronic.unblockUI('#shell_table');
            }
        })
    });
    /** Shell Table End */

    /** cPanel Table */
    cpanel_table = $('#cpanel_table');
    CpanelTable = cpanel_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#cpanel_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    cpanel_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#cpanel_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/cpanel_delete',
            method : 'post',
            data : {
                cpanel_id : $(this).attr('cpanel_id')
            },
            success : function(data) {
                CpanelTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                toastr['success']('This cPanel is deleted successfully !');
                Metronic.unblockUI('#cpanel_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting cPanel !');
                Metronic.unblockUI('#cpanel_table');
            }
        })
    });
    /** Cpanel Table End */

    /** Mailer Table */
    mailer_table = $('#mailer_table');
    MailerTable = mailer_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#mailer_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    mailer_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#mailer_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/mailer_delete',
            method : 'post',
            data : {
                mailer_id : $(this).attr('mailer_id')
            },
            success : function(data) {
                MailerTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 8, false);
                toastr['success']('This Mailer is deleted successfully !');
                Metronic.unblockUI('#mailer_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting Mailer !');
                Metronic.unblockUI('#mailer_table');
            }
        })
    });
    /** Mailer Table End */

    /** SMTP Table */
    smtp_table = $('#smtp_table');
    SmtpTable = smtp_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#smtp_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    smtp_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#smtp_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/smtp_delete',
            method : 'post',
            data : {
                smtp_id : $(this).attr('smtp_id')
            },
            success : function(data) {
                SmtpTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 7, false);
                toastr['success']('This SMTP is deleted successfully !');
                Metronic.unblockUI('#smtp_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting SMTP !');
                Metronic.unblockUI('#smtp_table');
            }
        })
    });
    /** SMTP Table End */

    /** Lead Table */
    lead_table = $('#lead_table');
    LeadTable = lead_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#lead_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    lead_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#lead_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/lead_delete',
            method : 'post',
            data : {
                lead_id : $(this).attr('lead_id')
            },
            success : function(data) {
                LeadTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                toastr['success']('This lead is deleted successfully !');
                Metronic.unblockUI('#lead_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting lead !');
                Metronic.unblockUI('#lead_table');
            }
        })
    });

    lead_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    })
    /** Lead Table End */

    /** Account Table */
    account_table = $('#account_table');
    AccountTable = account_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#account_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    account_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#account_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/account_delete',
            method : 'post',
            data : {
                account_id : $(this).attr('account_id')
            },
            success : function(data) {
                AccountTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                toastr['success']('This account is deleted successfully !');
                Metronic.unblockUI('#account_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting account !');
                Metronic.unblockUI('#account_table');
            }
        })
    });

    account_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    })
    /** Account Table End */

    /** Scam Table */
    scam_table = $('#scam_table');
    ScamTable = scam_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#scam_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    scam_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#scam_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/scam_delete',
            method : 'post',
            data : {
                scam_id : $(this).attr('scam_id')
            },
            success : function(data) {
                ScamTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                toastr['success']('This scam is deleted successfully !');
                Metronic.unblockUI('#scam_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting scam !');
                Metronic.unblockUI('#scam_table');
            }
        })
    });

    scam_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    })
    /** Scam Table End */

    /** Tutorial Table */
    tutorial_table = $('#tutorial_table');
    TutorialTable = tutorial_table.dataTable({
        "lengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        "pageLength": -1
    });
    var tableWrapper = $('#tutorial_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
    tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

    tutorial_table.on('click', '.btn_remove', function() {
        nRow = $(this).parents('tr')[0];
        Metronic.blockUI({
            target: '#tutorial_table',
            animate: true
        });
        $.ajax({
            url : '/admin/tools/tutorial_delete',
            method : 'post',
            data : {
                tutorial_id : $(this).attr('tutorial_id')
            },
            success : function(data) {
                TutorialTable.fnUpdate('<button type="button" class="btn purple btn-sm">Deleted</button>', nRow, 9, false);
                toastr['success']('This tutorial is deleted successfully !');
                Metronic.unblockUI('#tutorial_table');
            },
            error : function() {
                toastr['error']('Happening any errors on deleting tutorial !');
                Metronic.unblockUI('#tutorial_table');
            }
        })
    });

    tutorial_table.on('click', '.btn_proof', function() {
        var screenshot = $(this).attr('screenshot');
        window.open(screenshot, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
    })
    /** Tutorial Table End */
});
