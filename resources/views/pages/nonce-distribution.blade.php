@extends('layouts.app')

@section('title', 'Nonce distribution')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Nonce distribution
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                The pre and post nonce are values in a Munt block header that are repeatedly modified by SIGMA miners
                during the process of attempting to find a valid block to add to the chain. Values can range
                between 0 and 65536. Due to the nature in which SIGMA works, the post-nonce is expected
                to have a random distribution. The pre-nonce is also expected to have a random
                distribution, however with a heavy bias toward lower numbers.
            </p>
        </div>
        <div class="border-t border-gray-200 sm:px-4 sm:py-5 p-0">
            <div x-data="nonceDistribution()" x-init="init()">
                <canvas id="nonce-distribution-chart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/nonceDistribution.js') }}"></script>
@endsection
