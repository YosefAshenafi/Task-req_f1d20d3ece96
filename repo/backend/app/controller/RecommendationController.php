<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\RecommendationService;

class RecommendationController
{
    protected RecommendationService $service;

    public function __construct()
    {
        $this->service = new RecommendationService();
    }

    public function index(Request $request): Response
    {
        $context = $request->get('context', 'list');
        $limit = (int) $request->get('limit', 10);
        $userId = $request->user ? $request->user->id : 0;

        $result = $this->service->getRecommendations($userId, $context, $limit);
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    public function popular(Request $request): Response
    {
        $limit = (int) $request->get('limit', 10);
        $result = $this->service->getPopular($limit);
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    public function orders(Request $request): Response
    {
        $limit = (int) $request->get('limit', 10);
        $userId = $request->user ? $request->user->id : 0;

        $result = $this->service->getOrderRecommendations($userId, $limit);
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }
}
