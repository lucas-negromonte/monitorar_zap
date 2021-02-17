<script type="text/javascript" src="<?= url("/shared/scripts/highcharts.js") ?>"></script>
<script>
    Highcharts.chart('container', {

        title: {
            text: ' '
        },

        subtitle: {
            text: ' '
        },

        yAxis: {
            title: {
                text: '<?= label("conversions") ?>'
            }
        },

        xAxis: {
            categories: [<?= (!empty($chart->category) ?  $chart->category : "") ?>],
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
            }
        },

        series: [<?= (!empty($chart->series) ? $chart->series : "") ?>],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });
</script>