@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Details for block #{{ $block->height }}</h2>
            </div>
        </div>
        {{--        <div class="offset-lg-3 col-lg-6">--}}
        {{--            <div class="center-text">--}}
        {{--                <p>Fusce placerat pretium mauris, vel sollicitudin elit lacinia vitae. Quisque sit amet nisi erat.</p>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-latests table-detail">
                    <tbody>
                    <tr>
                        <td><strong>Hash</strong></td>
                        <td>{{ $block->hash }}</td>
                    </tr>
                    <tr>
                        <td><strong>Timestamp</strong></td>
                        <td>{{ $block->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total value out</strong></td>
                        <td><span class="gulden-icon"></span> {{ $block->total_value_out }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. of transactions</strong></td>
                        <td>{{ $block->transactions->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Version</strong></td>
                        <td>{{ $block->version }}</td>
                    </tr>
                    <tr>
                        <td><strong>Merkle root</strong></td>
                        <td>{{ $block->merkleroot }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Transactions</h2>
            </div>
        </div>
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-latests table-detail">
                    <thead>
                    <tr>
                        <th>Transaction id</th>
                        <th>Transaction amount</th>
                        <th>Type</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($block->transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                                    {{ Str::limit($transaction->txid, 25) }}
                                </a>
                            </td>
                            <td><span class="gulden-icon"></span> {{ $transaction->total_value_out }}</td>
                            <td>{{ $transaction->type }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        @if($previousBlock)
            <span class="mr-auto">
                <a href="{{ route('block', ['block' => $previousBlock]) }}">
                    <i class="fa fa-angle-double-left"></i>
                    Previous block
                </a>
            </span>
        @endif

        @if($nextBlock)
            <span class="ml-auto">
                <a href="{{ route('block', ['block' => $nextBlock]) }}">
                    Next block
                    <i class="fa fa-angle-double-right"></i>
                </a>
            </span>
        @endif
    </div>
@endsection
