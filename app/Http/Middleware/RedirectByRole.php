<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Types\Role;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentPath = $request->path();

            $redirectTo = match($user->role) {
                Role::Admin => 'admin/dashboard',
                Role::Caissier => 'caissier/dashboard',
                Role::Collecteur => 'collecteur/dashboard',
                Role::Comptable => 'comptable/dashboard',
                Role::Client => 'client/dashboard',
                default => 'dashboard',
            };

            if ($currentPath === 'dashboard') {
                return redirect($redirectTo);
            }

            $allowedPaths = [
                Role::Admin => ['admin/dashboard', 'admin'],
                Role::Caissier => ['caissier/dashboard', 'caissier'],
                Role::Collecteur => ['collecteur/dashboard', 'collecteur'],
                Role::Comptable => ['comptable/dashboard', 'comptable'],
                Role::Client => ['client/dashboard', 'client'],
            ];

            $firstSegment = explode('/', $currentPath)[0];

            if (in_array($firstSegment, ['admin', 'caissier', 'collecteur', 'comptable', 'client'])) {
                if (!in_array($currentPath, $allowedPaths[$user->role] ?? []) 
                    && !str_starts_with($currentPath, $allowedPaths[$user->role][1] ?? '')) {
                    return redirect($redirectTo);
                }
            }
        }

        return $next($request);
    }
}