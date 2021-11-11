@extends('layouts.app')

@section('title', 'Prices')

@section('content')
    <!-- This example requires Tailwind CSS v2.0+ -->
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
@endsection

@section('scripts')
    <script src="{{ asset('js/price.js') }}" ></script>
@endsection
