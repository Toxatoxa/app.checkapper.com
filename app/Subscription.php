<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{

    use Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['email', 'filter'];

    protected $casts = [
        'filter' => 'array',
    ];
}
