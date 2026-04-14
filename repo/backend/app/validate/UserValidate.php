<?php

namespace app\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username' => 'require|min:3|max:32|alphaNum',
        'password' => 'require|min:10|max:100',
        'role' => 'require|max:32',
        'status' => 'require|in:active,disabled',
    ];

    protected $message = [
        'username.require' => 'Username is required',
        'username.min' => 'Username must be at least 3 characters',
        'username.max' => 'Username cannot exceed 32 characters',
        'username.alphaNum' => 'Username must be alphanumeric',
        'password.require' => 'Password is required',
        'password.min' => 'Password must be at least 10 characters',
        'password.max' => 'Password cannot exceed 100 characters',
        'role.require' => 'Role is required',
        'role.max' => 'Role name cannot exceed 32 characters',
        'status.require' => 'Status is required',
        'status.in' => 'Invalid status value',
    ];

    protected $scene = [
        'create' => ['username', 'password', 'role', 'status'],
        'update' => ['username', 'status'],
        'changeRole' => ['role'],
    ];
}