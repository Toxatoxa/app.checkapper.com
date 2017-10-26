<?php

namespace App\Http\Controllers;

use App\Genre;
use App\ItunesCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        $array = Cache::remember('settings', 100, function () use ($request) {
            $stores = ItunesCountry::orderBy('name')
                ->get(['code', 'name', 'currency'])
                ->keyBy('code');

            $genres = Genre::iTunesCountry($request->itunes_country)
                ->pluck('name', 'id');

            return [
                'seo'   => [
                    'main' => [
                        'title'       => 'Fresh discounts, promotions and sales on ' . config('app.name'),
                        'description' => 'Fresh discounts, promotions and sales on ' . config('app.name'),
                    ],
                    'film' => [
                        'title'       => config('app.name') . ' :: Film ${filmName} Updates for ${updatedAt}',
                        'description' => config('app.name') . ' :: Film ${filmName} Updates for ${updatedAt}. ${filmDescription}'
                    ]
                ],
                'films' => [
                    'stores' => $stores,
                    'genres' => $genres,
                ],
            ];
        });

        return response()->json($array);
    }
}
