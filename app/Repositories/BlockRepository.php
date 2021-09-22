<?php

namespace App\Repositories;

use App\Models\Block;
use Illuminate\Contracts\Pagination\Paginator;

class BlockRepository
{
    public function index(): Paginator
    {
        return Block::orderBy('height', 'desc')->simplePaginate(10);
    }

    public function getCurrentHeight(): int
    {
        return Block::max('height') ?? 0;
    }
}
