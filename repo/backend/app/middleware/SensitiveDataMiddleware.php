<?php

namespace app\middleware;

use think\Request;
use think\Response;

class SensitiveDataMiddleware
{
    protected static array $sensitiveFields = [
        'user' => ['password_hash', 'salt', 'invoice_address'],
        'order' => ['invoice_address'],
    ];

    public function handle(Request $request, \Closure $next): Response
    {
        $response = $next($request);
        
        if ($request->user && $request->user->role !== 'administrator') {
            return $this->maskSensitiveData($response, $request);
        }

        return $response;
    }

    protected function maskSensitiveData(Response $response, Request $request): Response
    {
        $content = $response->getContent();
        $data = json_decode($content, true);
        
        if (!$data || !isset($data['data'])) {
            return $response;
        }

        $entityType = $this->detectEntityType($request);
        
        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = $this->maskFields($data['data'], $entityType);
        }

        $response->content(json_encode($data));
        return $response;
    }

    protected function maskFields(array $data, string $entityType): array
    {
        $fields = self::$sensitiveFields[$entityType] ?? [];
        
        foreach ($fields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }

        return $data;
    }

    protected function detectEntityType(Request $request): string
    {
        $path = $request->path();
        if (strpos($path, 'users') !== false) return 'user';
        if (strpos($path, 'orders') !== false) return 'order';
        return '';
    }
}