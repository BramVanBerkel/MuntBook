@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="item">
                <div class="title">
                    <div class="icon"></div>
                    <h5>Hashrate</h5>
                </div>
                <div class="text">
                    <span>{{ $hashrate }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
            <div class="item">
                <div class="title">
                    <div class="icon"></div>
                    <h5>Difficulty</h5>
                </div>
                <div class="text">
                    <span>{{ $difficulty}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Latest blocks</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-striped table-latests">
                    <thead>
                    <tr>
                        <td>Block</td>
                        <td>Timestamp</td>
                        <td>Transactions</td>
                        <td>Total value</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($blocks as $block)
                        <tr>
                            <td>
                                <a href="{{ route('block', ['block' => $block->height]) }}">
                                    {{ $block->height }}
                                </a>
                            </td>
                            <td>
                                {{ $block->created_at->format('Y-m-d H:i:s') }}
                                <small class="text-muted">{{ $block->created_at->longRelativeToNowDiffForHumans() }}</small>
                            </td>
                            <td>{{ $block->transactions()->count() }}</td>
                            <td><x-gulden-display value="{{ $block->total_value_out }}" /></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        @if($blocks->previousPageUrl())
            <span class="mr-auto">
                <a href="{{ $blocks->previousPageUrl() }}">
                    <i class="fa fa-angle-double-left"></i>
                    Previous page
                </a>
            </span>
        @endif

        @if($blocks->nextPageUrl())
            <span class="ml-auto">
                <a href="{{ $blocks->nextPageUrl() }}">
                    Next page
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </span>
        @endif
    </div>
@endsection
