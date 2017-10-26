<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VkMarket extends Model
{

    protected $fillable = ['group_id', 'name', 'screen_name', 'is_closed', 'photo', 'photo_medium', 'photo_big'];

}
