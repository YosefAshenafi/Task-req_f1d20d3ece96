<?php

namespace app\model;

use think\Model;

class Staffing extends Model
{
    protected $table = 'staffing';

    protected $type = [
        'activity_id' => 'integer',
        'required_count' => 'integer',
    ];
}