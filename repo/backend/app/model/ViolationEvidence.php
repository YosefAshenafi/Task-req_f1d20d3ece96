<?php

namespace app\model;

use think\Model;

class ViolationEvidence extends Model
{
    protected $table = 'violation_evidence';

    protected $type = [
        'violation_id' => 'integer',
    ];
}