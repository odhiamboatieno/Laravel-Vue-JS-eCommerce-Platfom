<?php

namespace App\Http\Middleware;
use Session;

use Closure;

class DemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(request()->ajax())
        {
            return response()->json(['status'=>'error','message'=>'This function is disabled in demo']);
        }
        else
        {
         Session::flash('error','Opps!  This function is disabled in demo');
         return redirect()->back();
        }
        return $next($request);
    }
}
