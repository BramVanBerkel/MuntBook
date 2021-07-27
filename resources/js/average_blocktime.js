let averageBlocktimeCanvas = document.getElementById('averageBlocktime').getContext('2d');
let averageBlocktimeChart = new Chart(averageBlocktimeCanvas, {
    type: 'line',
    options: {
        scales: {
            xAxes: [{
                type: 'time'
            }],
            yAxes: [{
                id: 'Average blocktime',
                type: 'linear',
                position: 'left',
                ticks: {
                    min: 100,
                    max: 200,
                }
            }]
        }
    }
});

ajax_chart(averageBlocktimeChart, average_blocktime_endpoint);

// function to update our chart
function ajax_chart(chart, url) {
    $.getJSON(url).done(function(response) {
        chart.data = response;
        chart.update();
    });
}
