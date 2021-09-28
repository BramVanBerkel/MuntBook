<?php

namespace App\Repositories;

use App\Models\Block;

class BlockRepository
{
    public function index()
    {
        return Block::orderBy('height', 'desc');
    }

    public function getCurrentHeight(): int
    {
        return Block::max('height') ?? 0;
    }
}
