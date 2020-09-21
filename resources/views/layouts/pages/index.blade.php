@extends('layouts.main')

@section('content')
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
                            <td>{{ $block->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $block->transactions()->count() }}</td>
                            <td>{{ $block->totalValueOut }}</td>
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
