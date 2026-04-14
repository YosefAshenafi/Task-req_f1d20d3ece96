<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\ShipmentService;

class ShipmentController
{
    protected ShipmentService $shipmentService;

    public function __construct()
    {
        $this->shipmentService = new ShipmentService();
    }

    /**
     * GET /api/v1/shipments
     * List all shipments with optional filters.
     */
    public function listAll(Request $request): Response
    {
        try {
            $page = (int) $request->get('page', 1);
            $limit = (int) $request->get('limit', 20);
            $status = $request->get('status', '');
            $shipments = $this->shipmentService->listAll($page, $limit, $status);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $shipments,
            ]);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/orders/:order_id/shipments
     */
    public function index(Request $request, int $orderId): Response
    {
        try {
            $shipments = $this->shipmentService->getByOrder($orderId);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $shipments,
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
     * POST /api/v1/orders/:order_id/shipments
     */
    public function create(Request $request, int $orderId): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $shipment = $this->shipmentService->createShipment($orderId, $data, $request->user);
            return json([
                'success' => true,
                'code' => 201,
                'data' => $shipment,
                'message' => 'Shipment created',
            ], 201);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/shipments/:id
     */
    public function show(Request $request, int $id): Response
    {
        try {
            $userId = $request->user ? $request->user->id : 0;
            $role = $request->user ? $request->user->role : '';
            $shipment = $this->shipmentService->getShipment($id, $userId, $role);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $shipment,
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
     * POST /api/v1/shipments/:id/scan
     */
    public function scan(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $scanCode = $data['scan_code'] ?? '';

        try {
            $result = $this->shipmentService->processScan($id, $scanCode, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/v1/shipments/:id/scan-history
     */
    public function scanHistory(Request $request, int $id): Response
    {
        try {
            $history = $this->shipmentService->getScanHistory($id);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $history,
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
     * POST /api/v1/shipments/:id/confirm-delivery
     */
    public function confirmDelivery(Request $request, int $id): Response
    {
        try {
            $shipment = $this->shipmentService->confirmDelivery($id, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $shipment,
                'message' => 'Delivery confirmed',
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
     * GET /api/v1/shipments/:id/exceptions
     */
    public function exceptions(Request $request, int $id): Response
    {
        try {
            $exceptions = $this->shipmentService->getExceptions($id);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $exceptions,
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
     * POST /api/v1/shipments/:id/exceptions
     */
    public function reportException(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $exception = $this->shipmentService->reportException($id, $data, $request->user);
            return json([
                'success' => true,
                'code' => 201,
                'data' => $exception,
                'message' => 'Exception reported',
            ], 201);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}