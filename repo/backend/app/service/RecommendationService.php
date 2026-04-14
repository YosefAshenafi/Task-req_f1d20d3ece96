<?php

namespace app\service;

use app\model\ActivityGroup;
use app\model\ActivityVersion;

class RecommendationService
{
    /**
     * Get personalized recommendations.
     */
    public function getRecommendations(int $userId, string $context = 'list', int $limit = 10): array
    {
        $activities = ActivityVersion::where('state', 'published')
            ->order('published_at', 'desc')
            ->limit($limit * 2)
            ->select();

        $results = [];
        $now = time();
        
        foreach ($activities as $v) {
            $results[] = [
                'id' => $v->group_id,
                'title' => $v->title,
                'tags' => json_decode($v->tags, true) ?: [],
                'signup_count' => \app\model\ActivitySignup::where('group_id', $v->group_id)->count(),
                'published_at' => $v->published_at,
            ];
        }

        $results = array_slice($results, 0, $limit);
        
        return [
            'context' => $context,
            'list' => $results,
        ];
    }

    /**
     * Get popular activities (fallback for cold start).
     */
    public function getPopular(int $limit = 10): array
    {
        $activities = ActivityVersion::where('state', 'published')
            ->order('published_at', 'desc')
            ->limit($limit)
            ->select();

        return array_map(fn($v) => [
            'id' => $v->group_id,
            'title' => $v->title,
            'tags' => json_decode($v->tags, true) ?: [],
            'signup_count' => \app\model\ActivitySignup::where('group_id', $v->group_id)->count(),
        ], $activities);
    }
}