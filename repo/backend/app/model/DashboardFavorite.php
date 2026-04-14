<?php

namespace app\model;

use think\Model;

class DashboardFavorite extends Model
{
    protected $table = 'dashboard_favorites';

    protected $type = [
        'user_id' => 'integer',
        'widget_id' => 'string',
    ];
}