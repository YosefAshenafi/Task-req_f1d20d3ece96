<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\DashboardService;

class DashboardController
{
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request): Response
    {
        $data = $this->dashboardService->getDefault($request->user->id);
        return json(['success' => true, 'code' => 200, 'data' => $data]);
    }

    public function custom(Request $request): Response
    {
        $data = $this->dashboardService->getCustom($request->user->id);
        return json(['success' => true, 'code' => 200, 'data' => $data]);
    }

    public function createCustom(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $dashboard = $this->dashboardService->saveCustom($request->user->id, $data);
            return json(['success' => true, 'code' => 201, 'data' => $dashboard], 201);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    public function updateCustom(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $dashboard = $this->dashboardService->updateCustom($id, $request->user->id, $data);
            return json(['success' => true, 'code' => 200, 'data' => $dashboard]);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}