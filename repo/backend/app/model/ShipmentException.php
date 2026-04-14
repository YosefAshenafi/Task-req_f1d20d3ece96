<?php

namespace app\model;

use think\Model;

class ShipmentException extends Model
{
    protected $table = 'shipment_exceptions';

    protected $type = [
        'shipment_id' => 'integer',
        'reported_by' => 'integer',
    ];
}