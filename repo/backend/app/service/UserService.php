<?php

namespace app\service;

use app\model\User;
use app\model\Role;

class UserService
{
    /**
     * List users with pagination and filters.
     */
    public function listUsers(int $page = 1, int $limit = 20, string $role = '', string $status = '', string $keyword = ''): array
    {
        $query = User::order('id', 'desc');

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($keyword)) {
            $query->where('username', 'like', "%{$keyword}%");
        }

        $total = $query->count();
        $users = $query->page($page, $limit)->select();

        return [
            'list' => array_map(fn($u) => $this->formatUser($u), $users),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * Get a single user by ID.
     */
    public function getUser(int $id): array
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }
        return $this->formatUser($user);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data, $currentUser): array
    {
        $this->validateUserData($data);

        if (User::where('username', $data['username'])->find()) {
            throw new \Exception('Username already exists', 400);
        }

        $user = new User();
        $user->username = $data['username'];
        $user->role = $data['role'] ?? 'regular_user';
        $user->status = $data['status'] ?? 'active';
        $user->setPassword($data['password']);
        $user->save();

        return $this->formatUser($user);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(int $id, array $data, $currentUser): array
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        if (isset($data['username']) && $data['username'] !== $user->username) {
            if (User::where('username', $data['username'])->find()) {
                throw new \Exception('Username already exists', 400);
            }
            $user->username = $data['username'];
        }

        if (isset($data['status'])) {
            if (!in_array($data['status'], ['active', 'disabled'])) {
                throw new \Exception('Invalid status', 400);
            }
            $user->status = $data['status'];
        }

        $user->save();

        return $this->formatUser($user);
    }

    /**
     * Delete a user (soft delete by setting status to disabled).
     */
    public function deleteUser(int $id, $currentUser): void
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        if ($user->id === $currentUser->id) {
            throw new \Exception('Cannot delete your own account', 400);
        }

        $user->status = 'disabled';
        $user->save();
    }

    /**
     * Change user role.
     */
    public function changeRole(int $id, string $role, $currentUser): array
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $validRoles = array_column(Role::select()->toArray(), 'name');
        if (!in_array($role, $validRoles)) {
            throw new \Exception('Invalid role', 400);
        }

        $user->role = $role;
        $user->save();

        return $this->formatUser($user);
    }

    /**
     * Reset user password (generates temporary password).
     */
    public function resetPassword(int $id, $currentUser): array
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $tempPassword = $this->generateTempPassword();
        $user->setPassword($tempPassword);
        $user->save();

        return [
            'user_id' => $user->id,
            'temp_password' => $tempPassword,
        ];
    }

    /**
     * Validate user input data.
     */
    protected function validateUserData(array $data): void
    {
        if (empty($data['username']) || strlen($data['username']) < 3) {
            throw new \Exception('Username must be at least 3 characters', 400);
        }

        if (empty($data['password']) || strlen($data['password']) < 10) {
            throw new \Exception('Password must be at least 10 characters', 400);
        }

        if (isset($data['role'])) {
            $validRoles = array_column(Role::select()->toArray(), 'name');
            if (!in_array($data['role'], $validRoles)) {
                throw new \Exception('Invalid role', 400);
            }
        }
    }

    /**
     * Format user for API response.
     */
    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'status' => $user->status,
            'failed_attempts' => $user->failed_attempts,
            'locked_until' => $user->locked_until,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    /**
     * Generate a temporary password.
     */
    protected function generateTempPassword(): string
    {
        return bin2hex(random_bytes(8));
    }
}