@extends('layouts.app')

@section('title', 'Node information')

@section('content')
    @if(!$running)
        <x-alert type="danger">
            GuldenD is not running!
        </x-alert>
    @else
        <x-information-block>
            <x-information-block-item name="Version">
                {{ $networkInfo->get('subversion') }}
            </x-information-block-item>
            <x-information-block-item name="Protocol version">
                {{ $networkInfo->get('protocolversion') }}
            </x-information-block-item>
            <x-information-block-item name="Uptime">
                {{ $uptime }}
            </x-information-block-item>
        </x-information-block>

        <x-divider title="By country"/>

        <x-table>
            <x-table-head>
                <x-table-head-item>Country</x-table-head-item>
                <x-table-head-item>Connections</x-table-head-item>
            </x-table-head>
            <x-table-body>
                @foreach($countries as $country => $connections)
                    <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                        <x-table-data-item>{{ $country }}</x-table-data-item>
                        <x-table-data-item>{{ $connections }}</x-table-data-item>
                    </x-table-row>
                @endforeach
            </x-table-body>
        </x-table>

        <x-divider title="By version"/>
        <x-table>
            <x-table-head>
                <x-table-head-item>Version</x-table-head-item>
                <x-table-head-item>Connections</x-table-head-item>
            </x-table-head>
            <x-table-body>
                @foreach($versions as $version => $connections)
                    <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                        <x-table-data-item>{{ $version }}</x-table-data-item>
                        <x-table-data-item>{{ $connections }}</x-table-data-item>
                    </x-table-row>
                @endforeach
            </x-table-body>
        </x-table>
    @endif
@endsection
