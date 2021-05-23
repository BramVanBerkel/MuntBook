@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Node information</h2>
            </div>
        </div>
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Version: {{ $networkInfo->get('version') }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Subversion: {{ $networkInfo->get('subversion') }}</h6>
                    <h6 class="card-subtitle mb-2 text-muted">Protocol version: {{ $networkInfo->get('protocolversion') }}</h6>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Connections: {{ $networkInfo->get('connections') }}</h5>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Uptime: {{ $uptime }}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection