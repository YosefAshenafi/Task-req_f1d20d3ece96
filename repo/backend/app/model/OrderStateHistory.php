<?php

namespace app\model;

use think\Model;

class OrderStateHistory extends Model
{
    protected $table = 'order_state_history';

    protected $type = [
        'order_id' => 'integer',
        'changed_by' => 'integer',
    ];
}