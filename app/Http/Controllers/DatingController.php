<?php

namespace App\Http\Controllers;

use App\Models\Dating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatingController extends Controller
{
    public function findYourDate()
    {
        $dating = Dating::where('user_id', Auth::user()->id)->first();

        dd($dating);
    }

    public function createAccount()
    {
        return view('frontend.create-account');
    }
}
