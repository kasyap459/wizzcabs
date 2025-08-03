<?php

namespace App\Http\Middleware;
use Session;
use Closure;

class WebLocalization
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
        if(!Session::has('language'))
        {
           Session::put('language', config('app.locale'));
        }

        app()->setLocale(Session::get('language'));

        return $next($request);
    }
}