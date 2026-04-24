<?php

declare(strict_types=1);

namespace tests\validate;

use app\validate\UserValidate;
use PHPUnit\Framework\TestCase;

/**
 * Direct unit coverage for UserValidate rule set.
 *
 * HTTP-level validation is already exercised by every POST/PUT users test in
 * the API suite (through the real controller->validate() call). This file
 * adds a narrower, behaviour-oriented check of the rule set itself so a
 * regression in a single rule is caught even without booting the HTTP stack.
 */
class UserValidateTest extends TestCase
{
    private UserValidate $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UserValidate();
    }

    // ------------------------------------------------------------------
    // create scene — requires username + password
    // ------------------------------------------------------------------

    public function testCreateSceneAcceptsValidPayload(): void
    {
        $ok = $this->validator->scene('create')->check([
            'username' => 'alice_01',
            'password' => 'LongEnough!Pass1',
        ]);
        $this->assertTrue($ok, 'Expected valid create payload to pass. Error: ' . $this->validator->getError());
    }

    public function testCreateSceneRejectsMissingUsername(): void
    {
        $ok = $this->validator->scene('create')->check([
            'password' => 'LongEnough!Pass1',
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Username is required', $this->validator->getError());
    }

    public function testCreateSceneRejectsShortUsername(): void
    {
        $ok = $this->validator->scene('create')->check([
            'username' => 'ab',
            'password' => 'LongEnough!Pass1',
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('at least 3', $this->validator->getError());
    }

    public function testCreateSceneRejectsUsernameWithSpaces(): void
    {
        $ok = $this->validator->scene('create')->check([
            'username' => 'has space',
            'password' => 'LongEnough!Pass1',
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('letters, numbers, hyphens', $this->validator->getError());
    }

    public function testCreateSceneRejectsShortPassword(): void
    {
        $ok = $this->validator->scene('create')->check([
            'username' => 'alice_01',
            'password' => 'short',
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('at least 10', $this->validator->getError());
    }

    // ------------------------------------------------------------------
    // update scene — requires username + status (no password)
    // ------------------------------------------------------------------

    public function testUpdateSceneAcceptsActiveStatus(): void
    {
        $ok = $this->validator->scene('update')->check([
            'username' => 'alice_01',
            'status'   => 'active',
        ]);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testUpdateSceneRejectsInvalidStatus(): void
    {
        $ok = $this->validator->scene('update')->check([
            'username' => 'alice_01',
            'status'   => 'bogus',
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Invalid status value', $this->validator->getError());
    }

    // ------------------------------------------------------------------
    // changeRole scene — requires role only
    // ------------------------------------------------------------------

    public function testChangeRoleSceneAcceptsRole(): void
    {
        $ok = $this->validator->scene('changeRole')->check(['role' => 'reviewer']);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testChangeRoleSceneRejectsEmptyRole(): void
    {
        $ok = $this->validator->scene('changeRole')->check([]);
        $this->assertFalse($ok);
        $this->assertSame('Role is required', $this->validator->getError());
    }
}
