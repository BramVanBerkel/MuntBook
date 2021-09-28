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
                <x-table-row color="{{ ($loop->index % 2 !== 0) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item><x-link href="{{ route('block', ['block' => $block->height]) }}">{{$block->height}}</x-link></x-table-data-item>
                    <x-table-data-item>{{ $block->created_at }}</x-table-data-item>
                    <x-table-data-item>{{ $block->transactions()->count() }}</x-table-data-item>
                    <x-table-data-item><x-gulden-display value="{{ $block->total_value_out }}"/></x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    {{$blocks->links()}}

@endsection
