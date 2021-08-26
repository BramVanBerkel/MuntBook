<table class="table table-striped table-latests table-detail">
    <thead>
    <tr>
        <th>Date</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($address->transactions() as $transaction)
        <tr>
            <td>
                <a href="{{ route('transaction', ['txid' => $transaction->txid]) }}">
                    {{ $transaction->created_at }}
                </a>
            </td>
            <td>
                @if($transaction->type === 'vout')
                    <x-gulden-display value="{{ $transaction->value }}" colored=true />
                @elseif($transaction->type === 'vin')
                    <x-gulden-display value="{{ -$transaction->value }}" colored=true />
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
