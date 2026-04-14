<?php

namespace app\service;

use app\model\AuditTrail;

class AuditService
{
    public function log(int $userId, string $entityType, int $entityId, string $action, string $oldState = '', string $newState = '', array $metadata = []): void
    {
        $entry = new AuditTrail();
        $entry->user_id = $userId;
        $entry->entity_type = $entityType;
        $entry->entity_id = $entityId;
        $entry->action = $action;
        $entry->old_state = $oldState;
        $entry->new_state = $newState;
        $entry->metadata = json_encode($metadata);
        $entry->save();
    }

    public function query(int $userId, string $entityType = '', int $entityId = 0, string $action = '', string $dateFrom = '', string $dateTo = '', int $page = 1, int $limit = 50): array
    {
        $query = AuditTrail::order('id', 'desc');

        if (!empty($entityType)) $query->where('entity_type', $entityType);
        if ($entityId > 0) $query->where('entity_id', $entityId);
        if (!empty($action)) $query->where('action', $action);
        if (!empty($dateFrom)) $query->where('created_at', '>=', $dateFrom);
        if (!empty($dateTo)) $query->where('created_at', '<=', $dateTo);

        $total = $query->count();
        $entries = $query->page($page, $limit)->select();

        $list = [];
        foreach ($entries as $e) {
            $list[] = $this->format($e);
        }

        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    protected function format(AuditTrail $e): array
    {
        return [
            'id' => $e->id,
            'user_id' => $e->user_id,
            'entity_type' => $e->entity_type,
            'entity_id' => $e->entity_id,
            'action' => $e->action,
            'old_state' => $e->old_state,
            'new_state' => $e->new_state,
            'metadata' => json_decode($e->metadata, true),
            'created_at' => $e->created_at,
        ];
    }
}