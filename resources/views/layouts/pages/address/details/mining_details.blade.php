<table class="table table-striped table-detail">
    <tbody>
    <tr>
        <td><strong>First block found</strong></td>
        <td>
            <a href="{{ route('block', ['block' => $address->first_block->height]) }}">
                {{ $address->first_block->height }}
            </a>
            <small>
                {{ $address->first_block->created_at }}
            </small>
        </td>
    </tr>
    <tr>
        <td><strong>Last block found</strong></td>
        <td>
            <a href="{{ route('block', ['block' => $address->last_block->height]) }}">
                {{ $address->last_block->height }}
            </a>
            <small>
                {{ $address->last_block->created_at }}
            </small>
        </td>
    </tr>
    <tr>
        <td><strong>Rewards found</strong></td>
        <td>
            <x-gulden-display value="{{ $address->minedVouts()->sum('value') }}" /> out of {{ $address->minedVouts()->count() }} blocks
        </td>
    </tr>
    </tbody>
</table>
