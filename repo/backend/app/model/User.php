<?php

namespace app\model;

use think\Model;

class User extends Model
{
    protected $table = 'users';

    protected $hidden = ['password_hash', 'salt'];

    protected $type = [
        'failed_attempts' => 'integer',
    ];

    /**
     * Verify a plaintext password against the stored hash.
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password . $this->salt, $this->password_hash);
    }

    /**
     * Set a new password (hash with salt).
     */
    public function setPassword(string $password): void
    {
        $this->salt = bin2hex(random_bytes(16));
        $this->password_hash = password_hash($password . $this->salt, PASSWORD_BCRYPT);
    }

    /**
     * Check if the account is currently locked.
     */
    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }
        return strtotime($this->locked_until) > time();
    }

    /**
     * Increment failed login attempts. Lock if threshold reached.
     */
    public function recordFailedAttempt(): void
    {
        $this->failed_attempts = $this->failed_attempts + 1;
        if ($this->failed_attempts >= 5) {
            $this->locked_until = date('Y-m-d H:i:s', time() + 900); // 15 minutes
        }
        $this->save();
    }

    /**
     * Reset failed attempts on successful login.
     */
    public function resetFailedAttempts(): void
    {
        $this->failed_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Get the role's permissions from the roles table.
     */
    public function getPermissions(): array
    {
        $role = Role::where('name', $this->role)->find();
        if (!$role) {
            return [];
        }
        $perms = $role->permissions;
        if (is_string($perms)) {
            return json_decode($perms, true) ?: [];
        }
        return is_array($perms) ? $perms : [];
    }

    /**
     * Check if user has a specific permission.
     * Supports wildcard matching (e.g. "users.*" matches "users.read").
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getPermissions();
        foreach ($permissions as $perm) {
            if ($perm === $permission) {
                return true;
            }
            // Wildcard: "users.*" matches "users.read", "users.create", etc.
            if (str_ends_with($perm, '.*')) {
                $prefix = substr($perm, 0, -2);
                if (str_starts_with($permission, $prefix . '.')) {
                    return true;
                }
            }
        }
        return false;
    }
}
