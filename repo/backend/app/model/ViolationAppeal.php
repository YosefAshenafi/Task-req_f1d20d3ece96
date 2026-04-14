<?php

namespace app\model;

use think\Model;

class ViolationAppeal extends Model
{
    protected $table = 'violation_appeals';

    protected $type = [
        'violation_id' => 'integer',
        'reviewer_id' => 'integer',
    ];
}