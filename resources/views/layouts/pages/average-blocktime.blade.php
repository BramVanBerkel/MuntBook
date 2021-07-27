@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Average blocktime</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <canvas id="averageBlocktime" width="300" height="200"></canvas>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const average_blocktime_endpoint = '{{ route('average-blocktime.data') }}';
    </script>
    <script src="{{ asset('js/average_blocktime.js') }}"></script>
@endsection
