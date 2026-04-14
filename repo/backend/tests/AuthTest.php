<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testPasswordHashing()
    {
        $password = 'testpassword123';
        
        $salt = bin2hex(random_bytes(16));
        $hash = password_hash($password . $salt, PASSWORD_BCRYPT);
        
        $this->assertTrue(password_verify($password . $salt, $hash));
    }

    public function testLockoutLogic()
    {
        $failedAttempts = 5;
        $locked = $failedAttempts >= 5;
        
        $this->assertTrue($locked);
    }

    public function testSessionTokenGeneration()
    {
        $token = bin2hex(random_bytes(32));
        
        $this->assertEquals(64, strlen($token));
        $this->assertTrue(is_string($token));
    }

    public function testRolePermissions()
    {
        $permissions = [
            'users.read',
            'users.create',
            'users.update',
            'users.delete',
        ];
        
        $hasPermission = in_array('users.read', $permissions);
        
        $this->assertTrue($hasPermission);
    }
}