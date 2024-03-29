@extends('layouts.app')

@section('title', 'Address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block>
        @if($address->address === \App\Models\Address::DEVELOPMENT_ADDRESS)
            <x-slot name="description">
                This is the address for Munt development. From block number 1030000 this address will receive 40 Munt for each block and from block 1226652 80 Munt, this is why we don't show the inputs.
                <br>
                To support Munt development directly by buying Munt, please visit <x-link href="https://blockhut.com/">Blockhut</x-link>
            </x-slot>
        @endif

        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First seen">
            {{ $address->firstSeen }}
        </x-information-block-item>
        <x-information-block-item name="Value in">
            <x-munt-display value="{{ $address->totalReceived }}" />
        </x-information-block-item>
        <x-information-block-item name="Value out">
            <x-munt-display value="{{ $address->totalSpent }}" />
        </x-information-block-item>
        <x-information-block-item name="Value">
            <x-munt-display value="{{ $address->unspent }}" colored="true" sign="false"/>
        </x-information-block-item>
        <x-information-block-item name="Total transactions">
            {{ $address->totalTransactions }}
        </x-information-block-item>
    </x-information-block>

    <x-divider title="Transactions" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Date</x-table-head-item>
            <x-table-head-item>Amount</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($transactions as $transaction)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                            <x-date :date="$transaction->timestamp" />
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-munt-display value="{{ $transaction->amount }}" colored="true" />
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    {{ $transactions->links() }}
@endsection
