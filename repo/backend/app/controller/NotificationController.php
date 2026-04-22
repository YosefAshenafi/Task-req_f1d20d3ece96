<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\NotificationService;

class NotificationController
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * GET /api/v1/notifications
     */
    public function index(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 20);
        
        $result = $this->notificationService->getNotifications($request->user->id, $page, $limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    /**
     * PUT /api/v1/notifications/:id/read
     */
    public function markRead(Request $request, int $id): Response
    {
        try {
            $this->notificationService->markRead($id, $request->user->id);
            return json(['success' => true, 'code' => 200, 'message' => 'Marked as read']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            return json(['success' => false, 'code' => $code, 'error' => $e->getMessage()], $code);
        }
    }

    /**
     * GET /api/v1/notifications/settings
     */
    public function settings(Request $request): Response
    {
        $settings = $this->notificationService->getSettings($request->user->id);
        return json(['success' => true, 'code' => 200, 'data' => $settings]);
    }

    /**
     * PUT /api/v1/notifications/settings
     */
    public function updateSettings(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $this->notificationService->updateSettings($request->user->id, $data);
            return json(['success' => true, 'code' => 200, 'message' => 'Settings updated']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}