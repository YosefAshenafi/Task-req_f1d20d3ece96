<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\UserService;

class UserController
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * GET /api/v1/users
     * List users with pagination and filters.
     */
    public function index(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 20);
        $role = $request->get('role', '');
        $status = $request->get('status', '');
        $keyword = $request->get('keyword', '');

        $result = $this->userService->listUsers($page, $limit, $role, $status, $keyword);

        return json([
            'success' => true,
            'code' => 200,
            'data' => $result,
        ]);
    }

    /**
     * GET /api/v1/users/:id
     */
    public function show(Request $request, int $id): Response
    {
        try {
            $user = $this->userService->getUser($id);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 404,
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * POST /api/v1/users
     * Create a new user.
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            (new \app\validate\UserValidate())->failException(true)->scene('create')->check($data);
            $user = $this->userService->createUser($data, $request->user);
            return json([
                'success' => true,
                'code' => 201,
                'data' => $user,
                'message' => 'User created successfully',
            ], 201);
        } catch (\think\exception\ValidateException $e) {
            return json([
                'success' => false,
                'code' => 422,
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * PUT /api/v1/users/:id
     * Update user details.
     */
    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->updateUser($id, $data, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $user,
                'message' => 'User updated successfully',
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    /**
     * DELETE /api/v1/users/:id
     */
    public function delete(Request $request, int $id): Response
    {
        try {
            $this->userService->deleteUser($id, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    /**
     * PUT /api/v1/users/:id/role
     * Change user role.
     */
    public function changeRole(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $role = $data['role'] ?? '';

        if (empty($role)) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => 'Role is required',
            ], 400);
        }

        try {
            $user = $this->userService->changeRole($id, $role, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $user,
                'message' => 'Role updated successfully',
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    /**
     * PUT /api/v1/users/:id/password
     * Reset user password (admin only).
     */
    public function resetPassword(Request $request, int $id): Response
    {
        try {
            $result = $this->userService->resetPassword($id, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $result,
                'message' => 'Password reset successfully',
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }
}