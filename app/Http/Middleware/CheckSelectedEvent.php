<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;


class CheckSelectedEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          if ($request->routeIs('home') ) {
            return $next($request);
        }
         if ($request->routeIs('manager.home') ) {
            return $next($request);
        }

        if (!Session::has('selectedEvent')) {
             if (auth()->user()->type === 'admin') {
            return redirect()->route('home')->with('error', 'Please select an event.');
             }else{
                 return redirect()->route('manager.home')->with('error', 'Please select an event.');
             }
        }
        return $next($request);
    }
}