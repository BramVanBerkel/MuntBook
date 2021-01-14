@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                <h2 class="section-title">Details for address {{ $address->address }}</h2>
            </div>
        </div>
        @if($address->address === App\Models\Address::DEVELOPMENT_ADDRESS)
            <div class="col-lg-12">
                <div class="center-text">
                    <p>This is the address for Gulden development.
                        From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden, this is why we don't show the inputs.</p>
                        <p>To support Gulden development directly by buying Guldens, please visit <a href="https://blockhut.com/" target="_blank">Blockhut</a></p>
                </div>
            </div>
        @endif
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
                        <td><strong>Value in</strong></td>
                        <td><x-gulden_display value="{{ $totalValueIn }}" /></td>
                    </tr>
                    <tr>
                        <td><strong>Value out</strong></td>
                        <td style="border-bottom: black 1px solid"><x-gulden_display value="{{ $totalValueOut }}" /></td>
                    </tr>
                    <tr>
                        <td><strong>Value</strong></td>
                        <td><x-gulden_display value="{{ $totalValue }}" colored="true" sign="false"/></td>
                    </tr>
                    <tr>
                        <td><strong>Total transactions</strong></td>
                        <td>{{ $transactions->total() }}</td>
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
