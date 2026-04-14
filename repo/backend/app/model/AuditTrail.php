<?php

namespace app\model;

use think\Model;

class AuditTrail extends Model
{
    protected $table = 'audit_trail';

    protected $type = [
        'user_id' => 'integer',
        'entity_id' => 'integer',
    ];
}