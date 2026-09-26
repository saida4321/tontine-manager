<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Types\Role;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // ✅ NE PAS VÉRIFIER LE RÔLE SUR LES DASHBOARDS (RedirectByRole s'en occupe)
        if ($request->routeIs('*.dashboard')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if (empty($roles)) {
            return $next($request);
        }

        $allowedRoles = [];
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $allowedRoles[] = (int) $role;
            } else {
                $allowedRoles[] = $this->getRoleId($role);
            }
        }

        if (!in_array($user->role, $allowedRoles)) {
            Log::warning('Tentative d\'accès non autorisée', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $allowedRoles,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }

    private function getRoleId(string $roleName): int
    {
        return match(strtolower($roleName)) {
            'admin', 'administrateur' => Role::Admin,
            'caissier' => Role::Caissier,
            'collecteur' => Role::Collecteur,
            'comptable' => Role::Comptable,
            'client' => Role::Client,
            default => 0,
        };
    }

    private function getUserDashboard(int $role): string
    {
        return match($role) {
            Role::Admin => '/admin/dashboard',
            Role::Caissier => '/caissier/dashboard',
            Role::Collecteur => '/collecteur/dashboard',
            Role::Comptable => '/comptable/dashboard',
            Role::Client => '/client/dashboard',
            default => '/dashboard',
        };
    }
}