@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Details for block #{{ $block }}</h2>
            </div>
        </div>
        <div class="row m-bottom-70">
            <div class="col-lg-12 col-md-12 col-sm-12">
                This block has not yet been mined, it will approximately be mined at {{ $time }}
            </div>
        </div>
    </div>
@endsection
