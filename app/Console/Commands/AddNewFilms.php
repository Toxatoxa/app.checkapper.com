<?php

namespace App\Console\Commands;

use App\Film;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddNewFilms extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:new_films';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new films';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = 'https://itunes.apple.com/';
        $limit = 200;
        $format = 'json';


        $countries = DB::table('itunes_countries')
            ->select('code')
            ->pluck('code');

        foreach ($countries as $countryCode) {
            foreach (self::$feedTypes as $feedType) {
                foreach (self::$genres as $genre) {

                    $genreVar = ($genre) ? '/genre=' . $genre : '';

                    // Example: https://itunes.apple.com/us/rss/topmovies/limit=100/genre=4401/xml
                    $url = $domain . $countryCode . '/rss/' . $feedType . '/limit=' . $limit . $genreVar . '/' . $format;

                    $this->info($url);

                    $response = json_decode(file_get_contents($url), true);

                    if (!$response || !isset($response['feed']['entry']))
                        continue;

                    $this->info('save');

                    $this->save($response['feed']['entry'], $countryCode);

                    sleep(1);
                }
            }
        }

    }

    public static function save($array, $countryCode)
    {
        foreach ($array as $appStoreApp) {

            $appStoreId = (isset($appStoreApp['id']['attributes']['im:id'])) ? $appStoreApp['id']['attributes']['im:id'] : null;

            if (!$appStoreId)
                continue;

            $filmModel = Film::where('track_id', $appStoreId)
                ->where('itunes_country', $countryCode)
                ->first();

            if ($filmModel)
                continue;

            Film::create([
                'status'         => Film::STATUS_NEW,
                'track_id'       => $appStoreId,
                'itunes_country' => $countryCode,
            ]);
        }
    }

    public static $feedTypes = [
        'topmovies',
        'topvideorentals',
    ];
    public static $genres = [
        '',
        '4434',
        '4401',
        '4402',
        '4431',
        '4403',
        '4404',
        '4422',
        '4405',
        '4406',
        '4407',
        '4420',
        '4408',
        '4409',
        '4425',
        '4426',
        '4410',
        '4428',
        '4421',
        '4433',
        '4423',
        '4424',
        '4411',
        '4432',
        '4412',
        '4429',
        '4413',
        '4414',
        '4415',
        '4417',
        '4416',
        '4427',
        '4430',
        '4419',
        '4418',
    ];
}
