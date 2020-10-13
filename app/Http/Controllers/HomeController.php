<?php

namespace App\Http\Controllers;

use App\path;
use App\Pin;
use App\Line;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home')
            ->with('lines', Line::all())
            ->with('pins', Pin::all())
            ->with('paths', Path::all());
    }
}
