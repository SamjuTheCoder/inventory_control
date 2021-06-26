<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function form()
    {
        return view('components.pages.basic-form');
    }
    public function table()
    {
        return view('components.pages.table');
    }
    public function modal()
    {
        return view('components.pages.modal');
    }
    public function icon()
    {
        return view('components.pages.themefy-icon');
    }
}
