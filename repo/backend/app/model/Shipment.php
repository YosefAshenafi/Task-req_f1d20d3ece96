<?php

namespace app\model;

use think\Model;

class Shipment extends Model
{
    protected $table = 'shipments';

    protected $type = [
        'order_id' => 'integer',
    ];

    public function scanEvents()
    {
        return $this->hasMany(ScanEvent::class, 'shipment_id');
    }

    public function exceptions()
    {
        return $this->hasMany(ShipmentException::class, 'shipment_id');
    }
}