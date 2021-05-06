import * as Chart from "lightweight-charts";

const chart = Chart.createChart(document.getElementById('prices'), {
    width: document.getElementById('prices').clientWidth,
    height: 600,
    localization: {
        timeFormatter: businessDayOrTimestamp => {
            const date = new Date(businessDayOrTimestamp * 1000);

            return `${date.getDate()} ${date.toLocaleString('default', {month: 'short'})} '${date.getFullYear().toString().substr(-2)} ${date.getHours()}:${('0'+date.getMinutes()).slice(-2)}`;
        }
    },
    rightPriceScale: {
        scaleMargins: {
            top: 0.3,
            bottom: 0.25,
        },
        borderVisible: false,
    },
    layout: {
        fontFamily: 'Roboto',
    },
    timeScale: {
        timeVisible: true,
        secondsVisible: false,
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
    $('#loader').removeClass('d-none');
    const params = new URLSearchParams({
        timeframe: timeframe,
    });

    fetch(prices_endpoint + '?' + params, {
        headers: {
            'accept': 'application/json',
        }
    }).then((response) => response.json())
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

            const volumeData = data.map(function (item) {
                return {
                    time: item.time,
                    value: item.volume,
                    color: (item.open <= item.close) ? '#92D1CC' : '#F0A2A1',
                }
            })

            volumeSeries.setData(volumeData);
            $('#loader').addClass('d-none');
        });
}

$('.timeframe').on('click', function (event) {
    const timeframe = $(event.target).val();
    $('.timeframe-text').text($(event.target).text());
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

$(document).ready(function () {
    const timeframe = $('.timeframe.active');
    $('.timeframe-text').text(timeframe.text());
    fillGraph(timeframe.val());
})
