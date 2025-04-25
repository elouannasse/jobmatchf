<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        $hasRole = false;
        
        switch ($role) {
            case 'Admin':
                $hasRole = $request->user()->isAdmin();
                break;
            case 'Recruteur':
                $hasRole = $request->user()->isRecruteur();
                break;
            case 'Candidat':
                $hasRole = $request->user()->isCandidat();
                break;
        }
        
        if (!$hasRole) {
            abort(403, "pas autorisé ");
        }
        
        return $next($request);
    }
}