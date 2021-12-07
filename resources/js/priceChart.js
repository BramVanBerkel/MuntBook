import { createChart } from "lightweight-charts";

window.priceChart = function () {
    return {
        selectedTimeframe: '1d',
        timeframes: ['1d', '7d', '1m', '3m', '1y', 'ytd', 'all'],
        chart: null,
        areaSeries: null,
        selectedIndex: 0,
        updateChart(timeframe) {
            const areaSeries = this.areaSeries;
            const chart = this.chart;

            fetch('/api/prices/' + timeframe)
                .then(response => response.json())
                .then(function (data) {
                    areaSeries.setData(data);
                    chart.timeScale().fitContent();
                });
        },
        init() {
            this.chart = createChart(document.getElementById('price'), {
                height: 500,
                layout: {
                    fontFamily: 'Nunito',
                },
                timeScale: {
                    timeVisible: true,
                    secondsVisible: false,
                    fixLeftEdge: true,
                    fixRightEdge: true,
                },
                rightPriceScale: {
                    visible: true,
                },
            });

            this.areaSeries = this.chart.addAreaSeries({
                topColor: 'rgba(33, 150, 243, 0.4)',
                bottomColor: 'rgba(33, 150, 243, 0.05)',
                lineColor: 'rgb(59, 130, 246)',
                priceFormat: {
                    type: 'price',
                    precision: 6,
                    minMove: 0.000001,
                },
            });

            this.updateChart('1d');
        }
    }
}
