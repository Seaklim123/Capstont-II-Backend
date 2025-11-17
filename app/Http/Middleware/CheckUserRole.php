<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $hasRole = match($role) {
            'admin' => $user->isAdmin(),
            'cashier' => $user->isCashier(),
            default => false
        };

        if (!$hasRole) {
            return response()->json([
                'message' => 'Forbidden: You do not have permission',
                'required_role' => $role,
                'your_role' => $user->role
            ], 403);
        }

        return $next($request);
    }
}
