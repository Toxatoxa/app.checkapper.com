<?php

namespace App;

use App\Jobs\SaveThumbJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{

    protected $fillable = ['status', 'track_id', 'itunes_country'];

    protected $hidden = ['status'];

    protected $dates = ['release_date'];

    const STATUS_NEW = 'new';
    const STATUS_UPDATING = 'updating';
    const STATUS_DONE = 'done';
    const STATUS_ERROR = 'error';

    public function film_history()
    {
        return $this->hasMany('App\FilmsHistory');
    }

    public function thumb()
    {
        return $this->hasOne(FilmThumb::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function getDescriptionAttribute()
    {
        return ($this->short_description) ? $this->short_description : words($this->long_description);
    }

    public function getTrackTimeSecAttribute()
    {
        return round($this->track_time_millis / 1000);
    }

    public function getDurationAttribute()
    {
        return gmdate("H:i", round($this->track_time_millis / 1000));
    }

    public function getAffiliateLink($campaign = 'undefined')
    {
        preg_match('/\/movie\/(.*)\/id/', $this->track_view_url, $matches);
        $movieName = (isset($matches[1])) ? $matches[1] : '';

        return 'https://itunes.apple.com/' . $this->itunes_country . '/movie/' . $movieName . '/id' . $this->track_id . '?mt=6&at=' . config('settings.itunes_affiliate_token') . '&ct=' . $campaign;
    }

    /**
     * @param $filmModel
     * @param $filmArray
     * @return mixed
     */
    public static function createFromAPI($filmModel, $filmArray, $country)
    {
        $filmModel->status = Film::STATUS_DONE;
        $filmModel->track_id = $filmArray['trackId'];
        $filmModel->itunes_country = $country;

        if (isset($filmArray['trackName'])) {
            $filmModel->track_name = $filmArray['trackName'];
        }

        if (isset($filmArray['artistName'])) {
            $filmModel->artist_name = $filmArray['artistName'];
        }

        if (isset($filmArray['trackCensoredName'])) {
            $filmModel->track_censored_name = $filmArray['trackCensoredName'];
        }

        if (isset($filmArray['trackViewUrl'])) {
            $filmModel->track_view_url = $filmArray['trackViewUrl'];
        }

        if (isset($filmArray['previewUrl'])) {
            $filmModel->preview_url = $filmArray['previewUrl'];
        }

        if (isset($filmArray['collectionPrice'])) {
            $filmModel->collection_price = $filmArray['collectionPrice'];
        }

        if (isset($filmArray['collectionHdPrice'])) {
            $filmModel->collection_hd_price = $filmArray['collectionHdPrice'];
        }

        if (isset($filmArray['trackPrice'])) {
            $filmModel->track_price = $filmArray['trackPrice'];
        }

        if (isset($filmArray['trackHdPrice'])) {
            $filmModel->track_hd_price = $filmArray['trackHdPrice'];
        }

        if (isset($filmArray['trackRentalPrice'])) {
            $filmModel->track_rental_price = $filmArray['trackRentalPrice'];
        }

        if (isset($filmArray['trackHdRentalPrice'])) {
            $filmModel->track_hd_rental_price = $filmArray['trackHdRentalPrice'];
        }

        if (isset($filmArray['trackTimeMillis'])) {
            $filmModel->track_time_millis = $filmArray['trackTimeMillis'];
        }

        if (isset($filmArray['country'])) {
            $filmModel->country = $filmArray['country'];
        }

        if (isset($filmArray['currency'])) {
            $filmModel->currency = $filmArray['currency'];
        }

        if (isset($filmArray['releaseDate'])) {
            $filmModel->release_date = (new Carbon($filmArray['releaseDate']))->toDateTimeString();
        }

        if (isset($filmArray['primaryGenreName'])) {

            $genre = Genre::where('name', $filmArray['primaryGenreName'])
                ->where('itunes_country', $country)
                ->first();

            if (!$genre) {
                $genre = Genre::create([
                    'itunes_country' => $country,
                    'name'           => $filmArray['primaryGenreName'],
                ]);
            }

            $filmModel->genre_id = $genre->id;
        }

        if (isset($filmArray['contentAdvisoryRating'])) {
            $filmModel->content_advisory_rating = $filmArray['contentAdvisoryRating'];
        }

        if (isset($filmArray['shortDescription'])) {
            $filmModel->short_description = $filmArray['shortDescription'];
        }

        if (isset($filmArray['longDescription'])) {
            $filmModel->long_description = $filmArray['longDescription'];
        }

        $filmModel->save();

        if (!$filmModel->thumb) {
            $filmThumb = FilmThumb::create([
                'film_id'    => $filmModel->id,
                'remote_100' => $filmArray['artworkUrl100']
            ]);

            dispatch((new SaveThumbJob($filmThumb->id))->onConnection('database')->onQueue('thumbs'));
        }

        return $filmModel;
    }
}
