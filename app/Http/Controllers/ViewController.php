<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ViewController extends Controller
{
    public function index(Request $request): View
    {
        return view('index');
    }
}
