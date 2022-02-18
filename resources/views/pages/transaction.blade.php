@extends('layouts.app')

@section('title', 'Transaction ' . Str::limit($transaction->txid, 25))

@section('content')
    <x-information-block>
        <x-information-block-item name="Transaction id" :copyable="$transaction->txid">
            {{ $transaction->txid }}
        </x-information-block-item>
        <x-information-block-item name="Block">
            <x-link href="{{ route('block', ['block' => $transaction->height]) }}" :styled="false">
                {{ $transaction->height }}
            </x-link>
        </x-information-block-item>
        <x-information-block-item name="Timestamp">
            <x-date :date="$transaction->timestamp" />
        </x-information-block-item>
        <x-information-block-item name="Total value out">
            <x-gulden-display value="{{ $transaction->amount }}" />
        </x-information-block-item>
        <x-information-block-item name="Version">
            {{ $transaction->version }}
        </x-information-block-item>
        <x-information-block-item name="Transaction fee">
            <x-gulden-display value="{{ $fee }}" />
        </x-information-block-item>
        @if($transaction->rewardedWitnessAddress !== null)
            <x-information-block-item name="Witness">
                <x-link :href="route('address', ['address' => $transaction->rewardedWitnessAddress])">{{ $transaction->rewardedWitnessAddress}}</x-link>
            </x-information-block-item>
        @endif
{{--        todo--}}
{{--        @if($transaction->rewardedMiningAddress !== null)--}}
{{--            <x-information-block name="Miner">--}}
{{--                <x-link :href="route('address', ['address' => $transaction->rewardedMiningAddress])">{{ $transaction->rewardedMiningAddress }}</x-link>--}}
{{--            </x-information-block>--}}
{{--        @endif--}}
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
                        <x-link rel="nofollow" href="{{ route('address', ['address' => $output->address]) }}">
                            {{ $output->address }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $output->amount }}" colored="true"/>
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection
