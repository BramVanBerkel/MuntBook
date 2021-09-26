@extends('layouts.app')

@section('title', 'Transaction ' . Str::limit($transaction->txid, 25))

@section('content')
    <x-information-block title="Transaction {{ Str::limit($transaction->txid, 25) }}">
        <x-information-block-item name="Transaction id">
            {{ $transaction->txid }}
        </x-information-block-item>
        <x-information-block-item name="Block">
            {{ $transaction->block_height }}
        </x-information-block-item>
        <x-information-block-item name="Timestamp">
            {{ $transaction->created_at }}
        </x-information-block-item>
        <x-information-block-item name="Total value out">
            <x-gulden-display value="{{ $transaction->total_value_out }}" />
        </x-information-block-item>
        <x-information-block-item name="Version">
            {{ $transaction->version }}
        </x-information-block-item>
        <x-information-block-item name="{{ $transaction->type === \App\Models\Transaction::TYPE_WITNESS ? 'Witness fee' : 'Transaction fee' }}">
            <x-gulden-display value="{{ $fee }}" />
        </x-information-block-item>
    </x-information-block>

    <x-divider title="Output" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Address</x-table-head-item>
            <x-table-head-item>Amount</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($outputs as $output)
                <x-table-row color="{{ ($loop->index % 2 !== 0) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        {{ $output->get('address') }}
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $output->get('value') }}"  colored="true"/>
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection
