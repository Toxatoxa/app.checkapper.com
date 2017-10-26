<?php

namespace App\Console\Commands;

use App\FilmsHistory;
use App\FilmThumb;
use App\Jobs\SaveThumbJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckNewThumbs extends Command
{

    protected $signature = 'check:thumbs';

    protected $description = 'Command description';

    public function handle()
    {
        FilmThumb::whereNotNull('start_updating_at')
            ->where('start_updating_at', '<=', Carbon::now()->subMinutes(10)->toDateTimeString())
            ->update(['start_updating_at' => null, 'local_sizes' => '[]']);

        $filmHistories = FilmsHistory::whereHas('film.thumb', function($query) {
            $query->where('local_sizes', '[]')
                ->whereNull('start_updating_at');
        })
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        foreach ($filmHistories as $filmHistory) {
            $filmHistory->film->thumb->start_updating_at = Carbon::now();
            $filmHistory->film->thumb->save();
            dispatch((new SaveThumbJob($filmHistory->film->thumb->id))->onConnection('database')->onQueue('thumbs'));
        }
    }
}
