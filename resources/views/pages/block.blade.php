@extends('layouts.app')

@section('title', "Block $block->height")

@section('content')
    <x-information-block>
        <x-information-block-item name="Hash" :copyable="$block->hash">
            {{ $block->hash }}
        </x-information-block-item>
        <x-information-block-item name="Timestamp">
            {{ $block->date }}
        </x-information-block-item>
        <x-information-block-item name="Total value out">
            <x-gulden-display value="{{ $block->totalValueOut }}"/>
        </x-information-block-item>
        <x-information-block-item name="No. of transactions">
            {{ $block->numTransactions }}
        </x-information-block-item>
        <x-information-block-item name="Version">
            {{ $block->version }}
        </x-information-block-item>
        <x-information-block-item name="Merkle root">
            {{ $block->merkleRoot }}
        </x-information-block-item>
    </x-information-block>

    <x-divider title="Transactions" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Hash</x-table-head-item>
            <x-table-head-item>Amount</x-table-head-item>
            <x-table-head-item>Type</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($block->transactions as $transaction)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                            {{ $transaction->txid }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $transaction->amount }}"/>
                    </x-table-data-item>
                    <x-table-data-item>
                        @switch($transaction->type)
                            @case(\App\Models\Transaction::TYPE_TRANSACTION)
                                {{-- HeroIcons switch-horizontal --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                @break
                            @case(\App\Models\Transaction::TYPE_WITNESS_FUNDING)
                                {{-- HeroIcons save --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                @break
                            @case(\App\Models\Transaction::TYPE_WITNESS)
                                {{-- HeroIcons eye --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                @break
                            @case(\App\Models\Transaction::TYPE_MINING)
                                {{-- HeroIcons chip --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                </svg>
                                @break
                        @endswitch
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

@endsection
