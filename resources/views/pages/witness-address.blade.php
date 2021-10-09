@extends('layouts.app')

@section('title', 'Mining address ' . Str::limit($address->address, 10))

@section('content')
    <x-information-block>
        <x-information-block-item name="Address" :copyable="$address->address">
            {{ $address->address }}
        </x-information-block-item>
        <x-information-block-item name="First seen">
            <x-date date="{{ $address->first_seen }}" />
        </x-information-block-item>
        @if($address->witnessAddressParts()->exists())
        <x-information-block-item x-data="{ open: false }" name="Total amount locked">
            <x-gulden-display value="{{ $address->total_amount_locked }}">
                @if($address->witnessAddressParts()->count() > 1)
                    split in {{ $address->witnessAddressParts()->count() }} parts
                    <button @click='open = !open' type="button" id="menu-button" class="h-4" aria-expanded="true" aria-haspopup="true">
                        <span class="sr-only">Open options</span>
                        {{-- Heroicon name: chevron-down --}}
                        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-180': open}" class="h-4 w-4 inline-block transform"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition-transform transition-opacity ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-end="opacity-0 transform -translate-y-3">
                        @foreach($address->witnessAddressParts as $part)
                            <p>Part {{ $loop->index + 1 }}: <x-gulden-display value="{{ $part->amount }}"></x-gulden-display></p>
                        @endforeach
                    </div>
                @endif
            </x-gulden-display>
        </x-information-block-item>
        <x-information-block-item name="Locked from block">
            <span>
                <x-link href="{{ route('block', ['block' => $address->locked_from_block]) }}" :styled="false">
                    {{ $address->locked_from_block }}
                </x-link>
                <small class="text-xs">
                    <x-date date="{{ $address->locked_from_block_timestamp }}" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Locked until block">
            <span>
                <x-link href="{{ route('block', ['block' => $address->locked_until_block]) }}" :styled="false">
                    {{ $address->locked_until_block }}
                </x-link>
                <small class="text-xs">
                    ~<x-date date="{{ $address->locked_until_block_timestamp }}" />
                </small>
            </span>
        </x-information-block-item>
        <x-information-block-item name="Weight">
            {{ number_format($address->adjusted_weight) }}
        </x-information-block-item>
        <x-information-block-item name="Eligible to witness">
            @if($address->eligible_to_witness)
                <span class="text-green-600">Address is eligible to witness</span>
            @else
                <span class="text-red-600">Address is not eligible to witness</span>
                @if($address->in_cooldown)
                    <span class="text-gray-500"> (cooldown {{ $address->cooldown }} / 100)</span>
                @endif
            @endif
        </x-information-block-item>
        <x-information-block-item name="Expired from inactivity">
            @if($address->eligible_to_witness)
                <span class="text-red-600">Address is expired from inactivity</span>
            @else
                <span class="text-green-600">Address is not expired from inactivity</span>
            @endif
        </x-information-block-item>
        @endif
    </x-information-block>

    <x-divider title="Blocks found" />

    <x-table>
        <x-table-head>
            <x-table-head-item>Date</x-table-head-item>
            <x-table-head-item>Amount</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($transactions as $transaction)
                <x-table-row color="{{ ($loop->index % 2 !== 0) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        <x-link href="{{ route('transaction', ['txid' => $transaction->transaction->txid]) }}">
                            <x-date date="{{ $transaction->transaction->created_at}}" />
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $transaction->transaction->vouts()->firstWhere('n', '=', 1)->value }}" />
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>
@endsection
