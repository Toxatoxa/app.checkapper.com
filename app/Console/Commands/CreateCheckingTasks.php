<?php

namespace App\Console\Commands;

use App\Jobs\UpdateFilmsPrices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateCheckingTasks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:checking_tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create checking prices jobs';

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
        $takePerJob = 100;
        $skip = 0;

        while (true) {

            $films = DB::table('films')
                ->select('track_id')
                ->distinct()
                ->offset($skip)
                ->limit($takePerJob)
                ->pluck('track_id')
                ->all();

            if (!$films)
                break;

            dispatch((new UpdateFilmsPrices($films))->onQueue('update'));
            $skip += $takePerJob;
        }
    }
}
