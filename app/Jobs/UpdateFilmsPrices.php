<?php

namespace App\Jobs;

use App\Check;
use App\Film;
use App\FilmsHistory;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFilmsPrices extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $films;

    /**
     * Create a new job instance.
     * @param $films
     */
    public function __construct($films)
    {
        $this->films = (is_array($films)) ? implode(',', $films) : $films;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Запрос в itunes и обновление цены.
        if (!$this->films)
            return;

        $countries = ['us', 'ru', 'ch'];

        foreach ($countries as $country) {

            $url = 'https://itunes.apple.com/lookup?country=' . $country . '&id=' . $this->films;

            $response = json_decode(file_get_contents($url), true);

            // Errors
            if (!$response || !isset($response['resultCount']) || $response['resultCount'] < 1) {
                continue;
            }

            foreach ($response['results'] as $film) {

                $filmModel = Film::where('track_id', $film['trackId'])->where('itunes_country', $country)->first();

                if (!$filmModel) {
                    Film::createFromAPI(new Film(), $film, $country);
                } elseif ($filmModel->status == Film::STATUS_NEW) {
                    Film::createFromAPI($filmModel, $film, $country);
                } else {
                    if (FilmsHistory::checkPrices($filmModel, $film, $country))
                        Film::createFromAPI($filmModel, $film, $country);
                }

                // Check
                Check::create([
                    'itunes_country' => $country,
                    'film_id' => $film['trackId'],
                    'response' => json_encode($film),
                ]);
            }
        }


    }
}
