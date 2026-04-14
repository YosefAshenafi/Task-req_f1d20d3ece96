<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\ExportService;
use app\service\DashboardService;
use app\model\Order;
use app\model\ActivityVersion;
use app\model\Violation;

class ExportController
{
    protected ExportService $exportService;
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->exportService = new ExportService();
        $this->dashboardService = new DashboardService();
    }

    /**
     * GET /api/v1/export/orders
     */
    public function orders(Request $request): Response
    {
        $format = $request->get('format', 'csv');
        $state = $request->get('state', '');

        $query = Order::order('id', 'desc');
        if (!empty($state)) {
            $query->where('state', $state);
        }
        $orders = $query->limit(500)->select();

        $data = [];
        $data[] = ['ID', 'Activity ID', 'State', 'Amount', 'Payment Method', 'Ticket', 'Created At'];
        foreach ($orders as $o) {
            $data[] = [$o->id, $o->activity_id, $o->state, $o->amount, $o->payment_method, $o->ticket_number ?? '', $o->created_at];
        }

        $filepath = $this->exportByFormat($data, 'orders', $format, $request->user->id);

        return $this->downloadResponse($filepath, 'orders_export', $format);
    }

    /**
     * GET /api/v1/export/activities
     */
    public function activities(Request $request): Response
    {
        $format = $request->get('format', 'csv');

        $activities = ActivityVersion::where('state', 'published')
            ->order('id', 'desc')
            ->limit(500)
            ->select();

        $data = [];
        $data[] = ['Group ID', 'Title', 'State', 'Version', 'Max Headcount', 'Published At'];
        foreach ($activities as $v) {
            $data[] = [$v->group_id, $v->title, $v->state, $v->version_number, $v->max_headcount, $v->published_at ?? ''];
        }

        $filepath = $this->exportByFormat($data, 'activities', $format, $request->user->id);

        return $this->downloadResponse($filepath, 'activities_export', $format);
    }

    /**
     * GET /api/v1/export/violations
     */
    public function violations(Request $request): Response
    {
        $format = $request->get('format', 'csv');

        $violations = Violation::order('id', 'desc')->limit(500)->select();

        $data = [];
        $data[] = ['ID', 'User ID', 'Rule ID', 'Points', 'Status', 'Created At'];
        foreach ($violations as $v) {
            $data[] = [$v->id, $v->user_id, $v->rule_id, $v->points, $v->status, $v->created_at];
        }

        $filepath = $this->exportByFormat($data, 'violations', $format, $request->user->id);

        return $this->downloadResponse($filepath, 'violations_export', $format);
    }

    protected function exportByFormat(array $data, string $name, string $format, int $userId): string
    {
        return match($format) {
            'png' => $this->exportService->exportToPng($this->flattenForPng($data), $name, $userId),
            'pdf' => $this->exportService->exportToPdf($this->flattenForPng($data), $name, $userId),
            default => $this->exportService->exportToExcel($data, $name, $userId),
        };
    }

    protected function flattenForPng(array $data): array
    {
        $flat = [];
        $headers = array_shift($data);
        foreach ($data as $i => $row) {
            $flat["Row " . ($i + 1)] = implode(' | ', array_map('strval', $row));
        }
        return $flat;
    }

    /**
     * GET /api/v1/export/download
     * Serve an export file for download.
     */
    public function download(Request $request): Response
    {
        $filename = $request->get('file', '');
        if (empty($filename)) {
            return json(['success' => false, 'code' => 400, 'error' => 'File parameter required'], 400);
        }

        // Sanitize: only allow basename, prevent directory traversal
        $filename = basename($filename);
        $filepath = runtime_path() . '/exports/' . $filename;

        if (!file_exists($filepath)) {
            return json(['success' => false, 'code' => 404, 'error' => 'Export file not found'], 404);
        }

        return download($filepath, $filename);
    }

    protected function downloadResponse(string $filepath, string $name, string $format): Response
    {
        $ext = match($format) {
            'png' => 'png',
            'pdf' => 'pdf',
            default => 'xlsx',
        };
        $contentType = match($format) {
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            default => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        };

        return json([
            'success' => true,
            'code' => 200,
            'data' => [
                'file' => basename($filepath),
                'format' => $format,
                'download_url' => '/api/v1/export/download?file=' . urlencode(basename($filepath)),
            ],
        ]);
    }
}
