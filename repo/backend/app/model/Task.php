<?php

namespace app\model;

use think\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $type = [
        'activity_id' => 'integer',
        'assigned_to' => 'integer',
    ];

    public function activity()
    {
        return $this->belongsTo(ActivityGroup::class, 'activity_id');
    }
}