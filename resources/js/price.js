import Chart from 'chart.js/auto';
import 'chartjs-adapter-moment';

window.data = function () {
    return {
        selectedTimeframe: '1d',
        timeframes: ['1d', '7d', '1m', '3m', '1y', 'ytd', 'all'],
        ctx: document.getElementById('priceChart').getContext('2d'),
        chart: null,
        selectedIndex: 0,
        updateChart(timeframe) {
            const chart = this.chart;

            chart.data.datasets.pop();

            fetch('/api/prices/' + timeframe)
                .then(response => response.json())
                .then(function (data) {
                    chart.data.datasets.push({
                        borderColor: 'rgb(59, 130, 246)',
                        label: 'price',
                        data: data,
                        pointRadius: 0,
                        tension: 1,
                    });

                    chart.update();
                });
        },
        init() {
            this.chart = new Chart(this.ctx, {
                type: 'line',
                responsive: true,
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return '€' + Number(context.parsed.y).toFixed(5);
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                    },
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                displayFormats: {
                                    hour: 'HH:SS'
                                },
                            },
                        },
                        y: {
                            ticks: {
                                callback: function (value) {
                                    return '€' + Number(value).toFixed(5);
                                },
                            },
                        },
                    },
                }
            });

            this.updateChart('1d');
        }
    }
}
