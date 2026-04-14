<?php

namespace app\middleware;

use think\Request;
use think\Response;
use app\service\AuthService;

class AuthMiddleware
{
    /**
     * Validate bearer token on protected routes.
     * Sets $request->user with the authenticated User model.
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            return json([
                'success' => false,
                'code' => 401,
                'error' => 'Authentication required',
            ], 401);
        }

        $token = substr($header, 7);
        if (empty($token)) {
            return json([
                'success' => false,
                'code' => 401,
                'error' => 'Invalid token',
            ], 401);
        }

        $authService = new AuthService();
        $user = $authService->validateToken($token);

        if (!$user) {
            return json([
                'success' => false,
                'code' => 401,
                'error' => 'Token expired or invalid',
            ], 401);
        }

        if ($user->status !== 'active') {
            return json([
                'success' => false,
                'code' => 403,
                'error' => 'Account is not active',
            ], 403);
        }

        // Attach user to request for downstream use
        $request->user = $user;

        return $next($request);
    }
}
