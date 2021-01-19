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

    <div class="row">
        <div class="col-lg-6">
            <div class="btn-group btn-group-toggle" id="timeframes" data-toggle="buttons">
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="30" name="options" autocomplete="off"> 30 days
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="60" name="options" autocomplete="off"> 60 days
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="180" name="options" autocomplete="off" checked> 180 days
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="365" name="options" autocomplete="off"> 1 year
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="1095" name="options" autocomplete="off"> 3 years
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="-1" name="options" autocomplete="off"> All Time
                </label>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="btn-group btn-group-toggle float-right" id="averages" data-toggle="buttons">
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="1" name="options" autocomplete="off"> Raw Values
                </label>
                <label class="btn btn-outline-primary active">
                    <input type="radio" class="chart-setting" value="7" name="options" autocomplete="off" checked> 7 day average
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" class="chart-setting" value="30" name="options" autocomplete="off"> 30 day average
                </label>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const url = '{{ route('difficulty.data') }}';
        const difficultyCanvas = document.getElementById('difficulty').getContext('2d');
        const difficultyChart = new Chart(difficultyCanvas, {
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

        // function to update our chart
        function ajax_chart(timeframe, average) {
            $.getJSON(url, {timeframe, average}).done(function(response) {
                difficultyChart.data = response;
                difficultyChart.update();
            });
        }

        $('.chart-setting').change(function(event) {
            let timeframe = $('#timeframes .active input').attr('value');
            let average = $('#averages .active input').attr('value');
            ajax_chart(timeframe, average);
        });

        $(function(){
            ajax_chart(180, 7)
        });
    </script>
@endsection
