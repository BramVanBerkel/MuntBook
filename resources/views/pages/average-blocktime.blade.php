@extends('layouts.app')

@section('title', 'Average blocktime')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Average blocktime
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Average blocktime in seconds per day
            </p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <div class="sm:py-4 sm:px-6">
                <canvas id="average-blocktime"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/averageBlocktime.js') }}"></script>
@endsection
