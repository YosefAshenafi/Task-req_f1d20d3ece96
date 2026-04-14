<?php

namespace app\model;

use think\Model;

class ActivityGroup extends Model
{
    protected $table = 'activity_groups';

    public function versions()
    {
        return $this->hasMany(ActivityVersion::class, 'group_id');
    }

    public function signups()
    {
        return $this->hasMany(ActivitySignup::class, 'group_id');
    }

    public function currentVersion()
    {
        return $this->hasOne(ActivityVersion::class, 'group_id')->order('version_number', 'desc');
    }
}