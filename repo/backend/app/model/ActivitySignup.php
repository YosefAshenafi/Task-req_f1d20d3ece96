<?php

namespace app\model;

use think\Model;

class ActivitySignup extends Model
{
    protected $table = 'activity_signups';

    protected $type = [
        'user_id' => 'integer',
        'group_id' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(ActivityGroup::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}