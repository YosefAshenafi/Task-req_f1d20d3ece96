<?php

namespace app\model;

use think\Model;

class FileUpload extends Model
{
    protected $table = 'file_uploads';

    protected $type = [
        'uploaded_by' => 'integer',
        'size' => 'integer',
    ];
}