<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Types\Etat;

class CheckEtat
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->etat !== Etat::ACTIF) {
            Log::warning('Tentative d\'accès avec compte non actif', [
                'user_id' => $user->id,
                'user_nom' => $user->nom,
                'etat' => $user->etat,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match($user->etat) {
                Etat::INACTIF => 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.',
                Etat::SUSPENDU => 'Votre compte a été suspendu. Veuillez contacter l\'administrateur.',
                Etat::SUPPRIME => 'Ce compte n\'existe plus. Veuillez contacter l\'administrateur.',
                default => 'Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.',
            };

            return redirect()->route('login')
                ->withErrors(['email' => $message])
                ->with('error', $message);
        }

        return $next($request);
    }
}