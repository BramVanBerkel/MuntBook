@extends('layouts.app')

@section('title', 'Mining yield calculator')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Mining yield calculator
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                In euros
            </p>
        </div>

        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <div class="sm:py-4 sm:px-6" x-data="miningYieldCalculator()" x-init="calculate()">
                <div class="flex gap-6">
                    <div>
                        <x-label for="hashrate">
                            Hashrate
                        </x-label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <x-input id="hashrate" x-model.number="hashrate" x-on:change="calculate()"
                                     type="number" name="hashrate" step="1" min="1"/>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm" id="price-currency">kH/s</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-label for="watts">
                            Power
                        </x-label>

                        <div class="mt-1 relative rounded-md shadow-sm">
                            <x-input id="watts" x-model.number="watts" x-on:change="calculate()"
                                     type="number" name="watts" min="0"/>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm" id="price-currency">Watts</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-label for="difficulty">
                            24h average difficulty
                        </x-label>

                        <x-input id="difficulty" x-model.number="difficulty" x-on:change="calculate()" type="number"
                                 name="difficulty" min="1"/>
                    </div>

                    <div>
                        <x-label for="kWhPrice">
                            kWh price
                        </x-label>

                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                            <x-input id="kWhPrice" x-model.number="kWhPrice" x-on:change="calculate()"
                                     class="pl-7 pr-12" type="number" name="kWhPrice" min="0" step="0.01"/>
                        </div>


                    </div>
                </div>

                <div>
                    {{-- Day --}}
                    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500">Yield per day</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="format(nlgPerDay)"></span><span class="gulden-icon"></span>
                                <span x-text="euroFormat(eurosPerDay)"
                                      class="ml-2 text-sm font-medium text-gray-500"></span>

                                <div
                                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium md:mt-2 lg:mt-0"
                                    :class="profitPerDay > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">

                                    <span class="sr-only">Profit</span>
                                    <span x-text="euroFormat(profitPerDay)"></span>
                                </div>
                            </dd>
                            <span class="text-gray-500 inline">Cost: </span><span
                                x-text="euroFormat(costPerDay)"></span>
                        </div>


                        {{-- Week --}}

                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500">Yield per week</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="format(nlgPerWeek)"></span><span class="gulden-icon"></span>
                                <span x-text="euroFormat(eurosPerWeek)"
                                      class="ml-2 text-sm font-medium text-gray-500"></span>

                                <div
                                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium md:mt-2 lg:mt-0"
                                    :class="profitPerWeek > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">

                                    <span class="sr-only">Profit</span>
                                    <span x-text="euroFormat(profitPerWeek)"></span>
                                </div>
                            </dd>
                            <span class="text-gray-500 inline">Cost: </span><span
                                x-text="euroFormat(costPerWeek)"></span>
                        </div>


                        {{-- Month --}}

                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500">Yield per month</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="format(nlgPerMonth)"></span><span class="gulden-icon"></span>
                                <span x-text="euroFormat(eurosPerMonth)"
                                      class="ml-2 text-sm font-medium text-gray-500"></span>

                                <div
                                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium md:mt-2 lg:mt-0"
                                    :class="profitPerMonth > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">

                                    <span class="sr-only">Profit</span>
                                    <span x-text="euroFormat(profitPerMonth)"></span>
                                </div>
                            </dd>
                            <span class="text-gray-500 inline">Cost: </span><span
                                x-text="euroFormat(costPerMonth)"></span>
                        </div>


                        {{-- Year --}}

                        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                            <dt class="text-sm font-medium text-gray-500">Yield per year</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                <span x-text="format(nlgPerYear)"></span><span class="gulden-icon"></span>
                                <span x-text="euroFormat(eurosPerYear)"
                                      class="ml-2 text-sm font-medium text-gray-500"></span>

                                <div
                                    class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium md:mt-2 lg:mt-0"
                                    :class="profitPerYear > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">

                                    <span class="sr-only">Profit</span>
                                    <span x-text="euroFormat(profitPerYear)"></span>
                                </div>
                            </dd>
                            <span class="text-gray-500 inline">Cost: </span><span
                                x-text="euroFormat(costPerYear)"></span>
                        </div>
                    </dl>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const price = @js($price->price);
        const hashrate = @js($hashrate);
        const difficulty = @js($difficulty);
    </script>
    <script src="{{ asset('js/miningYieldCalculator.js') }}"></script>
@endsection
