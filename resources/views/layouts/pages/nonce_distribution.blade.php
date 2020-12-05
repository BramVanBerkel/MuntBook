@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Nonce distribution</h2>
            </div>
        </div>
        {{--        <div class="offset-lg-3 col-lg-6">--}}
        {{--            <div class="center-text">--}}
        {{--                <p>Fusce placerat pretium mauris, vel sollicitudin elit lacinia vitae. Quisque sit amet nisi erat.</p>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>

    <div class="row">
        <div class="col-lg-12">
            <canvas id="nonceDistribution" width="300" height="200"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let url = '{{ route('nonce-distribution.data') }}';
        let nonceDistributionCanvas = document.getElementById('nonceDistribution').getContext('2d');
        let nonceDistributionChart = new Chart(nonceDistributionCanvas, {
            type: 'scatter',
        });

        ajax_chart(nonceDistributionChart, url);

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
    </script>
@endsection
