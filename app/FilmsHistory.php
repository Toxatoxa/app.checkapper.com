<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FilmsHistory extends Model
{

    protected $table = 'films_history';

    public function getRouteKey()
    {
        return hashids()->encode($this->getKey());
    }

    public function scopeITunesCountry($query, $itunesCountry)
    {
        if (!$itunesCountry) {
            $itunesCountry = 'us';
        }
        $query->where('itunes_country', $itunesCountry);
    }

    public function scopeForDay($query, $date)
    {
        $date = Carbon::parse($date);
        $query->where('created_at', '>=', $date->format('Y-m-d'))
            ->where('created_at', '<', $date->addDay()->format('Y-m-d'));
    }

    public function getAppAffiliateLink($campaign = 'undefined')
    {
        //https://app.checkapper.com/update/79kM?ct=facebook1
        return config('app.backend_url') . '/update/' . $this->getRouteKey() . '?ct=' . $campaign;
    }

    public function scopeFilter($query, array $filmFilter)
    {
        $filmFilter = FilmsFilter::createFromArray($filmFilter);

        if ($filmFilter->get('is_novelty') || $filmFilter->get('genre_id')) {
            $query->whereHas('film', function ($query) use ($filmFilter) {

                if ($filmFilter->get('is_novelty')) {
                    $query->where('release_date', '>=', Carbon::now()->addMonth(-2)->format('Y-m-d'));
                }

                if ($filmFilter->get('genre_id') && $filmFilter->genre_id) {
                    $query->where('genre_id', $filmFilter->genre_id);
                }
            });
        }

        if ($filmFilter->get('decreased_hd_rent')
            || $filmFilter->get('decreased_sd_rent')
            || $filmFilter->get('available_hd_rent')
            || $filmFilter->get('available_sd_rent')
            || $filmFilter->get('decreased_hd')
            || $filmFilter->get('decreased_sd')
            || $filmFilter->get('available_hd')
            || $filmFilter->get('available_sd')
        ) {
            $query->where(function ($query) use ($filmFilter) {
                $query->where('id', 0);

                foreach (self::$varsArray as $item) {
                    if ($filmFilter->get($item)) {
                        $query->orWhere($item, 1);
                    }
                }
            });
        }
    }

    public function getSeoTitleAttribute()
    {
        return $this->showUpdatesText() . ' Film: ' . $this->film->track_name;
    }

    public function getSeoDescriptionAttribute()
    {
        $params = [
            $this->film->duration,
            $this->film->genre->name,
            $this->film->release_date->year,
            $this->film->country,
        ];

        return implode(' | ', $params) . ' | ' . $this->film->long_description;
    }

    /**
     * @param $query
     */
    public function scopeToday($query)
    {
        $query->where('created_at', '>=', Carbon::now()->format('Y-m-d'))
            ->where('created_at', '<', Carbon::now()->addDay()->format('Y-m-d'));
    }

    public function getDiscount($type)
    {
        if (!in_array($type, self::$pricesArray)) {
            return null;
        }

        return round(100 - ($this->$type / $this->{$type . '_last'}) * 100);
    }

    public function film()
    {
        return $this->belongsTo('App\Film', 'film_id', 'id');
    }

    public function showDiff()
    {
        $arr = [];

        foreach (self::$pricesArray as $priceField) {
            $current = $this->$priceField;
            $last = $this->{$priceField . '_last'};

            if ($current !== $last) {
                $arr[$priceField] = [
                    'last'    => $last,
                    'current' => $current,
                ];
            }
        }

        return $arr;
    }

    public function showUpdatesText($description = false)
    {
        $text = '';
        foreach ($this->showUpdates() as $update) {

            if ($update == 'decreased_hd_rent') {
                $text .= '-' . $this->getDiscount('track_hd_rental_price') . '% Rent Price Drop: $' . $this->film->track_hd_rental_price . '' . (($this->track_hd_rental_price_last) ? ' (was: $' . $this->track_hd_rental_price_last . ')' : '') . '[HD version]. ';
            } elseif ($update == 'decreased_sd_rent') {
                $text .= '-' . $this->getDiscount('track_rental_price') . '% Rent Price Drop: $' . $this->film->track_rental_price . '' . (($this->track_rental_price_last) ? ' (was: $' . $this->track_rental_price_last . ')' : '') . '[SD version]. ';
            }

            if ($this->available_hd_rent) {
                $text .= 'Rent Availible: $' . $this->film->track_hd_rental_price . '[HD version]. ';
            } elseif ($this->available_sd_rent) {
                $text .= 'Rent Availible: $' . $this->film->track_rental_price . '[SD version]. ';
            }

            if ($this->decreased_hd) {
                $text .= '-' . $this->getDiscount('track_hd_price') . '% Price Drop: $' . $this->film->track_hd_price . '' . (($this->track_hd_price_last) ? ' (was: $' . $this->track_hd_price_last . ')' : '') . '[HD version]. ';
            } elseif ($this->decreased_sd) {
                $text .= '-' . $this->getDiscount('track_price') . '% Price Drop: $' . $this->film->track_price . '' . (($this->track_sd_price_last) ? ' (was: $' . $this->track_sd_price_last . ')' : '') . '[SD version]. ';
            }

            if ($this->available_hd) {
                $text .= 'Availible: $' . $this->film->track_hd_price . '[HD version]. ';
            } elseif ($this->available_sd) {
                $text .= 'Availible: $' . $this->film->track_price . '[SD version]. ';
            }
        }

        return $text;
    }

    public function showUpdates()
    {
        $arr = [];

        // Became available HD version
        if ($this->track_hd_price_last === null && $this->track_hd_price) {
            array_push($arr, 'available_hd');
        }

        // Decreased a price for HD quality
        if ($this->track_hd_price_last && $this->track_hd_price && $this->track_hd_price < $this->track_hd_price_last) {
            array_push($arr, 'decreased_hd');
        }

        // Became available SD version
        if ($this->track_price_last === null && $this->track_price) {
            array_push($arr, 'available_sd');
        }

        // Decreased a price for SD quality
        if ($this->track_price_last && $this->track_price && $this->track_price < $this->track_price_last) {
            array_push($arr, 'decreased_sd');
        }

        // Became available for HD rent
        if ($this->track_hd_rental_price_last === null && $this->track_hd_rental_price) {
            array_push($arr, 'available_hd_rent');
        }

        // Decreased a price for HD rent
        if ($this->track_hd_rental_price_last && $this->track_hd_rental_price && $this->track_hd_rental_price < $this->track_hd_rental_price_last) {
            array_push($arr, 'decreased_hd_rent');
        }

        // Became available for SD rent
        if ($this->track_rental_price_last === null && $this->track_rental_price) {
            array_push($arr, 'available_sd_rent');
        }

        // Decreased a price for SD rent
        if ($this->track_rental_price_last && $this->track_rental_price && $this->track_rental_price < $this->track_rental_price_last) {
            array_push($arr, 'decreased_sd_rent');
        }

        return $arr;
    }

    public function updateHistoryInfo()
    {
        foreach (self::$varsArray as $item) {
            $this->$item = 0;
        }

        $updates = $this->showUpdates();

        foreach ($updates as $update) {
            $this->$update = 1;
        }

        if ($updates) {
            $this->save();

            return true;
        }

        return false;
    }

    public static function checkPrices(Film $filmModel, $filmArray, $country)
    {
        $filmsHistoryModel = new FilmsHistory();
        $filmsHistoryModel->collection_price = (isset($filmArray['collectionPrice'])) ? number_format((float) $filmArray['collectionPrice'], 2, '.', '') : null;
        $filmsHistoryModel->collection_hd_price = (isset($filmArray['collectionHdPrice'])) ? number_format((float) $filmArray['collectionHdPrice'], 2, '.', '') : null;
        $filmsHistoryModel->track_price = (isset($filmArray['trackPrice'])) ? number_format((float) $filmArray['trackPrice'], 2, '.', '') : null;
        $filmsHistoryModel->track_hd_price = (isset($filmArray['trackHdPrice'])) ? number_format((float) $filmArray['trackHdPrice'], 2, '.', '') : null;
        $filmsHistoryModel->track_rental_price = (isset($filmArray['trackRentalPrice'])) ? number_format((float) $filmArray['trackRentalPrice'], 2, '.', '') : null;
        $filmsHistoryModel->track_hd_rental_price = (isset($filmArray['trackHdRentalPrice'])) ? number_format((float) $filmArray['trackHdRentalPrice'], 2, '.', '') : null;

        $filmsHistoryModel->collection_price_last = $filmModel->collection_price;
        $filmsHistoryModel->collection_hd_price_last = $filmModel->collection_hd_price;
        $filmsHistoryModel->track_price_last = $filmModel->track_price;
        $filmsHistoryModel->track_hd_price_last = $filmModel->track_hd_price;
        $filmsHistoryModel->track_rental_price_last = $filmModel->track_rental_price;
        $filmsHistoryModel->track_hd_rental_price_last = $filmModel->track_hd_rental_price;

        $filmsHistoryModel->film_id = $filmModel->id;
        $filmsHistoryModel->itunes_country = $country;

        return ($filmsHistoryModel->updateHistoryInfo()) ? true : false;
    }

    public static $pricesArray = [
        'collection_price',
        'collection_hd_price',
        'track_price',
        'track_hd_price',
        'track_rental_price',
        'track_hd_rental_price',
    ];

    public static $varsArray = [
        'available_hd',
        'decreased_hd',
        'available_sd',
        'decreased_sd',
        'available_hd_rent',
        'decreased_hd_rent',
        'available_sd_rent',
        'decreased_sd_rent',
    ];
}

class FilmsFilter
{

    static function createFromArray($array)
    {
        $filmFilter = new FilmsFilter();

        foreach ($array as $key => $value) {
            $filmFilter->$key = $value;
        }

        return $filmFilter;
    }

    public function get($filter)
    {
        return isset($this->$filter) ? $this->$filter : null;
    }
}