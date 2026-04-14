<?php

namespace app\service;

use app\model\SearchIndex;
use app\model\ActivityGroup;
use app\model\ActivityVersion;
use app\model\Order;

class SearchService
{
    /**
     * Search entities.
     */
    public function search(string $query, string $type = '', int $page = 1, int $limit = 20): array
    {
        $queryLower = strtolower($query);
        $normalized = $this->normalizeText($query);
        
        $where = function($q) use ($query, $normalized) {
            $q->whereOr(function($sq) use ($query, $normalized) {
                $sq->where('title', 'like', "%{$query}%");
                $sq->whereOr('body', 'like', "%{$query}%");
                $sq->whereOr('normalized_text', 'like', "%{$normalized}%");
            });
        };

        if (!empty($type)) {
            $where->where('entity_type', $type);
        }

        $total = SearchIndex::where($where)->count();
        $results = SearchIndex::where($where)->page($page, $limit)->select();

        return [
            'list' => array_map(fn($r) => $this->formatResult($r), $results),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * Get search suggestions.
     */
    public function suggest(string $query, int $limit = 10): array
    {
        $results = SearchIndex::where('title', 'like', "{$query}%")
            ->limit($limit)
            ->select();

        return array_map(fn($r) => [
            'id' => $r->entity_id,
            'type' => $r->entity_type,
            'title' => $r->title,
        ], $results);
    }

    /**
     * Index an entity.
     */
    public function index(string $entityType, int $entityId, string $title, string $body, array $tags = []): void
    {
        $existing = SearchIndex::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->find();

        if ($existing) {
            $existing->title = $title;
            $existing->body = $body;
            $existing->tags = json_encode($tags);
            $existing->normalized_text = $this->normalizeText($title . ' ' . $body);
            $existing->pinyin_text = $this->toPinyin($title);
            $existing->save();
        } else {
            $index = new SearchIndex();
            $index->entity_type = $entityType;
            $index->entity_id = $entityId;
            $index->title = $title;
            $index->body = $body;
            $index->tags = json_encode($tags);
            $index->normalized_text = $this->normalizeText($title . ' ' . $body);
            $index->pinyin_text = $this->toPinyin($title);
            $index->save();
        }
    }

    /**
     * Remove from index.
     */
    public function remove(string $entityType, int $entityId): void
    {
        SearchIndex::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->delete();
    }

    /**
     * Rebuild entire index.
     */
    public function rebuild(): void
    {
        SearchIndex::whereRaw('1=1')->delete();

        $activities = ActivityVersion::where('state', 'published')->select();
        foreach ($activities as $v) {
            $this->index('activity', $v->group_id, $v->title, $v->body, json_decode($v->tags, true) ?: []);
        }

        $orders = Order::select();
        foreach ($orders as $o) {
            $this->index('order', $o->id, 'Order #' . $o->id, $o->notes ?: '', []);
        }
    }

    /**
     * Clean up orphaned entries.
     */
    public function cleanup(): int
    {
        $count = 0;

        $activityIds = ActivityVersion::column('group_id');
        $orphans = SearchIndex::where('entity_type', 'activity')
            ->whereNotIn('entity_id', $activityIds)
            ->delete();
        $count += $orphans;

        $orderIds = Order::column('id');
        $orphans = SearchIndex::where('entity_type', 'order')
            ->whereNotIn('entity_id', $orderIds)
            ->delete();
        $count += $orphans;

        return $count;
    }

    /**
     * Get spell correction suggestions.
     */
    public function correct(string $query): ?string
    {
        $results = SearchIndex::where('title', 'like', "{$query}%")->limit(1)->find();
        if ($results) {
            return $results->title;
        }
        return null;
    }

    protected function normalizeText(string $text): string
    {
        return strtolower(preg_replace('/[^a-z0-9\s]/', '', $text));
    }

    protected function toPinyin(string $text): string
    {
        return $text;
    }

    protected function formatResult(SearchIndex $r): array
    {
        return [
            'id' => $r->entity_id,
            'type' => $r->entity_type,
            'title' => $r->title,
            'body' => mb_substr($r->body, 0, 100),
            'url' => $this->getUrl($r->entity_type, $r->entity_id),
        ];
    }

    protected function getUrl(string $type, int $id): string
    {
        return match($type) {
            'activity' => "/src/views/activities/detail.html?id={$id}",
            'order' => "/src/views/orders/detail.html?id={$id}",
            default => "#",
        };
    }
}