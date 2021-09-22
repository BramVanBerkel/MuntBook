@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-stats>
        <x-stats-item>
            <x-slot name="title">
                Block Height
            </x-slot>
            {{ $blockHeight }}
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Hashrate
            </x-slot>
            {{ $hashrate }}
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Difficulty
            </x-slot>
            {{ $difficulty }}
        </x-stats-item>
        <x-stats-item>
            <x-slot name="title">
                Transactions <small>(24 hr)</small>
            </x-slot>
            {{ $transactions24hr }}
        </x-stats-item>
    </x-stats>

    <x-divider title="Latest Blocks"/>

    <div class="pt-5 flex flex-col"> <!-- TODO: extract to component? -->
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Block
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transactions
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total value
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($blocks as $block)
                            <tr class="{{ $loop->index % 2 === 0 ? "bg-white" : "bg-gray-50" }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $block->height }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $block->created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $block->transactions()->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <x-gulden-display value="{{ $block->total_value_out }}"/>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
