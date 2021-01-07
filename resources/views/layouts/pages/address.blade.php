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
                        <td>{{ $address->vouts()->first()->transaction->block->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total value in</strong></td>
                        <td></td>
                    </tr>
                    <tr>
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
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                <a href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                                    {{ $transaction->created_at }}
                                </a>
                            </td>
                            <td>
                                @if($transaction->type === 'vout')
                                    <x-gulden_display value="{{ $transaction->value }}" colored=true />
                                @elseif($transaction->type === 'vin')
                                    <x-gulden_display value="{{ -$transaction->value }}" colored=true />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        {{ $transactions->links('layouts.partials.pagination_links') }}
    </div>
@endsection
