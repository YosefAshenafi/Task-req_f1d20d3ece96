<?php

namespace app\model;

use think\Model;

class ChecklistItem extends Model
{
    protected $table = 'checklist_items';

    protected $type = [
        'checklist_id' => 'integer',
        'completed_by' => 'integer',
    ];
}