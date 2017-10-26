<?php

namespace App\Console\Commands;

use App\FilmsHistory;
use Illuminate\Console\Command;

class UpdateFilmHistoryValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:films_history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Films History Values';

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
        $films = FilmsHistory::all();

        foreach($films as $film) {
            if(!$film->updateHistoryInfo()) {
                $film->delete();
            }
        }
    }
}
