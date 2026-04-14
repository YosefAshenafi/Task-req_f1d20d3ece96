<?php

namespace app\model;

use think\Model;

class SearchIndex extends Model
{
    protected $table = 'search_index';

    protected $type = [
        'entity_id' => 'integer',
    ];
}