<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\NotificationService;

class PreferenceController
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * GET /api/v1/preferences
     */
    public function index(Request $request): Response
    {
        $settings = $this->notificationService->getSettings($request->user->id);
        return json(['success' => true, 'code' => 200, 'data' => $settings]);
    }

    /**
     * PUT /api/v1/preferences
     */
    public function update(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->notificationService->updateSettings($request->user->id, $data);
            return json(['success' => true, 'code' => 200, 'message' => 'Preferences updated']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}