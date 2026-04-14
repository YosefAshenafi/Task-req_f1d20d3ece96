<?php

namespace app\model;

use think\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $type = [
        'created_by' => 'integer',
        'team_lead_id' => 'integer',
    ];

    public function stateHistory()
    {
        return $this->hasMany(OrderStateHistory::class, 'order_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'order_id');
    }
}