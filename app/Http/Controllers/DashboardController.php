<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if(Auth::check())
        {
            return view('backend.admin.dashboard');
        }
        else
        {
            return redirect(route('front.home'));
        }
    }
}
