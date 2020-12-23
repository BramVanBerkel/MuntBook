@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Details for address {{ $address->address }}</h2>
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
                        <td><strong>First seen</strong></td>
                        <td>{{ $address->vouts->first()->transaction->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total value in</strong></td>
                        <td></td>
                    </tr>
                    <tr >
                        <td><strong>Total value out</strong></td>
                        <td style="border-bottom: black 1px solid"></td>
                    </tr>
                    <tr>
                        <td><strong>Total value</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong>Total transactions</strong></td>
                        <td></td>
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
                        <th>Transaction ammount</th>
                        <th>Transaction date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($address->transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                                    {{ Str::limit($transaction->txid, 25) }}
                                </a>
                            </td>
                            <td><span class="gulden-icon"></span> {{ $transaction->total_value_out }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
