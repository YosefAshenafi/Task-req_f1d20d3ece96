<?php

namespace app\model;

use think\Model;

class UserPreference extends Model
{
    protected $table = 'user_preferences';

    protected $type = [
        'user_id' => 'integer',
    ];
}