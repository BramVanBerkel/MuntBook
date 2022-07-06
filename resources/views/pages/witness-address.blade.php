@extends('layouts.app')

@section('title', 'Witness address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block>
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        @if($address->parts->isNotEmpty())
            <x-information-block-item x-data="{ open: false }" name="Total amount locked">
                <x-gulden-display value="{{ $address->totalAmountLocked }}">
                    split in {{ $address->parts->count() }} {{ Str::of('part')->plural($address->parts->count()) }}
                    <button @click='open = !open' type="button" id="menu-button" class="h-4" aria-expanded="true"
                            aria-haspopup="true">
                        <span class="sr-only">Open options</span>

                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': open}"
                             class="h-4 w-4 inline-block transform" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition-transform transition-opacity ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-end="opacity-0 transform -translate-y-3">
                        @foreach($address->parts as $part)
                            <p>
                                Part {{ $loop->index + 1 }}:
                                <x-gulden-display value="{{ $part->value }}"></x-gulden-display>

                                <x-badge type="{{ $part->status->type() }}">
                                    @if($part->status === \App\Enums\WitnessAddressPartStatusEnum::COOLDOWN)
                                        {{ $part->status->label() }} ({{ $part->cooldown }} / {{ config('gulden.witness_cooldown_period') }})
                                    @else
                                        {{ $part->status->label() }}
                                    @endif
                                </x-badge>
                            </p>
                        @endforeach
                    </div>
                </x-gulden-display>
            </x-information-block-item>
            <x-information-block-item name="Locked from block">
            <span>
                <x-link href="{{ route('block', ['block' => $address->lockedFromBlock]) }}" :styled="false">
                    {{ $address->lockedFromBlock }}
                </x-link>
                <small class="text-xs">
                    <x-date :date="$address->lockedFromTimestamp"/>
                </small>
            </span>
            </x-information-block-item>
            <x-information-block-item name="Locked until block">
            <span>
                <x-link href="{{ route('block', ['block' => $address->lockedUntilBlock]) }}" :styled="false">
                    {{ $address->lockedUntilBlock }}
                </x-link>
                <small class="text-xs">
                    ~<x-date :date="$address->lockedUntilTimestamp"/>
                </small>
            </span>
            </x-information-block-item>
            <x-information-block-item name="Weight">
                {{ number_format($address->totalWeight) }}
            </x-information-block-item>
        @endif
    </x-information-block>

    <x-divider title="Blocks witnessed"/>

    <x-table>
        <x-table-head>
            <x-table-head-item>Block</x-table-head-item>
            <x-table-head-item>Date</x-table-head-item>
            <x-table-head-item>Reward</x-table-head-item>
            <x-table-head-item>Compound</x-table-head-item>
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
                        <x-date :date="$transaction->timestamp"/>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display
                            value="{{ $transaction->reward }}" :colored="true"/>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display
                            value="{{ $transaction->compound }}"/>
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    <div class="p-2">
        {{ $transactions->onEachSide(1)->links() }}
    </div>

@endsection
