<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $blocks = Block::simplePaginate(20);

        return view('layouts.pages.index', [
            'blocks' => $blocks
        ]);
    }
}
