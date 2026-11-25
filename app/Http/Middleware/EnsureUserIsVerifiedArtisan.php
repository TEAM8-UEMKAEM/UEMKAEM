<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerifiedArtisan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        $artisan = $user->artisan;

        abort_unless(
            $artisan && $artisan->is_verified && $artisan->verification_status === 'verified',
            403,
            'Halaman ini hanya untuk artisan terverifikasi.'
        );

        return $next($request);
    }
}
