let nonceDistributionCanvas = document.getElementById('nonceDistribution').getContext('2d');
let nonceDistributionChart = new Chart(nonceDistributionCanvas, {
    type: 'scatter',
});

ajax_chart(nonceDistributionChart, nonce_distribution_endpoint);

// function to update our chart
function ajax_chart(chart, url) {
    $.getJSON(url).done(function(response) {
        let min = response.datasets[0].data[response.datasets[0].data.length - 1].x;
        chart.data = response;
        chart.options.scales.xAxes[0].ticks.min = min
        chart.options.scales.xAxes[0].ticks.stepSize = 100;
        chart.update();
    });
}
