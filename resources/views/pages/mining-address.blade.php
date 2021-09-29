@extends('layouts.app')

@section('title', 'Mining address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block title="Address {{ $address->address }}">
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First block found">
            {{ $address->first_block->height }}
            <small>
                <x-date :date="$address->first_block->created_at" />
            </small>
        </x-information-block-item>
        <x-information-block-item name="Last block found">
            {{ $address->last_block->height }}
            <small>
                <x-date :date="$address->last_block->created_at" />
            </small>
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
                <x-table-row color="{{ ($loop->index % 2 !== 0) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link href="{{ route('block', ['block' => $transaction->transaction->block->height]) }}">
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
