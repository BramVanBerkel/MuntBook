@extends('layouts.app')

@section('title', 'Address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block title="Address {{ $address->address }}">
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First seen">
            {{ $address->vouts()->first()->transaction->block->created_at }}
        </x-information-block-item>
        <x-information-block-item name="Value in">
            <x-gulden-display value="{{ $address->total_value_in }}" />
        </x-information-block-item>
        <x-information-block-item name="Value out">
            <x-gulden-display value="{{ $address->total_value_out }}" />
        </x-information-block-item>
        <x-information-block-item name="Value">
            <x-gulden-display value="{{ $address->total_value }}" colored="true" sign="false"/>
        </x-information-block-item>
        <x-information-block-item name="Total transactions">
            {{ $address->transactions->count() }}
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
                <x-table-row color="{{ ($loop->index % 2 !== 0) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                            {{ $transaction->created_at }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $transaction->value }}" colored="true" />
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection