<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        if (Auth::user()->type === 'client') {
            return redirect()->route('dashboard.obras');
        }

        return view('pages.dashboard.home');
    }

    public function destroy() {
        Auth::logout();

        return redirect()->route('login');
    }
}
