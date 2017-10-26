<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['itunes_country', 'name'];

    public function scopeITunesCountry($query, $itunesCountry)
    {
        if (!$itunesCountry)
            $itunesCountry = 'us';
        $query->where('itunes_country', $itunesCountry);
    }
}
