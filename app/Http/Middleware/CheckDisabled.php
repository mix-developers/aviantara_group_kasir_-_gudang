<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        $user = $request->user();

        // Check if user is disabled
        if ($user && $user->is_disabled == 1) {
            Auth::logout(); // Logout the user
            return redirect()->route('login')->with('danger', 'Akun Anda telah dinonaktifkan.');
        }

        // Lanjutkan dengan middleware atau proses berikutnya
        return $next($request);
    }
}
