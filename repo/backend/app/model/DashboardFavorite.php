<?php

namespace app\model;

use think\Model;

class DashboardFavorite extends Model
{
    protected $table = 'dashboard_favorites';

    protected $type = [
        'user_id' => 'integer',
        'dashboard_id' => 'integer',
    ];
}