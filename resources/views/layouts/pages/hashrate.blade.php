@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Hashrate</h2>
            </div>
        </div>
        <div class="offset-lg-3 col-lg-6">
            <div class="center-text">
                <p>The average hashrate per day, for the last 100 days</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <canvas id="hashrate" width="300" height="200"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let url = '{{ route('hashrate.data') }}';
        let hashrateCanvas = document.getElementById('hashrate').getContext('2d');
        let hashrateChart = new Chart(hashrateCanvas, {
            type: 'line',
            options: {
                scales: {
                    xAxes: [{
                        type: 'time'
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true,
                }
            }
        });

        ajax_chart(hashrateChart, url);

        // function to update our chart
        function ajax_chart(chart, url) {
            $.getJSON(url).done(function(response) {
                chart.data = response;
                chart.update();
            });
        }
    </script>
@endsection
