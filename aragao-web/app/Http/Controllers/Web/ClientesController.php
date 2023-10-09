<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ClientesController extends Controller {
    public function index() {
        return view('pages.dashboard.clientes');
    }
}