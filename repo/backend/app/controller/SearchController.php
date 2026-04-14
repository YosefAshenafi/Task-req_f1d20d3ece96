<?php

namespace app\controller;

use think\Request;
use think\Response;
use app\service\SearchService;

class SearchController
{
    protected SearchService $searchService;

    public function __construct()
    {
        $this->searchService = new SearchService();
    }

    /**
     * GET /api/v1/search
     */
    public function index(Request $request): Response
    {
        $query = $request->get('q', '');
        $type = $request->get('type', '');
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 20);

        if (strlen($query) < 2) {
            return json(['success' => true, 'code' => 200, 'data' => ['list' => [], 'total' => 0]]);
        }

        $result = $this->searchService->search($query, $type, $page, $limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    /**
     * GET /api/v1/search/suggest
     */
    public function suggest(Request $request): Response
    {
        $query = $request->get('q', '');
        $limit = (int) $request->get('limit', 10);

        if (strlen($query) < 1) {
            return json(['success' => true, 'code' => 200, 'data' => []]);
        }

        $suggestions = $this->searchService->suggest($query, $limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $suggestions]);
    }

    /**
     * GET /api/v1/search/logistics
     */
    public function logistics(Request $request): Response
    {
        $query = $request->get('q', '');
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', 20);

        $result = $this->searchService->search($query, 'order', $page, $limit);
        
        return json(['success' => true, 'code' => 200, 'data' => $result]);
    }

    /**
     * GET /api/v1/index/status
     */
    public function status(Request $request): Response
    {
        $total = \app\model\SearchIndex::count();
        
        return json(['success' => true, 'code' => 200, 'data' => ['total' => $total]]);
    }

    /**
     * POST /api/v1/index/rebuild
     */
    public function rebuild(Request $request): Response
    {
        $this->searchService->rebuild();
        
        return json(['success' => true, 'code' => 200, 'message' => 'Index rebuilt']);
    }

    /**
     * POST /api/v1/index/cleanup
     */
    public function cleanup(Request $request): Response
    {
        $count = $this->searchService->cleanup();
        
        return json(['success' => true, 'code' => 200, 'message' => "Cleaned up {$count} entries"]);
    }
}