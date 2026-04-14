<?php

namespace app\model;

use think\Model;

class UserGroupMember extends Model
{
    protected $table = 'user_group_members';

    protected $type = [
        'group_id' => 'integer',
        'user_id' => 'integer',
    ];
}