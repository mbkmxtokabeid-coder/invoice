<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;


class Karyawan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role === 'Pemilik' || auth()->user()->role === 'Admin' || auth()->user()->role === 'Produksi') {
            return $next($request);
        }
        Alert::error('Kamu tidak memiliki akses Karyawan');
        return redirect('/listBarang');
    }
}
