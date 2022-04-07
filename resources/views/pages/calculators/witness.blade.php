@extends('layouts.app')

@section('title', 'Witness yield calculator')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Witness yield calculator
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                In euros
            </p>
        </div>

        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <div class="sm:py-4 sm:px-6" x-data="witnessYieldCalculator()" x-init="calculate()">
                <div class="flex gap-6">
                    <div>
                        <x-label for="amount">
                            Amount of gulden
                        </x-label>

                        <x-input id="amount" x-model.number="amount" x-on:change="calculate()" class="block mt-1"
                                 type="number" name="amount" min="5000" max="1000000" step="1000"/>
                    </div>

                    <div>
                        <x-label for="days">
                            Witness period in days
                        </x-label>

                        <x-input id="days" x-model.number="days" x-on:change="calculate()" class="block mt-1"
                                 type="number" name="days" min="30" max="1095" step="1" value="365"/>
                    </div>
                </div>


                <div>
                    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">Yield per year</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="yieldPerYear"></span>
                                <span class="gulden-icon"></span><span x-text="yieldPerYearPercentage"
                                                                       class="ml-2 text-sm font-medium text-gray-500"></span>
                            </dd>
                        </div>

                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total yield</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="totalYield"></span>
                                <i class="gulden-icon"></i><span x-text="totalYieldPercentage"
                                                                 class="ml-2 text-sm font-medium text-gray-500"></span>
                            </dd>

                        </div>
                    </dl>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const networkWeight = @js($networkWeight);
        const networkWeightAdjusted = @js($networkWeightAdjusted);
    </script>
    <script src="{{ asset('js/witnessYieldCalculator.js') }}"></script>
@endsection
