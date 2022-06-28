<?php

namespace App\Http\Middleware;

use App\Models\Dating;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            $dating = Dating::where('user_id', Auth::user()->id)->first();
            if(!$dating)
            {
                return redirect()->route('dating.create.account');
            }
            return $next($request);
        }
    }
}
