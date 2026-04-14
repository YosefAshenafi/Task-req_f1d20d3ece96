<?php

namespace app\model;

use think\Model;

class ScanEvent extends Model
{
    protected $table = 'scan_events';

    protected $type = [
        'shipment_id' => 'integer',
        'scanned_by' => 'integer',
    ];
}