<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CodecanyonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $setting = codeCheker();

        if ($setting) {
            return $next($request);
        }

        return redirect()->route('purchase.code');
        // return "You Are Not Verified";

    }
}
