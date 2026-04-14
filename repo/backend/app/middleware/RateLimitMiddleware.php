<?php

namespace app\middleware;

use think\Request;
use think\Response;

class RateLimitMiddleware
{
    protected static array $requestCounts = [];
    protected static int $windowSeconds = 60;
    protected static int $maxRequests = 60;

    public function handle(Request $request, \Closure $next): Response
    {
        $ip = $request->ip();
        $key = $ip . ':' . date('YmdHi');
        $now = time();

        if (!isset(self::$requestCounts[$key])) {
            self::$requestCounts[$key] = ['ip' => $ip, 'count' => 0, 'reset_at' => $now + self::$windowSeconds];
        }

        self::$requestCounts[$key]['count']++;

        if (self::$requestCounts[$key]['count'] > self::$maxRequests) {
            return json([
                'success' => false,
                'code' => 429,
                'error' => 'Rate limit exceeded',
            ], 429);
        }

        return $next($request);
    }
}