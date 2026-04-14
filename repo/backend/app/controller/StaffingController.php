<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\StaffingService;

class StaffingController
{
    protected StaffingService $staffingService;

    public function __construct()
    {
        $this->staffingService = new StaffingService();
    }

    public function index(Request $request, int $activityId): Response
    {
        try {
            $staffing = $this->staffingService->getStaffing($activityId);
            return json(['success' => true, 'code' => 200, 'data' => $staffing]);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 404, 'error' => $e->getMessage()], 404);
        }
    }

    public function create(Request $request, int $activityId): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $staffing = $this->staffingService->createStaffing($activityId, $data, $request->user);
            return json(['success' => true, 'code' => 201, 'data' => $staffing], 201);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $staffing = $this->staffingService->updateStaffing($id, $data, $request->user);
            return json(['success' => true, 'code' => 200, 'data' => $staffing]);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    public function delete(Request $request, int $id): Response
    {
        try {
            $this->staffingService->deleteStaffing($id, $request->user);
            return json(['success' => true, 'code' => 200, 'message' => 'Deleted']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}