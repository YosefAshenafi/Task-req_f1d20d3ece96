<?php

declare(strict_types=1);

namespace tests\validate;

use app\validate\ActivityValidate;
use PHPUnit\Framework\TestCase;

class ActivityValidateTest extends TestCase
{
    private ActivityValidate $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ActivityValidate();
    }

    // ------------------------------------------------------------------
    // create scene — requires title
    // ------------------------------------------------------------------

    public function testCreateSceneAcceptsValidPayload(): void
    {
        $ok = $this->validator->scene('create')->check([
            'title' => 'Spring Hackathon 2026',
            'body'  => 'Optional description',
            'max_headcount' => 40,
        ]);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testCreateSceneRejectsMissingTitle(): void
    {
        $ok = $this->validator->scene('create')->check([
            'body' => 'body only',
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Title is required', $this->validator->getError());
    }

    public function testCreateSceneRejectsOverlongTitle(): void
    {
        $ok = $this->validator->scene('create')->check([
            'title' => str_repeat('t', 201),
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('200 characters', $this->validator->getError());
    }

    public function testCreateSceneRejectsNegativeMaxHeadcount(): void
    {
        $ok = $this->validator->scene('create')->check([
            'title' => 'Valid title',
            'max_headcount' => -5,
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('cannot be negative', $this->validator->getError());
    }

    public function testCreateSceneRejectsNonNumericMaxHeadcount(): void
    {
        $ok = $this->validator->scene('create')->check([
            'title' => 'Valid title',
            'max_headcount' => 'many',
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('must be a number', $this->validator->getError());
    }

    // ------------------------------------------------------------------
    // update scene
    // ------------------------------------------------------------------

    public function testUpdateSceneAcceptsNewTitle(): void
    {
        $ok = $this->validator->scene('update')->check([
            'title' => 'Renamed activity',
        ]);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testUpdateSceneRejectsOverlongBody(): void
    {
        $ok = $this->validator->scene('update')->check([
            'title' => 'ok',
            'body'  => str_repeat('x', 5001),
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('5000 characters', $this->validator->getError());
    }

    // ------------------------------------------------------------------
    // Full rule set (no scene) — date format gate
    // ------------------------------------------------------------------

    public function testSignupStartMustBeAValidDate(): void
    {
        $ok = $this->validator->check([
            'title' => 'ok',
            'signup_start' => 'not-a-date',
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('Invalid start date', $this->validator->getError());
    }
}
