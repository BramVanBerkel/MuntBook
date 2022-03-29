<?php

namespace App\Http\Controllers;

class AverageBlocktimeController extends Controller
{
    public function __invoke()
    {
        return view('pages.average-blocktime');
    }
}
