<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $blocks = Block::orderBy('height', 'desc')->limit(20)->get();

        return view('layouts.pages.index', [
            'blocks' => $blocks
        ]);
    }
}
