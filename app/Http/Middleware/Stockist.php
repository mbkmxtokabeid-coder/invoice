<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use RealRashid\SweetAlert\Facades\Alert;

class Stockist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role === 'Pemilik' || auth()->user()->role === 'Stockist'|| auth()->user()->role === 'Admin'|| auth()->user()->role === 'Produksi') {
            return $next($request);
        } elseif (auth()->user()->role === 'Admin') {
            Alert::error('Kamu tidak memiliki akses');
            return redirect('/');
        } else {
            Alert::error('Kamu tidak memiliki akses');
            return redirect('/daftar-spk');
        }
    }
}
