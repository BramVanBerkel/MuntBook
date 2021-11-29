@extends('layouts.app')

@section('title', 'Prices')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Gulden price
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                In sats
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <div class="py-4 px-6">
                <canvas id="priceChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <x-button-group x-data="data()" x-init="init()">
        <template x-for="(timeframe, index) in timeframes">
            <x-button-group-button
                x-on:click="if(timeframe !== 0) updateChart(timeframe); selectedTimeframe = timeframe;"
                x-bind:class="{'rounded-l-md': index === 0, 'rounded-r-md': index === timeframes.length-1, 'bg-blue-400': selectedTimeframe === timeframe}">
                <span x-text="timeframe"></span>
            </x-button-group-button>
        </template>
    </x-button-group>
@endsection

@section('scripts')
    <script src="{{ asset('js/price.js') }}"></script>
@endsection
