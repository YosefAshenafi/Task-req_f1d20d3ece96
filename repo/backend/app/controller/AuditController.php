<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\AuditService;

class AuditController
{
    protected AuditService $auditService;

    public function __construct()
    {
        $this->auditService = new AuditService();
    }

    /**
     * GET /api/v1/audit
     */
    public function index(Request $request): Response
    {
        $entityType = $request->get('entity_type', '');
        $entityId = (int) $request->get('entity_id', 0);
        $action = $request->get('action', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 50);

        $result = $this->auditService->query(
            $request->user->id,
            $entityType,
            $entityId,
            $action,
            $dateFrom,
            $dateTo,
            $page,
            $limit
        );

        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }
}