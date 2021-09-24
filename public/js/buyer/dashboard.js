$(document).ready(function() {
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['RDP',     rdps],
        ['Shell',      shells],
        ['cPanel',  cpanels],
        ['Mailer', mailers],
        ['SMTP',    smtps],
        ['Lead',      leads],
        ['Account',  accounts],
        ['Scampage', scams],
        ['Tutorial',    tutorials]
        ]);

        var options = {
        title: 'All Available Tools',
        pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
});
