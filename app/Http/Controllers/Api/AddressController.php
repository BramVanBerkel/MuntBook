<?php

namespace App\Http\Controllers\Api;

use App\DataObjects\CalendarItem;
use App\Http\Controllers\Controller;
use App\Models\Vout;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function miningAddressCalendar(string $address)
    {
        return DB::table('addresses')
            ->select([
                DB::raw("date_trunc('day', blocks.created_at) as date"),
                DB::raw('count(*) as count'),
            ])
            ->join('vouts', 'vouts.address_id', '=', 'addresses.id')
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->where('vouts.type', '=', Vout::TYPE_MINING)
            ->groupBy('date')
            ->get()
            ->transform(fn (object $calendarItem): CalendarItem => new CalendarItem(
                date: Carbon::make($calendarItem->date)->toDateString(),
                count: $calendarItem->count,
            ));
    }

    public function witnessAddressCalendar(string $address)
    {
        return DB::table('addresses')
            ->select([
                DB::raw("date_trunc('day', blocks.created_at) as date"),
                DB::raw('count(*) as count'),
            ])
            ->join('vouts', 'vouts.address_id', '=', 'addresses.id')
            ->join('transactions', 'vouts.transaction_id', '=', 'transactions.id')
            ->join('blocks', 'transactions.block_height', 'blocks.height')
            ->where('addresses.address', '=', $address)
            ->whereIn('vouts.type', [Vout::TYPE_WITNESS_REWARD, Vout::TYPE_WITNESS])
            ->whereDate('blocks.created_at', '>=', now()->subYear())
            ->groupBy('date')
            ->get()
            ->transform(fn (object $calendarItem): CalendarItem => new CalendarItem(
                date: Carbon::make($calendarItem->date)->toDateString(),
                count: $calendarItem->count,
            ));
    }
}
