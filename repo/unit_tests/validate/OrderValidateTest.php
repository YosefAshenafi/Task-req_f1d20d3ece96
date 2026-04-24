<?php

declare(strict_types=1);

namespace tests\validate;

use app\validate\OrderValidate;
use PHPUnit\Framework\TestCase;

class OrderValidateTest extends TestCase
{
    private OrderValidate $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new OrderValidate();
    }

    // ------------------------------------------------------------------
    // create scene
    // ------------------------------------------------------------------

    public function testCreateSceneAcceptsMinimalValidPayload(): void
    {
        $ok = $this->validator->scene('create')->check([
            'activity_id' => 7,
            'items'       => [],
            'notes'       => 'please process',
            'payment_method' => 'card',
            'amount'      => 12.50,
        ]);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testCreateSceneRejectsMissingActivityId(): void
    {
        $ok = $this->validator->scene('create')->check([
            'items' => [],
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Activity ID is required', $this->validator->getError());
    }

    public function testCreateSceneRejectsNonNumericActivityId(): void
    {
        $ok = $this->validator->scene('create')->check([
            'activity_id' => 'not-a-number',
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Invalid activity ID', $this->validator->getError());
    }

    public function testCreateSceneRejectsOverlongNotes(): void
    {
        $ok = $this->validator->scene('create')->check([
            'activity_id' => 1,
            'notes'       => str_repeat('x', 501),
        ]);
        $this->assertFalse($ok);
        $this->assertStringContainsString('500 characters', $this->validator->getError());
    }

    public function testCreateSceneRejectsNonArrayItems(): void
    {
        $ok = $this->validator->scene('create')->check([
            'activity_id' => 1,
            'items'       => 'not-an-array',
        ]);
        $this->assertFalse($ok);
        $this->assertSame('Items must be an array', $this->validator->getError());
    }

    // ------------------------------------------------------------------
    // update scene
    // ------------------------------------------------------------------

    public function testUpdateSceneAcceptsPartialPayload(): void
    {
        $ok = $this->validator->scene('update')->check([
            'items' => [['sku' => 'ABC']],
            'notes' => 'updated',
        ]);
        $this->assertTrue($ok, 'Error: ' . $this->validator->getError());
    }

    public function testUpdateSceneRejectsOverlongNotes(): void
    {
        $ok = $this->validator->scene('update')->check([
            'notes' => str_repeat('n', 501),
        ]);
        $this->assertFalse($ok);
    }
}
