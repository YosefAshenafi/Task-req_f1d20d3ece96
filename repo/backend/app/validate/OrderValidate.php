<?php

namespace app\validate;

use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'activity_id' => 'require|number',
        'team_lead_id' => 'number',
        'items' => 'array',
        'notes' => 'max:500',
        'payment_method' => 'max:50',
        'amount' => 'number',
        'ticket_number' => 'max:50',
    ];

    protected $message = [
        'activity_id.require' => 'Activity ID is required',
        'activity_id.number' => 'Invalid activity ID',
        'items.array' => 'Items must be an array',
        'notes.max' => 'Notes cannot exceed 500 characters',
        'payment_method.max' => 'Payment method cannot exceed 50 characters',
        'amount.number' => 'Amount must be a number',
        'ticket_number.max' => 'Ticket number cannot exceed 50 characters',
    ];
}