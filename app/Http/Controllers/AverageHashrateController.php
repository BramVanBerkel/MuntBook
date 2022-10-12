<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AverageHashrateController extends Controller
{
    public function __invoke()
    {
        return view('pages.average-hashrate');
    }
}
