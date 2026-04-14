<?php

namespace app\model;

use think\Model;

class ActivityChangeLog extends Model
{
    protected $table = 'activity_change_logs';

    protected $type = [
        'group_id' => 'integer',
        'from_version' => 'integer',
        'to_version' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(ActivityGroup::class, 'group_id');
    }
}