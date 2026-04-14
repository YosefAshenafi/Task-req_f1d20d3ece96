<?php

namespace app\service;

use app\model\Dashboard;
use app\model\Order;
use app\model\ActivityGroup;
use app\model\ActivityVersion;

class DashboardService
{
    public function getDefault(int $userId): array
    {
        $ordersByState = Order::field('state, count(*) as count')
            ->group('state')
            ->select()
            ->toArray();

        $activitiesByState = ActivityVersion::field('state, count(*) as count')
            ->group('state')
            ->select()
            ->toArray();

        $recentOrders = Order::order('id', 'desc')->limit(5)->select();

        return [
            'widgets' => [
                'orders_by_state' => $ordersByState,
                'activities_by_state' => $activitiesByState,
                'recent_orders' => array_map(fn($o) => [
                    'id' => $o->id,
                    'state' => $o->state,
                    'amount' => $o->amount,
                ], $recentOrders),
            ],
        ];
    }

    public function getCustom(int $userId): array
    {
        return Dashboard::where('user_id', $userId)->select()->toArray();
    }

    public function saveCustom(int $userId, array $data): array
    {
        $dashboard = new Dashboard();
        $dashboard->user_id = $userId;
        $dashboard->name = $data['name'];
        $dashboard->widgets = json_encode($data['widgets'] ?? []);
        $dashboard->is_default = $data['is_default'] ?? false;
        $dashboard->save();
        
        return $dashboard->toArray();
    }

    public function updateCustom(int $id, int $userId, array $data): array
    {
        $dashboard = Dashboard::find($id);
        if (!$dashboard || $dashboard->user_id != $userId) {
            throw new \Exception('Dashboard not found', 404);
        }

        if (isset($data['name'])) $dashboard->name = $data['name'];
        if (isset($data['widgets'])) $dashboard->widgets = json_encode($data['widgets']);
        $dashboard->save();
        
        return $dashboard->toArray();
    }
}