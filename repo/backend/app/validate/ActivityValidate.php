<?php

namespace app\validate;

use think\Validate;

class ActivityValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:200',
        'body' => 'max:5000',
        'max_headcount' => 'number|min:0',
        'signup_start' => 'date',
        'signup_end' => 'date',
    ];

    protected $message = [
        'title.require' => 'Title is required',
        'title.max' => 'Title cannot exceed 200 characters',
        'body.max' => 'Body cannot exceed 5000 characters',
        'max_headcount.number' => 'Max headcount must be a number',
        'max_headcount.min' => 'Max headcount cannot be negative',
        'signup_start.date' => 'Invalid start date',
        'signup_end.date' => 'Invalid end date',
    ];

    protected $scene = [
        'create' => ['title', 'body', 'max_headcount'],
        'update' => ['title', 'body'],
    ];
}