<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\AuthService;

class AuthController
{
    protected AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login(Request $request): Response
    {
        $data = $request->getContent();
        $input = json_decode($data, true);

        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($username) || empty($password)) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => 'Username and password are required',
            ], 400);
        }

        try {
            $result = $this->authService->login($username, $password);
            $user = $result['user'];

            return json([
                'success' => true,
                'code' => 200,
                'data' => [
                    'access_token' => $result['token'],
                    'expires_at' => $result['expires_at'],
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'role' => $user->role,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): Response
    {
        $token = $this->extractToken($request);
        if ($token) {
            $this->authService->logout($token);
        }

        return json([
            'success' => true,
            'code' => 200,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * POST /api/v1/auth/unlock
     * Admin only — unlocks a locked account
     */
    public function unlock(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => 'user_id is required',
            ], 400);
        }

        try {
            $this->authService->unlockAccount((int)$userId);
            return json([
                'success' => true,
                'code' => 200,
                'message' => 'Account unlocked successfully',
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 500;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    private function extractToken(Request $request): ?string
    {
        $header = $request->header('Authorization', '');
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }
}
