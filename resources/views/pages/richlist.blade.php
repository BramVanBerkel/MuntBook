@extends('layouts.app')

@section('title', 'Richlist')

@section('content')
    <x-information-block description="
        The top gulden addresses with the highest balance.<br>
        <br>
        <br>
        Please note that this does not mean that these are the wallets with the highest amount of guldens in them.<br>
        Because Gulden automatically creates a new address when receiving guldens, multiple addresses can be tied to a single wallet.<br>
        <br>
        Updated hourly
        ">

    </x-information-block>

    <x-divider title="Richlist" />

    <x-table>
        <x-table-head>
            <x-table-head-item>#</x-table-head-item>
            <x-table-head-item>Address</x-table-head-item>
            <x-table-head-item>Value</x-table-head-item>
        </x-table-head>
        <x-table-body>
            @foreach($richList as $address)
                <x-table-row color="{{ ($loop->even) ? 'bg-gray-50' : 'bg-white' }}">
                    <x-table-data-item>
                        {{ $address->index }}
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-link rel="nofollow" href="{{ route('address', ['address' => $address->address]) }}">
                            {{ $address->address }}
                        </x-link>
                    </x-table-data-item>
                    <x-table-data-item>
                        <x-gulden-display value="{{ $address->value }}" />
                    </x-table-data-item>
                </x-table-row>
            @endforeach
        </x-table-body>
    </x-table>

    {{ $richList->links() }}
@endsection
