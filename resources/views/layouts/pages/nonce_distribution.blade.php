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
        const nonce_distribution_endpoint = '{{ route('nonce-distribution.data') }}';
    </script>
    <script src="{{ asset('js/nonce_distribution.js') }}"></script>
@endsection
