<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  public function handle(Request $request, Closure $next, $role)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    if (Auth::user()->role !== $role) {
        return redirect()->route('login')->with('error', 'Anda tidak punya akses ke halaman tersebut.');
    }

    return $next($request);
}
}
