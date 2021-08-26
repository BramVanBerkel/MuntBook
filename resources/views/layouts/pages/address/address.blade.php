@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                @switch($address->type->value)
                    @case(\App\Enums\AddressTypeEnum::MINING()->value)
                        <h2 class="section-title">Details for mining address {{ $address->address }}</h2>
                    @break
                    @case(\App\Enums\AddressTypeEnum::WITNESS()->value)
                        <h2 class="section-title">Details for witness address {{ $address->address }}</h2>
                    @break
                    @default
                        <h2 class="section-title">Details for address {{ $address->address }}</h2>
                @endswitch
            </div>
        </div>
        @if($address->address === App\Models\Address\Address::DEVELOPMENT_ADDRESS)
            <div class="col-lg-12">
                <div class="center-text">
                    <p>This is the address for Gulden development.
                        From block number 1030000 this address will receive 40 Gulden for each block and from block 1226652 80 Gulden, this is why we don't show the inputs.</p>
                        <p>To support Gulden development directly by buying Guldens, please visit <a href="https://blockhut.com/" target="_blank">Blockhut</a></p>
                </div>
            </div>
        @endif
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::ADDRESS()), 'layouts.pages.address.details.address_details')
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::MINING()), 'layouts.pages.address.details.mining_details')
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::WITNESS()) &&
                                $address->witnessAddressParts()->count() > 0,
                                'layouts.pages.address.details.witness_details')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="center-heading">
                @switch($address->type->value)
                    @case(\App\Enums\AddressTypeEnum::MINING()->value)
                        <h2 class="section-title">Mined blocks</h2>
                    @break
                    @case(\App\Enums\AddressTypeEnum::WITNESS()->value)
                        <h2 class="section-title">Rewards</h2>
                    @break
                    @default
                        <h2 class="section-title">Transactions</h2>
                @endswitch
            </div>
        </div>
    </div>
    <div class="row m-bottom-70">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="table-responsive">
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::ADDRESS()), 'layouts.pages.address.table.address_table')
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::MINING()), 'layouts.pages.address.table.mining_table')
                @includeWhen($address->type->equals(\App\Enums\AddressTypeEnum::WITNESS()), 'layouts.pages.address.table.witness_table')
            </div>
        </div>
    </div>
    <div class="row">
        {{ $address->transactions()->links('layouts.partials.pagination_links') }}
    </div>
@endsection
