<?php

namespace app\model;

use think\Model;

class UserGroup extends Model
{
    protected $table = 'user_groups';

    public function members()
    {
        return $this->hasMany(UserGroupMember::class, 'group_id');
    }
}