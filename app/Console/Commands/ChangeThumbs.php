<?php

namespace App\Console\Commands;

use App\FilmThumb;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangeThumbs extends Command
{

    protected $signature = 'change:thumbs';

    protected $description = 'Command description';

    public function handle()
    {
        $query = DB::table('films')
            ->leftJoin('film_thumbs', 'films.id', '=', 'film_thumbs.film_id')
            ->whereNull('film_thumbs.id')
            ->select('films.id', 'films.artwork_url_100');

        if (!$query) {
            return;
        }

        $count = $query->count();
        
        $bar = $this->output->createProgressBar($count);

        DB::table('films')
            ->leftJoin('film_thumbs', 'films.id', '=', 'film_thumbs.film_id')
            ->whereNull('film_thumbs.id')
            ->select('films.id', 'films.artwork_url_100')
            ->orderBy('id')
            ->chunk(1000, function ($films) use ($bar) {
                $this->info('1');
                foreach ($films as $film) {
                    FilmThumb::create([
                        'film_id'    => $film->id,
                        'remote_100' => $film->artwork_url_100
                    ]);
                    $bar->advance();
                }
            });

        $bar->finish();
    }
}
