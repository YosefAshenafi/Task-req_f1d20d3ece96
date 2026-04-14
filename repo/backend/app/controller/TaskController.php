<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\TaskService;

class TaskController
{
    protected TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    /**
     * GET /api/v1/activities/:activity_id/tasks
     */
    public function index(Request $request, int $activityId): Response
    {
        try {
            $tasks = $this->taskService->getTasks($activityId);
            return json(['success' => true, 'code' => 200, 'data' => $tasks]);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 404, 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * POST /api/v1/activities/:activity_id/tasks
     */
    public function create(Request $request, int $activityId): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $task = $this->taskService->createTask($activityId, $data, $request->user);
            return json(['success' => true, 'code' => 201, 'data' => $task, 'message' => 'Task created'], 201);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * PUT /api/v1/tasks/:id
     */
    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $task = $this->taskService->updateTask($id, $data, $request->user);
            return json(['success' => true, 'code' => 200, 'data' => $task, 'message' => 'Task updated']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * PUT /api/v1/tasks/:id/status
     */
    public function updateStatus(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $status = $data['status'] ?? '';
        try {
            $task = $this->taskService->updateStatus($id, $status, $request->user);
            return json(['success' => true, 'code' => 200, 'data' => $task, 'message' => 'Status updated']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * DELETE /api/v1/tasks/:id
     */
    public function delete(Request $request, int $id): Response
    {
        try {
            $this->taskService->deleteTask($id, $request->user);
            return json(['success' => true, 'code' => 200, 'message' => 'Task deleted']);
        } catch (\Exception $e) {
            return json(['success' => false, 'code' => 400, 'error' => $e->getMessage()], 400);
        }
    }
}