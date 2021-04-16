import * as Chart from "lightweight-charts";

const chart = Chart.createChart(document.getElementById('prices'), {
    width: document.getElementById('prices').clientWidth,
    height: 600,
    localization: {
        timeFormatter: businessDayOrTimestamp => {
            const date = new Date(businessDayOrTimestamp * 1000);

            // console.log(businessDayOrTimestamp + ' ' + Chart.isBusinessDay(businessDayOrTimestamp));

            return `${date.getDate()} ${date.toLocaleString('default', { month: 'short' })} '${date.getFullYear().toString().substr(-2)} ${date.getHours()}:${date.getUTCMinutes()}`;
        }
    },
});

let candlestickSeries = chart.addCandlestickSeries();
let volumeSeries = chart.addHistogramSeries({
    priceFormat: {
        type: 'volume',
    },
    priceScaleId: '',
    scaleMargins: {
        top: 0.8,
        bottom: 0,
    },
});

function fillGraph(timeframe) {
    fetch(prices_endpoint + '?' + new URLSearchParams({
        timeframe: timeframe,
    })).then((response) => response.json())
        .then(function (data) {
            const ohlcData = data.map(function (item) {
                return {
                    time: item.time,
                    open: item.open,
                    high: item.high,
                    low: item.low,
                    close: item.close,
                }
            });
            candlestickSeries.setData(ohlcData)

            const volumeData = data.map(function(item) {
                return {
                    time: item.time,
                    value: item.volume,
                    color: (item.open <= item.close) ? '#25A69A' : '#F0A2A1',
                }
            })

            console.log(volumeData);
            volumeSeries.setData(volumeData);
        });
}

$('.timeframe').on('click', function (event) {
    const timeframe = $(event.target).val();
    $('#timeframes .active').removeClass('active');
    $(event.target).addClass('active');
    chart.removeSeries(candlestickSeries)
    candlestickSeries = chart.addCandlestickSeries();
    chart.removeSeries(volumeSeries)
    volumeSeries = chart.addHistogramSeries({
        priceFormat: {
            type: 'volume',
        },
        priceScaleId: '',
        scaleMargins: {
            top: 0.8,
            bottom: 0,
        },
    });
    fillGraph(timeframe)
});

$(document).ready(function() {
    const timeframe = $('.timeframe.active');
    fillGraph(timeframe.val());
})
