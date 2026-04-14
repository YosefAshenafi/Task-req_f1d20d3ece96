<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\UploadService;

class UploadController
{
    protected UploadService $uploadService;

    public function __construct()
    {
        $this->uploadService = new UploadService();
    }

    /**
     * POST /api/v1/upload
     * Upload a file with SHA-256 fingerprint.
     */
    public function upload(Request $request): Response
    {
        try {
            $file = $request->file('file');
            if (!$file) {
                return json([
                    'success' => false,
                    'code' => 400,
                    'error' => 'No file uploaded',
                ], 400);
            }

            $result = $this->uploadService->upload($file, $request->user);
            return json([
                'success' => true,
                'code' => 201,
                'data' => $result,
                'message' => 'File uploaded successfully',
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
     * GET /api/v1/upload/:id
     * Get file info.
     */
    public function show(Request $request, int $id): Response
    {
        try {
            $userId = $request->user ? $request->user->id : 0;
            $role = $request->user ? $request->user->role : '';
            $file = $this->uploadService->getFile($id, $userId, $role);
            return json([
                'success' => true,
                'code' => 200,
                'data' => $file,
            ]);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 404;
            return json([
                'success' => false,
                'code' => $code,
                'error' => $e->getMessage(),
            ], $code);
        }
    }

    /**
     * GET /api/v1/upload/:id/download
     * Download the file.
     */
    public function download(Request $request, int $id): Response
    {
        try {
            $userId = $request->user ? $request->user->id : 0;
            $role = $request->user ? $request->user->role : '';
            $fileInfo = $this->uploadService->download($id, $userId, $role);
            return download($fileInfo['file_path'], $fileInfo['original_name']);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 404,
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * DELETE /api/v1/upload/:id
     * Delete a file.
     */
    public function delete(Request $request, int $id): Response
    {
        try {
            $this->uploadService->deleteFile($id, $request->user);
            return json([
                'success' => true,
                'code' => 200,
                'message' => 'File deleted successfully',
            ]);
        } catch (\Exception $e) {
            return json([
                'success' => false,
                'code' => 400,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}