import * as Chart from "lightweight-charts";

const chart = Chart.createChart(document.getElementById('prices'), {
    width: document.getElementById('prices').clientWidth,
    height: 600,
    localization: {
        timeFormatter: businessDayOrTimestamp => {
            const date = new Date(businessDayOrTimestamp * 1000);

            return date.toLocaleString();
        },
    }
});

const candlestickSeries = chart.addCandlestickSeries();

fetch(prices_endpoint).then((response) => response.json())
    .then(function (data) {
        let ohlcData = data.map(function (item) {
            return {
                time: item.time,
                open: item.open,
                high: item.high,
                low: item.low,
                close: item.close,
            }
        });
        candlestickSeries.setData(ohlcData)
    });
