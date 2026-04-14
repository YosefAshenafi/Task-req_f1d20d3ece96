<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\ChecklistService;

class ChecklistController
{
    protected ChecklistService $checklistService;

    public function __construct()
    {
        $this->checklistService = new ChecklistService();
    }

    /**
     * GET /api/v1/activities/:activity_id/checklists
     */
    public function index(Request $request, int $activityId): Response
    {
        try {
            $checklists = $this->checklistService->getChecklists($activityId);
            return json(['success' => true, 'code' => 200, 'data' => $checklists]);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 404, 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * POST /api/v1/activities/:activity_id/checklists
     */
    public function create(Request $request, int $activityId): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $checklist = $this->checklistService->createChecklist($activityId, $data, $request->user);
            return json(['success' => true, 'code' => 201, 'data' => $checklist, 'message' => 'Checklist created'], 201);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * PUT /api/v1/checklists/:id
     */
    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $checklist = $this->checklistService->updateChecklist($id, $data, $request->user);
            return json(['success' => true, 'code' => 200, 'data' => $checklist, 'message' => 'Checklist updated']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * DELETE /api/v1/checklists/:id
     */
    public function delete(Request $request, int $id): Response
    {
        try {
            $this->checklistService->deleteChecklist($id, $request->user);
            return json(['success' => true, 'code' => 200, 'message' => 'Checklist deleted']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * POST /api/v1/checklists/:id/items/:item_id/complete
     */
    public function completeItem(Request $request, int $checklistId, int $itemId): Response
    {
        try {
            $item = $this->checklistService->completeItem($checklistId, $itemId, $request->user);
            return json(['success' => true, 'code' => 200, 'data' => $item, 'message' => 'Item completed']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}