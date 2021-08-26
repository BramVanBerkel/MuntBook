<table class="table table-striped table-latests table-detail">
    <thead>
    <tr>
        <th>Block</th>
        <th>Reward</th>
        <th>Difficulty</th>
    </tr>
    </thead>
    <tbody>
    @foreach($address->transactions() as $reward)
        <tr>
            <td class="row">
                <a href="{{ route('block', ['block' => $reward->transaction->block->height]) }}" class="col-12">
                    {{ $reward->transaction->block->height }}
                </a>
                <small class="col-12">{{ $reward->transaction->block->created_at }}</small>
            </td>
            <td><x-gulden-display value="{{ $reward->value }}" colored="true" /></td>
            <td>{{ number_format($reward->transaction->block->difficulty, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
