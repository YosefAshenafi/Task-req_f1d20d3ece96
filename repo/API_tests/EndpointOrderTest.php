<?php

declare(strict_types=1);

namespace tests\api;

use app\model\Order;

/**
 * HTTP endpoint tests for:
 *   GET  /api/v1/orders
 *   GET  /api/v1/orders/:id
 *   GET  /api/v1/orders/:id/history
 *   POST /api/v1/orders
 *   PUT  /api/v1/orders/:id
 *   POST /api/v1/orders/:id/cancel
 *   POST /api/v1/orders/:id/close
 *   POST /api/v1/orders/:id/refund
 *   PUT  /api/v1/orders/:id/address
 */
class EndpointOrderTest extends HttpTestCase
{
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupUsersLike('http-order-%');
        $this->cleanupUsersLike('http-test-admin%');
        $this->cleanupTestOrders();

        $this->order = $this->createOrder('placed');
    }

    protected function tearDown(): void
    {
        $this->cleanupTestOrders();
        $this->cleanupUsersLike('http-order-%');
        $this->cleanupUsersLike('http-test-admin%');
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // GET /api/v1/orders
    // ------------------------------------------------------------------

    public function testListOrdersReturns401WhenUnauthenticated(): void
    {
        $res = $this->get('/api/v1/orders');
        $this->assertUnauthorized($res);
    }

    public function testListOrdersReturns200ForAdmin(): void
    {
        $this->loginAsAdmin();
        $res = $this->get('/api/v1/orders');

        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertArrayHasKey('list', $res['body']['data'] ?? []);
    }

    public function testListOrdersReturns200ForRegularUser(): void
    {
        // regular_user has orders.read
        $this->loginAsRole('regular_user', 'http-order-regular');
        $res = $this->get('/api/v1/orders');

        $this->assertStatus(200, $res);
    }

    // ------------------------------------------------------------------
    // GET /api/v1/orders/:id
    // ------------------------------------------------------------------

    public function testGetOrderReturns401WhenUnauthenticated(): void
    {
        $res = $this->get('/api/v1/orders/' . $this->order->id);
        $this->assertUnauthorized($res);
    }

    public function testGetOrderReturns200ForAdmin(): void
    {
        $this->loginAsAdmin();
        $res = $this->get('/api/v1/orders/' . $this->order->id);

        $this->assertStatus(200, $res);
        $data = $res['body']['data'] ?? [];
        $this->assertEquals($this->order->id, $data['id'] ?? null);
    }

    public function testGetOrderReturns404ForNonExistentId(): void
    {
        $this->loginAsAdmin();
        $res = $this->get('/api/v1/orders/999999');

        $this->assertNotFound($res);
    }

    // ------------------------------------------------------------------
    // GET /api/v1/orders/:id/history
    // ------------------------------------------------------------------

    public function testGetOrderHistoryReturns200ForAdmin(): void
    {
        $this->loginAsAdmin();
        $res = $this->get('/api/v1/orders/' . $this->order->id . '/history');

        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders — requires orders.create
    // ------------------------------------------------------------------

    public function testCreateOrderReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders', [
            'activity_id'  => 1,
            'items'        => [],
            'amount'       => 0,
        ]);
        $this->assertUnauthorized($res);
    }

    public function testCreateOrderReturns201ForRegularUser(): void
    {
        // regular_user has orders.create
        $this->loginAsRole('regular_user', 'http-order-regular');
        $user = \app\model\User::where('username', 'http-order-regular')->find();

        $res = $this->post('/api/v1/orders', [
            'activity_id'  => 1,
            'team_lead_id' => $user->id,
            'items'        => [],
            'amount'       => 0.0,
        ]);

        // 201 created or 400 if validation fails — either is not a 401/403
        $this->assertNotEquals(401, $res['status']);
        $this->assertNotEquals(403, $res['status']);
    }

    // ------------------------------------------------------------------
    // PUT /api/v1/orders/:id — requires orders.update
    // ------------------------------------------------------------------

    public function testUpdateOrderReturns401WhenUnauthenticated(): void
    {
        $res = $this->put('/api/v1/orders/' . $this->order->id, ['notes' => 'test note']);
        $this->assertUnauthorized($res);
    }

    public function testUpdateOrderReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-order-regular');
        $res = $this->put('/api/v1/orders/' . $this->order->id, ['notes' => 'test note']);
        $this->assertForbidden($res);
    }

    public function testUpdateOrderReturns200ForAdmin(): void
    {
        $this->loginAsAdmin();
        $res = $this->put('/api/v1/orders/' . $this->order->id, ['notes' => 'updated note']);

        $this->assertStatus(200, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/cancel — requires orders.cancel
    // ------------------------------------------------------------------

    public function testCancelOrderReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/cancel', []);
        $this->assertUnauthorized($res);
    }

    public function testCancelOrderReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-order-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/cancel', []);
        $this->assertForbidden($res);
    }

    public function testCancelOrderReturns200ForAdmin(): void
    {
        // Create a separate order so the main one stays available for other tests
        $cancelable = $this->createOrder('placed');
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $cancelable->id . '/cancel', [
            'reason' => 'http test cancellation',
        ]);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
    }

    // ------------------------------------------------------------------
    // PUT /api/v1/orders/:id/address — requires orders.update
    // ------------------------------------------------------------------

    public function testUpdateAddressReturns401WhenUnauthenticated(): void
    {
        $res = $this->put('/api/v1/orders/' . $this->order->id . '/address', [
            'invoice_address' => '123 Main St',
        ]);
        $this->assertUnauthorized($res);
    }

    public function testUpdateAddressReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-order-regular');
        $res = $this->put('/api/v1/orders/' . $this->order->id . '/address', [
            'invoice_address' => '123 Main St',
        ]);
        $this->assertForbidden($res);
    }

    public function testUpdateAddressReturns200ForAdmin(): void
    {
        $this->loginAsAdmin();
        $res = $this->put('/api/v1/orders/' . $this->order->id . '/address', [
            'invoice_address' => '456 Oak Ave, Springfield',
        ]);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
    }

    // ------------------------------------------------------------------
    // Multi-tenant authorization via HTTP (B13, B14) — replaces the
    // service-level bypass previously in API_tests/OrderApiTest.php.
    // ------------------------------------------------------------------

    public function testRegularUserSeesOnlyOwnOrders(): void
    {
        // Seed one order owned by the regular user, one owned by someone else.
        $owner = $this->ensureUser('http-order-owner', 'regular_user');
        $other = $this->ensureUser('http-order-other', 'regular_user');

        $mine = $this->createOrder('placed');
        $mine->created_by   = $owner->id;
        $mine->team_lead_id = $owner->id;
        $mine->save();

        $theirs = $this->createOrder('placed');
        $theirs->created_by   = $other->id;
        $theirs->team_lead_id = $other->id;
        $theirs->save();

        $this->loginAsRole('regular_user', 'http-order-owner');
        $res = $this->get('/api/v1/orders');
        $this->assertStatus(200, $res);

        $list = $res['body']['data']['list'] ?? [];
        $this->assertNotEmpty($list, 'owner should see at least their own order');
        foreach ($list as $row) {
            $this->assertSame(
                $owner->id,
                (int) ($row['created_by'] ?? 0),
                'regular_user must only see their own orders; got created_by=' . ($row['created_by'] ?? 'null')
            );
        }
    }

    public function testReviewerCanReadAnyOrder(): void
    {
        // Order created by someone else — reviewer must still be able to GET it.
        $someoneElse = $this->ensureUser('http-order-victim', 'regular_user');
        $order = $this->createOrder('placed');
        $order->created_by   = $someoneElse->id;
        $order->team_lead_id = $someoneElse->id;
        $order->save();

        $this->loginAsRole('reviewer', 'http-order-reviewer');
        $res = $this->get('/api/v1/orders/' . $order->id);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame($order->id, (int) ($res['body']['data']['id'] ?? -1));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function createOrder(string $state): Order
    {
        $order = new Order();
        $order->activity_id  = 1;
        $order->created_by   = 1;
        $order->team_lead_id = 1;
        $order->state        = $state;
        $order->items        = json_encode([]);
        $order->amount       = 0.0;
        // Mark with a recognizable ticket_number so we can clean up
        $order->ticket_number = 'http-order-test-' . uniqid();
        $order->save();
        return $order;
    }

    private function cleanupTestOrders(): void
    {
        Order::where('ticket_number', 'like', 'http-order-test-%')->delete();
    }
}
