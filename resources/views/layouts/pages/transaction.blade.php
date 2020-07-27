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
                    @foreach($transaction->vins()->whereNotNull('vout_id')->get() as $vin)
                        <tr>
                            <td>
                                @dd($vin->vout->addresses);
                                <a href="{{ route('address', ['address' => $vin->vout->addresses->whereHas('address')->first()->address]) }}">
                                    {{ $vin->vout->addresses->first()->address }}
                                </a>
                            </td>
                            <td class="text-danger">-<span class="gulden-icon"></span> {{ $vin->vout->value }}</td>
                        </tr>
                    @endforeach

                    @foreach($transaction->vouts()->has('addresses')->get() as $vout)
                        <tr>
                            <td>
                                @if($vout->addresses->count() === 1)
                                <a href="{{ route('address', ['address' => $vout->addresses->first()->address]) }}">
                                        {{ $vout->addresses->first()->address }}
                                    </a>
                                @else
                                    @foreach($vout->addresses as $address)
                                        <a href="{{ route('address', ['address' => $address->address]) }}">
                                            {{ Str::limit($address->address, 10) }}
                                        </a>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-success">+<span class="gulden-icon"></span>{{ $vout->value }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
