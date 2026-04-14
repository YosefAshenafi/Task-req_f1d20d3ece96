<?php

namespace app\model;

use think\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $type = [
        'user_id' => 'integer',
    ];
}