@extends('layouts.app')

@section('title', 'Transaction ' . Str::limit($transaction->txid, 25))

@section('content')
    <x-information-block>
        <x-information-block-item name="Transaction id" :copyable="$transaction->txid">
            {{ $transaction->txid }}
        </x-information-block-item>
        <x-information-block-item name="Block">
            <x-link href="{{ route('block', ['block' => $transaction->block_height]) }}" :styled="false">
                {{ $transaction->block_height }}
            </x-link>
        </x-information-block-item>
        <x-information-block-item name="Timestamp">
            <x-date :date="$transaction->created_at" />
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
        @if(isset($rewarded_witness_address))
            <x-information-block-item name="Rewarded witness address">
                <x-link :href="route('address', ['address' => $rewarded_witness_address->address])">{{ $rewarded_witness_address->address }}</x-link>
            </x-information-block-item>
        @endif
    </x-information-block>

    <x-divider title="Output" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Address</x-table-head-item>
            <x-table-head-item>Amount</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($outputs as $output)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('address', ['address' => $output->get('address')]) }}">
                            {{ $output->get('address') }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $output->get('value') }}"  colored="true"/>
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection
