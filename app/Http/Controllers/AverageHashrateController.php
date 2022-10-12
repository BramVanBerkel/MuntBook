<?php

namespace App\Http\Controllers;

class AverageHashrateController extends Controller
{
    public function __invoke()
    {
        return view('pages.average-hashrate');
    }
}
