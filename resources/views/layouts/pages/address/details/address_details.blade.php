<table class="table table-striped table-latests table-detail">
    <tbody>
    <tr>
        <td><strong>Address</strong></td>
        <td>{{ $address->address }}
            <span style="cursor: pointer" onclick="navigator.clipboard.writeText('{{ $address->address }}')">
                <i class="fa fa-clipboard"></i>
            </span>
        </td>
    </tr>
    <tr>
        <td><strong>First seen</strong></td>
        <td>{{ $address->vouts()->first()->transaction->block->created_at }}</td>
    </tr>
    <tr>
        <td><strong>Value in</strong></td>
        <td><x-gulden-display value="{{ $address->total_value_in }}" /></td>
    </tr>
    <tr>
        <td><strong>Value out</strong></td>
        <td style="border-bottom: black 1px solid"><x-gulden-display value="{{ $address->total_value_out }}" /></td>
    </tr>
    <tr>
        <td><strong>Value</strong></td>
        <td><x-gulden-display value="{{ $address->total_value }}" colored="true" sign="false"/></td>
    </tr>
    <tr>
        <td><strong>Total transactions</strong></td>
        <td>{{ $address->transactions()->total() }}</td>
    </tr>
    </tbody>
</table>
