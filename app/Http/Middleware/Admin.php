<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedRoles = ['Admin', 'Pemilik', 'Magang', 'Stockist', 'Produksi', 'AdminTKB'];
        if (in_array(auth()->user()->role, $allowedRoles)) {
            return $next($request);
        }
        Alert::error('Kamu tidak memiliki akses Admin');
        return redirect('/daftar-spk');
    }
}
