<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class MinhaContaController extends Controller
{
    public function index() {
        return view('pages.dashboard.minha-conta');
    }
}