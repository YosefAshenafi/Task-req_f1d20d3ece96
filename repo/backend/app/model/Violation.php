<?php

namespace app\model;

use think\Model;

class Violation extends Model
{
    protected $table = 'violations';

    protected $type = [
        'user_id' => 'integer',
        'rule_id' => 'integer',
        'points' => 'integer',
        'created_by' => 'integer',
    ];

    public function rule()
    {
        return $this->belongsTo(ViolationRule::class, 'rule_id');
    }
}