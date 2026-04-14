<?php

namespace app\model;

use think\Model;

class ViolationRule extends Model
{
    protected $table = 'violation_rules';

    protected $type = [
        'points' => 'integer',
        'created_by' => 'integer',
    ];
}