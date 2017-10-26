<?php

namespace App\Http\Controllers;

use App\FilmsHistory;
use Illuminate\Http\Request;

class FilmsController extends Controller
{

    protected $campaign = 'site';

    public function __construct(Request $request)
    {
        if ($request->get('ct')) {
            $this->campaign = $request->get('ct');
        }
    }

    public function index(Request $request)
    {
        $filmHistories = FilmsHistory::iTunesCountry($request->itunes_country)
            ->filter($request->all())
            ->with('film')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(50);

        return $this->transformFilmHistories($filmHistories);
    }

    public function show($hash)
    {
        $filmsHistory = $this->getHistoryByHash($hash);

        return $this->transformFilmHistory($filmsHistory, true);
    }

    public function seoredirect($hash)
    {
        $filmsHistory = $this->getHistoryByHash($hash);
        $campaign = $this->campaign;

        return view('films.seoredirect', compact('filmsHistory', 'hash', 'campaign'));
    }

    private function getHistoryByHash($hash)
    {
        return FilmsHistory::findOrFail(hashids()->decode($hash)[0]);
    }

    private function transformFilmHistories($filmHistories)
    {
        $data = [];
        foreach ($filmHistories as $history) {
            $data[] = $this->transformFilmHistory($history);
        }

        $array = [
            'pagination' => [
                "per_page"       => $filmHistories->perPage(),
                "current_page"   => $filmHistories->currentPage(),
                "next_page_url"  => $filmHistories->nextPageUrl(),
                "has_more_pages" => $filmHistories->hasMorePages(),
            ],
            "data"       => $data,
        ];

        return $array;
    }

    private function transformFilmHistory($filmHistory, $full = false)
    {
        $changes = [];

        if ($filmHistory->decreased_hd_rent) {
            $changes['decreased_hd_rent'] = [
                'discount'   => $filmHistory->getDiscount('track_hd_rental_price'),
                'price'      => $filmHistory->film->track_hd_rental_price,
                'last_price' => $filmHistory->track_hd_rental_price_last,
            ];
        } elseif ($filmHistory->decreased_sd_rent) {
            $changes['decreased_sd_rent'] = [
                'discount'   => $filmHistory->getDiscount('track_rental_price'),
                'price'      => $filmHistory->film->track_rental_price,
                'last_price' => $filmHistory->track_rental_price_last,
            ];
        }

        if ($filmHistory->available_hd_rent) {
            $changes['available_hd_rent'] = [
                'discount'   => null,
                'price'      => $filmHistory->film->track_hd_rental_price,
                'last_price' => null,
            ];
        } elseif ($filmHistory->available_sd_rent) {
            $changes['available_sd_rent'] = [
                'discount'   => null,
                'price'      => $filmHistory->film->track_rental_price,
                'last_price' => null,
            ];
        }

        if ($filmHistory->decreased_hd) {
            $changes['decreased_hd'] = [
                'discount'   => $filmHistory->getDiscount('track_hd_price'),
                'price'      => $filmHistory->film->track_hd_price,
                'last_price' => $filmHistory->track_hd_price_last,
            ];
        } elseif ($filmHistory->decreased_sd) {
            $changes['decreased_sd'] = [
                'discount'   => $filmHistory->getDiscount('track_price'),
                'price'      => $filmHistory->film->track_price,
                'last_price' => $filmHistory->track_price_last,
            ];
        }

        if ($filmHistory->available_hd) {
            $changes['available_hd'] = [
                'discount'   => null,
                'price'      => $filmHistory->film->track_hd_price,
                'last_price' => null,
            ];
        } elseif ($filmHistory->available_sd) {
            $changes['available_sd'] = [
                'discount'   => null,
                'price'      => $filmHistory->film->track_price,
                'last_price' => null,
            ];
        }

        $data = [
            'history_hash'      => $filmHistory->getRouteKey(),
            'updated_at'        => $filmHistory->created_at->formatLocalized('%A %d %B %Y'),
            'genre_id'          => $filmHistory->film->genre_id,
            'name'              => $filmHistory->film->track_name,
            'poster_img'        => $filmHistory->film->thumb->small,
            'poster_img_2x'     => $filmHistory->film->thumb->medium,
            'short_description' => $filmHistory->film->description,
            'changes'           => $changes,
            'prices'            => [
                'hd'      => $filmHistory->film->track_hd_price,
                'sd'      => $filmHistory->film->track_price,
                'hd_rent' => $filmHistory->film->track_hd_rental_price,
                'sd_rent' => $filmHistory->film->track_rental_price,
            ]
        ];

        if ($full) {

            $fullData = [
                'track_id'                => $filmHistory->film->track_id,
                'poster_img_4x'           => $filmHistory->film->thumb->large,
                'long_description'        => $filmHistory->film->long_description,
                'artist_name'             => $filmHistory->film->artist_name,
                'view_url'                => $filmHistory->film->getAffiliateLink($this->campaign),
                'preview_url'             => $filmHistory->film->preview_url,
                'time_millis'             => $filmHistory->film->track_time_millis,
                'country'                 => $filmHistory->film->country,
                'release_date'            => $filmHistory->film->release_date->toDateString(),
                'content_advisory_rating' => $filmHistory->film->content_advisory_rating,
            ];

            $data = array_merge($data, $fullData);
        }

        return $data;
    }
}
