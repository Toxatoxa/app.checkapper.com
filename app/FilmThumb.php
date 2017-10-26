<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilmThumb extends Model
{

    const SIZE_SMALL = '120x180';
    const SIZE_MEDIUM = '240x360';
    const SIZE_LARGE = '480x720';

    protected $dates = ['start_updating_at'];

    protected $fillable = ['film_id', 'remote_100'];

    protected $casts = [
        'local_sizes' => 'array',
    ];

    public function getSmallAttribute()
    {
        return $this->getUrl(self::SIZE_SMALL);
    }

    public function getMediumAttribute()
    {
        return $this->getUrl(self::SIZE_MEDIUM);
    }

    public function getLargeAttribute()
    {
        return $this->getUrl(self::SIZE_LARGE);
    }

    public function getUrlBySize($size)
    {
        return str_replace('100x100bb', $size . "bb", $this->remote_100);
    }

    private function getUrl($size)
    {
        return (!$this->start_updating_at && in_array($size, $this->local_sizes))
            ? url($this->getPathToFile($size))
            : $this->getUrlBySize($size);
    }

    public function getPathToFile($size)
    {
        return 'thumbs/'
            . $this->film->itunes_country
            . '/' . $this->film->genre_id
            . '/' . $this->film_id
            . '/' . $size . '.jpg';
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public static $allSizes = [
        self::SIZE_SMALL,
        self::SIZE_MEDIUM,
        self::SIZE_LARGE,
    ];
}
