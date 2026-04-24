<?php

declare(strict_types=1);

namespace tests\api;

use app\model\Order;

/**
 * HTTP endpoint tests for order state-transition routes:
 *   POST /api/v1/orders/:id/initiate-payment
 *   POST /api/v1/orders/:id/confirm-payment
 *   POST /api/v1/orders/:id/start-ticketing
 *   POST /api/v1/orders/:id/ticket
 *   POST /api/v1/orders/:id/refund
 *   POST /api/v1/orders/:id/close
 *   POST /api/v1/orders/:id/request-address-correction
 *   POST /api/v1/orders/:id/approve-address-correction
 *
 * Bootstrap roles used:
 *   administrator  — orders.* (all transition permissions)
 *   regular_user   — orders.read, orders.create only (no transition permissions)
 */
class EndpointOrderTransitionTest extends HttpTestCase
{
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanupUsersLike('http-trans-%');
        $this->cleanupUsersLike('http-test-admin%');
        $this->cleanupTestOrders();

        $this->order = $this->createOrder('placed');
    }

    protected function tearDown(): void
    {
        $this->cleanupTestOrders();
        $this->cleanupUsersLike('http-trans-%');
        $this->cleanupUsersLike('http-test-admin%');
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/initiate-payment  (rbac: orders.payment)
    // ------------------------------------------------------------------

    public function testInitiatePaymentReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/initiate-payment', []);
        $this->assertUnauthorized($res);
    }

    public function testInitiatePaymentReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/initiate-payment', []);
        $this->assertForbidden($res);
    }

    public function testInitiatePaymentAdminTransitionsToPendingPayment(): void
    {
        // B3: behavior replaces weak assertNotEquals(401/403) pair.
        // Seed state is 'placed' (createOrder default) — valid precondition.
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/initiate-payment', []);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('pending_payment', $res['body']['data']['state'] ?? null);
        // 30-min auto-cancel timer must be set.
        $this->assertNotEmpty($res['body']['data']['auto_cancel_at'] ?? null);
    }

    public function testInitiatePaymentFailsForNonPlacedState(): void
    {
        // B3-edge: wrong-state input must emit 400, not a silent 200.
        $this->loginAsAdmin();
        $paid = $this->createOrder('paid');
        $res  = $this->post('/api/v1/orders/' . $paid->id . '/initiate-payment', []);
        $this->assertStatus(400, $res);
        $this->assertFalse($res['body']['success'] ?? true);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/confirm-payment  (rbac: orders.payment)
    // ------------------------------------------------------------------

    public function testConfirmPaymentReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/confirm-payment', []);
        $this->assertUnauthorized($res);
    }

    public function testConfirmPaymentReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/confirm-payment', []);
        $this->assertForbidden($res);
    }

    public function testConfirmPaymentAdminTransitionsToPaid(): void
    {
        // B4: seed prerequisite state = pending_payment; expect transition to 'paid'.
        $this->loginAsAdmin();
        $pending = $this->createOrder('pending_payment');
        $res = $this->post('/api/v1/orders/' . $pending->id . '/confirm-payment', [
            'payment_method' => 'cash',
            'amount' => 42.0,
        ]);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('paid', $res['body']['data']['state'] ?? null);
    }

    public function testConfirmPaymentFailsForPlacedOrder(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/confirm-payment', []);
        $this->assertStatus(400, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/start-ticketing  (rbac: orders.ticketing)
    // ------------------------------------------------------------------

    public function testStartTicketingReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/start-ticketing', []);
        $this->assertUnauthorized($res);
    }

    public function testStartTicketingReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/start-ticketing', []);
        $this->assertForbidden($res);
    }

    public function testStartTicketingAdminTransitionsToTicketing(): void
    {
        // B5: prerequisite state = paid.
        $this->loginAsAdmin();
        $paid = $this->createOrder('paid');
        $res  = $this->post('/api/v1/orders/' . $paid->id . '/start-ticketing', []);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('ticketing', $res['body']['data']['state'] ?? null);
    }

    public function testStartTicketingFailsFromPlaced(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/start-ticketing', []);
        $this->assertStatus(400, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/ticket  (rbac: orders.ticketing)
    // ------------------------------------------------------------------

    public function testTicketReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/ticket', []);
        $this->assertUnauthorized($res);
    }

    public function testTicketReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/ticket', []);
        $this->assertForbidden($res);
    }

    public function testTicketAdminIssuesTicketNumber(): void
    {
        // B6: prerequisite state = ticketing; must emit ticket_number and state 'ticketed'.
        $this->loginAsAdmin();
        $tix = $this->createOrder('ticketing');
        $res = $this->post('/api/v1/orders/' . $tix->id . '/ticket', [
            'ticket_number' => 'TKT-TEST-' . uniqid(),
        ]);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('ticketed', $res['body']['data']['state'] ?? null);
        $this->assertNotEmpty($res['body']['data']['ticket_number'] ?? null);
    }

    public function testTicketFailsFromPlaced(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/ticket', [
            'ticket_number' => 'TKT-X',
        ]);
        $this->assertStatus(400, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/refund  (rbac: orders.refund)
    // ------------------------------------------------------------------

    public function testRefundReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/refund', []);
        $this->assertUnauthorized($res);
    }

    public function testRefundReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/refund', []);
        $this->assertForbidden($res);
    }

    public function testRefundAdminTransitionsPaidToCanceled(): void
    {
        // B7: prerequisite state = paid; admin-only refund.
        $this->loginAsAdmin();
        $paid = $this->createOrder('paid');
        $res  = $this->post('/api/v1/orders/' . $paid->id . '/refund', []);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('canceled', $res['body']['data']['state'] ?? null);
    }

    public function testRefundFailsForNonPaidOrder(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/refund', []);
        $this->assertStatus(400, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/close  (rbac: orders.close)
    // ------------------------------------------------------------------

    public function testCloseReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/close', []);
        $this->assertUnauthorized($res);
    }

    public function testCloseReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/close', []);
        $this->assertForbidden($res);
    }

    public function testCloseAdminTransitionsTicketedToClosed(): void
    {
        // B8: prerequisite state = ticketed.
        $this->loginAsAdmin();
        $ticketed = $this->createOrder('ticketed');
        $res      = $this->post('/api/v1/orders/' . $ticketed->id . '/close', []);
        $this->assertStatus(200, $res);
        $this->assertSuccess($res);
        $this->assertSame('closed', $res['body']['data']['state'] ?? null);
        $this->assertNotEmpty($res['body']['data']['closed_at'] ?? null);
    }

    public function testCloseFailsFromPlacedState(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/close', []);
        $this->assertStatus(400, $res);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/request-address-correction
    //   (rbac: orders.request_correction)
    // ------------------------------------------------------------------

    public function testRequestAddressCorrectionReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/request-address-correction', []);
        $this->assertUnauthorized($res);
    }

    public function testRequestAddressCorrectionReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/request-address-correction', []);
        $this->assertForbidden($res);
    }

    public function testRequestAddressCorrectionStoresPendingPayload(): void
    {
        // B9a: only closed orders can request correction; seed 'closed'.
        $this->loginAsAdmin();
        $closed = $this->createOrder('closed');
        $res = $this->post('/api/v1/orders/' . $closed->id . '/request-address-correction', [
            'new_address' => ['line1' => '100 Main St', 'city' => 'Testville'],
        ]);
        $this->assertStatus(200, $res);
        // Controller returns the service result with success flag inside body.
        $this->assertTrue(
            ($res['body']['success'] ?? false) || ($res['body']['data']['success'] ?? false),
            'Expected success flag in body; got ' . json_encode($res['body'])
        );
        // Persistence check: DB row holds pending_address_correction JSON.
        $reloaded = \app\model\Order::find($closed->id);
        $this->assertNotEmpty($reloaded->pending_address_correction);
    }

    public function testRequestAddressCorrectionRejectsNonClosedState(): void
    {
        $this->loginAsAdmin();
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/request-address-correction', [
            'new_address' => ['line1' => 'x'],
        ]);
        // Service returns success:false for non-closed orders; controller still
        // returns 200 with body.success=false.
        $this->assertFalse($res['body']['success'] ?? true);
    }

    // ------------------------------------------------------------------
    // POST /api/v1/orders/:id/approve-address-correction
    //   (rbac: orders.approve)
    // ------------------------------------------------------------------

    public function testApproveAddressCorrectionReturns401WhenUnauthenticated(): void
    {
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/approve-address-correction', []);
        $this->assertUnauthorized($res);
    }

    public function testApproveAddressCorrectionReturns403ForRegularUser(): void
    {
        $this->loginAsRole('regular_user', 'http-trans-regular');
        $res = $this->post('/api/v1/orders/' . $this->order->id . '/approve-address-correction', []);
        $this->assertForbidden($res);
    }

    public function testApproveAddressCorrectionClearsPendingPayload(): void
    {
        // B9b: seed a closed order with a pending correction, then approve.
        $this->loginAsAdmin();
        $closed = $this->createOrder('closed');
        $closed->pending_address_correction = json_encode([
            'type'           => 'address_correction',
            'requested_by'   => 1,
            'requester_role' => 'team_lead',
            'new_address'    => ['line1' => '200 Oak'],
            'status'         => 'pending_review',
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
        $closed->save();

        $res = $this->post('/api/v1/orders/' . $closed->id . '/approve-address-correction', []);
        $this->assertStatus(200, $res);
        $this->assertTrue(
            ($res['body']['success'] ?? false) || ($res['body']['data']['success'] ?? false),
            'Expected success flag; got ' . json_encode($res['body'])
        );
        $reloaded = \app\model\Order::find($closed->id);
        $this->assertNull($reloaded->pending_address_correction);
        $this->assertNotEmpty($reloaded->invoice_address);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function createOrder(string $state): Order
    {
        $order = new Order();
        $order->activity_id   = 1;
        $order->created_by    = 1;
        $order->team_lead_id  = 1;
        $order->state         = $state;
        $order->items         = json_encode([]);
        $order->amount        = 0.0;
        // Prefix distinguishes these records for cleanup
        $order->ticket_number = 'http-trans-' . uniqid();
        $order->save();
        return $order;
    }

    private function cleanupTestOrders(): void
    {
        Order::where('ticket_number', 'like', 'http-trans-%')->delete();
    }
}
