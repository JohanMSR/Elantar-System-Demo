<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }
    public function index()
    {
        return view('dashboard.team.team');
    }
}
