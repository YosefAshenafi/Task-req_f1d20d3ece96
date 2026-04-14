<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\RecommendationService;

class RecommendationController
{
    protected RecommendationService $recommendationService;

    public function __construct()
    {
        $this->recommendationService = new RecommendationService();
    }

    /**
     * GET /api/v1/recommendations
     */
    public function index(Request $request): Response
    {
        $context = $request->get('context', 'list');
        $limit = (int) $request->get('limit', 10);

        $result = $this->recommendationService->getRecommendations($request->user->id, $context, $limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    /**
     * GET /api/v1/recommendations/popular
     */
    public function popular(Request $request): Response
    {
        $limit = (int) $request->get('limit', 10);
        $result = $this->recommendationService->getPopular($limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }
}