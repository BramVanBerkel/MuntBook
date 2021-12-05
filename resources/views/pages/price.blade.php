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
                <div id="price"></div>
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

    <div
        x-data="@js(['nlg' => 1, 'eur' => round($currentPrice->price, 5), 'nlgPrice' => round($currentPrice->price, 5)])"
        class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Gulden converter
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                1 Gulden = €{{ round($currentPrice->price, 5) }}
            </p>
        </div>

        <div class="p-4 flex items-center space-x-4">
            <div>
                <label for="nlg-price" class="sr-only">Price</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input x-model="nlg"
                           x-on:input="eur = parseFloat(nlgPrice * $event.target.value).toFixed(2)"
                           type="number" step="0.0001" min="0" id="nlg-price"
                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">
                        NLG
                    </span>
                    </div>
                </div>
            </div>
            <span>=</span>
            <div>
                <label for="eur-price" class="sr-only">Price</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input x-model="eur"
                           x-on:input="nlg = parseFloat($event.target.value / nlgPrice).toFixed(4)"
                           type="number" step="0.01" min="0" name="eur-price" id="eur-price"
                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">
                        EUR
                    </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/price.js') }}"></script>
@endsection
