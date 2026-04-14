<?php

namespace app\model;

use think\Model;

class Checklist extends Model
{
    protected $table = 'checklists';

    protected $type = [
        'activity_id' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(ChecklistItem::class, 'checklist_id');
    }
}