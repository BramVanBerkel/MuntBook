@extends('layouts.app')

@section('title', 'Mining address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block>
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First block found">
            <span>
                <x-link href="{{ route('block', ['block' => $address->firstBlock]) }}" :styled="false">
                    {{ $address->firstBlock }}
                </x-link>
                <small class="text-xs">
                    <x-date :date="$address->firstBlockDate" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Last block found">
            <span>
                <x-link href="{{ route('block', ['block' => $address->lastBlock]) }}" :styled="false">
                    {{ $address->lastBlock }}
                </x-link>
                <small class="text-xs">
                    <x-date :date="$address->lastBlockDate" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Rewards found">
            <x-gulden-display value="{{ $address->totalRewardsValue }}" /> out of {{ $address->totalRewards }} blocks
        </x-information-block-item>
    </x-information-block>

    <x-divider title="Blocks calendar" />

    <div id="calendar" class="flex justify-center"></div>

    <div id="calendar-tooltip"></div>

    <x-divider title="Blocks found" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Block</x-table-head-item>
            <x-table-head-item>Date</x-table-head-item>
            <x-table-head-item>Reward</x-table-head-item>
            <x-table-head-item>Difficulty</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($transactions as $transaction)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('block', ['block' => $transaction->height]) }}">
                            {{ $transaction->height }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        {{ $transaction->date }}
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $transaction->reward }}" colored="true" />
                    </x-table-data-item>
                    <x-table-data-item>
                        {{ number_format($transaction->difficulty, 2) }}
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    {{ $transactions->links() }}
@endsection

@section('scripts')
    <script>
        const address = @js($address->address);
    </script>
    <script src="{{ asset('js/miningAddress.js') }}"></script>
@endsection
