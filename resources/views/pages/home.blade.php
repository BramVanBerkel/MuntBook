@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-stats>
        <x-stats-item>
            <x-slot name="title">
                Block Height
            </x-slot>
            <x-link href="{{ route('block', ['block' => $blockHeight]) }}" :styled="false">
                {{ $blockHeight }}
            </x-link>
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Hashrate
            </x-slot>
            {{ $hashrate }}
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Difficulty
            </x-slot>
            {{ $difficulty }}
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Transactions <small>(24 hr)</small>
            </x-slot>
            {{ $transactions24hr }}
        </x-stats-item>
        @if(!config('munt.testnet'))
        <x-stats-item>
            <x-slot name="title">
                Price
            </x-slot>
            <x-link href="{{ route('price') }}" :styled="false">
                €{{ round($price?->price, 5) }}
            </x-link>
        </x-stats-item>
        @endif
    </x-stats>

    <x-divider title="Latest Blocks"/>

    <x-table>
        <x-table-head>
            <x-table-row>
                <x-table-head-item>Block</x-table-head-item>
                <x-table-head-item>Timestamp</x-table-head-item>
                <x-table-head-item>Transactions</x-table-head-item>
                <x-table-head-item>Total value</x-table-head-item>
            </x-table-row>
        </x-table-head>


        <x-table-body>
            @foreach($blocks as $block)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item><x-link rel="nofollow" href="{{ route('block', ['block' => $block->height]) }}">{{ $block->height }}</x-link></x-table-data-item>
                    <x-table-data-item>
                        <x-date :date="$block->timestamp" />
                    </x-table-data-item>
                    <x-table-data-item>{{ $block->transactions }}</x-table-data-item>
                    <x-table-data-item><x-munt-display value="{{ $block->value }}"/></x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    {{ $blocks->links() }}

@endsection
