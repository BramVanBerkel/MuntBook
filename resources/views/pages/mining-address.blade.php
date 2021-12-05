@extends('layouts.app')

@section('title', 'Mining address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block>
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First block found">
            <span>
                <x-link href="{{ route('block', ['block' => $address->first_block->height]) }}" :styled="false">
                    {{ $address->first_block->height }}
                </x-link>
                <small class="text-xs">
                    <x-date :date="$address->first_block->created_at" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Last block found">
            <span>
                <x-link href="{{ route('block', ['block' => $address->last_block->height]) }}" :styled="false">
                    {{ $address->last_block->height }}
                </x-link>
                <small class="text-xs">
                    <x-date :date="$address->last_block->created_at" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Rewards found">
            <x-gulden-display value="{{ $address->minedVouts()->sum('value') }}" /> out of {{ $address->minedVouts()->count() }} blocks
        </x-information-block-item>
    </x-information-block>

    <x-divider title="Blocks found" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Date</x-table-head-item>
            <x-table-head-item>Reward</x-table-head-item>
            <x-table-head-item>Difficulty</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($transactions as $transaction)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('block', ['block' => $transaction->transaction->block->height]) }}">
                            {{ $transaction->transaction->block->height }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $transaction->value }}" colored="true" />
                    </x-table-data-item>
                    <x-table-data-item>
                        {{ number_format($transaction->transaction->block->difficulty, 2) }}
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection
