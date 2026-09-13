<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika parameter dikirim sebagai satu string "admin,staff", pecah menjadi array
        $allowedRoles = [];
        foreach ($roles as $role) {
            $parts = explode(',', $role);
            foreach ($parts as $part) {
                $allowedRoles[] = trim($part);
            }
        }

        if ($request->user() && in_array($request->user()->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki role yang diizinkan (Role Anda: ' . ($request->user()->role ?? 'Guest') . ')');
    }
}
