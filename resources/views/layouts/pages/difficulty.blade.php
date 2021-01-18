@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Difficulty</h2>
            </div>
        </div>
                <div class="offset-lg-3 col-lg-6">
                    <div class="center-text">
                        <p>The average difficulty per day, for the last 100 days</p>
                    </div>
                </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <canvas id="difficulty" width="300" height="200"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let url = '{{ route('difficulty.data') }}';
        let difficultyCanvas = document.getElementById('difficulty').getContext('2d');
        let difficultyChart = new Chart(difficultyCanvas, {
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
        console.log()

        ajax_chart(difficultyChart, url);

        // function to update our chart
        function ajax_chart(chart, url) {
            $.getJSON(url).done(function(response) {
                console.log(response);
                // let min = response.datasets[0].data[response.datasets[0].data.length - 1].x;
                chart.data = response;
                // chart.options.scales.xAxes[0].ticks.min = min
                // chart.options.scales.xAxes[0].ticks.stepSize = 100;
                chart.update();
            });
        }
    </script>
@endsection
