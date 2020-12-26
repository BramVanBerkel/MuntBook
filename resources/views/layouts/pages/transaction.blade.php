@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Details for transaction #{{ Str::limit($transaction->txid, 25) }}</h2>
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
                        <td><strong>Transaction id</strong></td>
                        <td>{{ $transaction->txid }}</td>
                    </tr>
                    <tr>
                        <td><strong>Block</strong></td>
                        <td>{{ $transaction->block->height }}</td>
                    </tr>
                    <tr>
                        <td><strong>Timestamp</strong></td>
                        <td>{{ $transaction->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total value out</strong></td>
                        <td><span class="gulden-icon"></span> {{ $transaction->total_value_out }}</td>
                    </tr>
                    <tr>
                        <td><strong>Version</strong></td>
                        <td>{{ $transaction->version }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Output</h2>
            </div>
        </div>
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-latests table-detail">
                    <tbody>
                    <thead>
                    <tr>
                        <th>Address</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vins as $vin)
                        <tr>
                            <td>
                                <a href="{{ route('address', ['address' => $vin->address]) }}">
                                    {{ $vin->address }}
                                </a>
                            </td>
                            <td class="text-danger">-<span class="gulden-icon"></span> {{ $vin->value }}</td>
                        </tr>
                    @endforeach

                    @foreach($vouts as $vout)
                        <tr>
                            <td>
                                <a href="{{ route('address', ['address' => $vout->address->address]) }}">
                                    {{ $vout->address->address }}
                                </a>
                            </td>
                            <td class="text-success">+<span class="gulden-icon"></span>{{ $vout->value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
