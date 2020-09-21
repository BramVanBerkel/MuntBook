@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">General Information</h2>
            </div>
        </div>
        <div class="offset-lg-3 col-lg-6">
            <div class="center-text">
                <p>Fusce placerat pretium mauris, vel sollicitudin elit lacinia vitae. Quisque sit amet nisi erat.</p>
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
@endsection
