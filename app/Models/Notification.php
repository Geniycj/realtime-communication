<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'type',
        'data',
        'notifiable_type',
        'notification_id'
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
        'read_at',
    ];
}
