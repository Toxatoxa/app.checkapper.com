<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VkMarketItem extends Model
{

    protected $fillable = ['vk_market_id', 'vk_id', 'title', 'description', 'price', 'currency', 'thumb_photo'];

    public function market()
    {
        return $this->belongsTo('App\VkMarket', 'vk_market_id', 'id');
    }

    public function getLinkAttribute()
    {
        return 'https://vk.com/market-'.$this->vk_market_id.'?w=product-'.$this->vk_market_id.'_'.$this->vk_id;
    }

    public function getVkAttachmentLinkAttribute()
    {
        return 'market-'.$this->vk_market_id.'_'.$this->vk_id;
    }
}
