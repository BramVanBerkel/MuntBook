<?php


namespace App\Models\Address;


use App\Models\Block;
use App\Models\Vout;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class MiningAddress extends Address
{
    public function minedVouts(): HasMany
    {
        return $this->hasMany(Vout::class, 'address_id')
            ->where('type', '=', Vout::TYPE_MINING);
    }

    public function getFirstBlockAttribute(): Block
    {
        return $this->minedVouts()
            ->orderBy('transaction_id')
            ->first()
            ->transaction
            ->block;
    }

    public function getLastBlockAttribute(): Block
    {
        return $this->minedVouts()
            ->orderByDesc('transaction_id')
            ->first()
            ->transaction
            ->block;
    }

    public function getTransactionsAttribute()
    {
        return $this->minedVouts()
            ->orderByDesc('created_at');
    }
}
