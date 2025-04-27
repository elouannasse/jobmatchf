<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class RoleMiddleware
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || !$this->checkRole($request->user(), $role)) {
            abort(403, "   non.");
        }

        return $next($request);
    }

    /**
     * 
     * @param  \App\Models\User  $user
     * @param  string  $role
     * @return bool
     */
    private function checkRole($user, $role): bool
    {
        switch ($role) {
            case 'Admin':
                return $user->isAdmin();
            case 'Recruteur':
                return $user->isRecruteur();
            case 'Candidat':
                return $user->isCandidat();
            default:
                return false;
        }
    }
}