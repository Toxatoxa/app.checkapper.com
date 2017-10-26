<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VkMarketsNew extends Model
{

    protected $fillable = ['vk_market_item_id', 'price', 'previous_price', 'currency'];


    public function item()
    {
        return $this->belongsTo('App\VkMarketItem', 'vk_market_item_id', 'id');
    }

    public function scopeForDay($query, $date)
    {
        $date = Carbon::parse($date);
        $query->where('created_at', '>=', $date->format('Y-m-d'))
            ->where('created_at', '<', $date->addDay()->format('Y-m-d'));
    }

    /**
     * @param $query
     */
    public function scopeToday($query)
    {
        $query->where('created_at', '>=', Carbon::now()->format('Y-m-d'))
            ->where('created_at', '<', Carbon::now()->addDay()->format('Y-m-d'));
    }

    public function getDiscountAttribute()
    {
        return round(100-$this->price*100/$this->previous_price);
    }
}
