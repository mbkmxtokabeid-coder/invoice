<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // dd(Auth::user());
        Auth::user()->update([
            'device_token' => $request->token
        ]);

        return response()->json([], 201);
    }
}
