<table class="table table-striped table-latests table-detail">
    <thead>
    <tr>
        <th>Date witness</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($address->transactions() as $vout)
        <tr>
            <td>
                <a href="{{ route('transaction', ['txid' => $vout->transaction->txid]) }}">
                    {{ $vout->transaction->created_at }}
                </a>
            </td>
            <td><x-gulden-display value="{{ $vout->transaction->vouts()->firstWhere('n', '=', 1)->value }}"></x-gulden-display></td>
        </tr>
    @endforeach
    </tbody>
</table>
