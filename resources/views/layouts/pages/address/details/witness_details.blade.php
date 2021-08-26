<table class="table table-striped table-latests table-detail">
    <tbody>
    <tr>
        <td><strong>First seen witness</strong></td>
        <td>{{ $address->first_seen }}</td>
    </tr>
    <tr>
        <td><strong>Total amount locked</strong></td>
        <td>
            <x-gulden-display value="{{ $address->total_amount_locked }}" />
            @if($address->witnessAddressParts()->count() > 1)
                split in {{ $address->witnessAddressParts()->count() }} parts
                <a class="fas fa-caret-down" role="button" data-toggle="collapse" data-bs-toggle="collapse" href="#witness-parts" aria-expanded="false" aria-controls="witness-parts"></a>
                <div class="collapse" id="witness-parts">
                    @foreach($address->witnessAddressParts as $part)
                        <p>Part {{ $loop->index + 1 }}: <x-gulden-display value="{{ $part->amount }}"></x-gulden-display></p>
                    @endforeach
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Locked from block</strong></td>
        <td>
            <a href="{{ route('block', ['block' => $address->locked_from_block]) }}">{{ $address->locked_from_block }}</a>
            ({{ $address->locked_from_block_timestamp->toDateString() }})
        </td>
    </tr>
    <tr>
        <td><strong>Locked until block</strong></td>
        <td>
            <a href="{{ route('block', ['block' => $address->locked_until_block]) }}">{{ $address->locked_until_block }}</a>
            (~{{ $address->locked_until_block_timestamp->toDateString() }})
        </td>
    </tr>
    <tr>
        <td><strong>Weight</strong></td>
        <td>{{ number_format($address->adjusted_weight) }}</td>
    </tr>
    <tr>
        <td><strong>Eligible to witness</strong></td>
        <td>
            @if($address->eligible_to_witness)
                <span class="text-success">Address is eligible to witness</span>
            @else
                <span class="text-danger">Address is not eligible to witness</span>
                @if($address->in_cooldown)
                    <span class="text-secondary"> (cooldown {{ $address->cooldown }} / 100)</span>
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Expired from inactivity</strong></td>
        <td>
            @if($address->expired_from_inactivity)
                <span class="text-danger">Address is expired from inactivity</span>
            @else
                <span class="text-success">Address is not expired from inactivity</span>
            @endif
        </td>
    </tr>
{{--    <tr>--}}
{{--        <td><strong>Value out</strong></td>--}}
{{--        <td style="border-bottom: black 1px solid"><x-gulden-display value="{{ $totalValueOut }}" /></td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><strong>Value</strong></td>--}}
{{--        <td><x-gulden-display value="{{ $totalValue }}" colored="true" sign="false"/></td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td><strong>Total transactions</strong></td>--}}
{{--        <td>{{ $transactions->total() }}</td>--}}
{{--    </tr>--}}
    </tbody>
</table>
