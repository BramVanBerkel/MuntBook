import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';

const ctx = document.getElementById('priceChart');

const chart = new Chart(ctx, {
    type: 'line',
    responsive: true,
    options: {
        interaction: {
            intersect: false,
        },
        maintainAspectRatio: false,
        scales: {
            x: {
                type: 'timeseries',
            },
        },
    }
});

fetch('/api/prices/1d')
    .then(response => response.json())
    .then(function (data) {
        const min = data.reduce(function (prev, curr) {
            return prev.y < curr.y ? prev : curr;
        }).y;

        const max = data.reduce(function (prev, curr) {
            return prev.y > curr.y ? prev : curr;
        }).y;

        chart.options.scales.y.suggestedMin = min - 2;
        chart.options.scales.y.suggestedMax = max + 2;

        chart.data.datasets.push({
            borderColor: 'rgb(59, 130, 246)',
            label: 'price',
            data: data
        });

        chart.update();
    });

