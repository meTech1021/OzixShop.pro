$(document).ready(function() {
    setInterval(() => {
        $.ajax({
            url : '/get_infos',
            method : 'post',
            success : function(data) {
                $('#ticket_span').text(data.ticket_cnt);
                $('#report_span').text(data.report_cnt);
                $('.balance_span').text(data.balance);
                $('#total_span').text(data.ticket_cnt + data.report_cnt);
                $('#rdps_span').text(data.rdps_cnt);
                $('#shells_span').text(data.shells_cnt);
                $('#cpanels_span').text(data.cpanels_cnt);
                $('#mailers_span').text(data.mailers_cnt);
                $('#smtps_span').text(data.smtps_cnt);
                $('#checked_list_span').text(data.checked_list_cnt);
                $('#email_list_span').text(data.email_list_cnt);
                $('#combo_list_span').text(data.combo_list_cnt);
                $('#marketing_span').text(data.marketings_cnt);
                $('#hosting_span').text(data.hostings_cnt);
                $('#games_span').text(data.games_cnt);
                $('#vpn_span').text(data.vpns_cnt);
                $('#shoppig_span').text(data.shoppings_cnt);
                $('#stream_span').text(data.streams_cnt);
                $('#dating_span').text(data.datings_cnt);
                $('#learning_span').text(data.learnings_cnt);
                $('#voip_span').text(data.voips_cnt);
                $('#tutorials_span').text(data.tutorials_cnt);
                $('#scampage_span').text(data.scams_cnt);
            }
        });
    }, 1000);

    $.ajax({
        url : '/get_infos',
        method : 'post',
        success : function(data) {
            $('#ticket_span').text(data.ticket_cnt);
            $('#report_span').text(data.report_cnt);
            $('.balance_span').text(data.balance);
            $('#total_span').text(data.ticket_cnt + data.report_cnt);
            $('#rdps_span').text(data.rdps_cnt);
            $('#shells_span').text(data.shells_cnt);
            $('#cpanels_span').text(data.cpanels_cnt);
            $('#mailers_span').text(data.mailers_cnt);
            $('#smtps_span').text(data.smtps_cnt);
            $('#checked_list_span').text(data.checked_list_cnt);
            $('#email_list_span').text(data.email_list_cnt);
            $('#combo_list_span').text(data.combo_list_cnt);
            $('#marketing_span').text(data.marketings_cnt);
            $('#hosting_span').text(data.hostings_cnt);
            $('#games_span').text(data.games_cnt);
            $('#vpn_span').text(data.vpns_cnt);
            $('#shoppig_span').text(data.shoppings_cnt);
            $('#stream_span').text(data.streams_cnt);
            $('#dating_span').text(data.datings_cnt);
            $('#learning_span').text(data.learnings_cnt);
            $('#voip_span').text(data.voips_cnt);
            $('#tutorials_span').text(data.tutorials_cnt);
            $('#scampage_span').text(data.scams_cnt);
        }
    });

});
