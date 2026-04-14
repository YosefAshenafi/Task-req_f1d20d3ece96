<?php

namespace app\model;

use think\Model;

class Dashboard extends Model
{
    protected $table = 'dashboards';

    protected $type = [
        'user_id' => 'integer',
    ];
}