<?php

namespace app\model;

use think\Model;

class ActivityVersion extends Model
{
    protected $table = 'activity_versions';

    protected $type = [
        'version_number' => 'integer',
        'max_headcount' => 'integer',
        'current_signups' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(ActivityGroup::class, 'group_id');
    }

    public function latest()
    {
        return $this->hasOne(ActivityVersion::class, 'group_id')->order('version_number', 'desc');
    }
}