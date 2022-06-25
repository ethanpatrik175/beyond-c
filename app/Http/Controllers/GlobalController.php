<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GlobalController extends Controller
{
    public function createSlug(Request $request)
    {
        $slug = Str::slug($request->name);
        return $slug;
    }

    public function clear()
    {
        Artisan::call('config:cache');
        Artisan::call('optimize:clear');

        return 'Clear Done!';
    }

    public function getPath()
    {
        return public_path();
    }
}
