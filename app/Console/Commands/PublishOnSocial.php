<?php

namespace App\Console\Commands;

use App\FilmsHistory;
use App\Jobs\PublishPostOnFacebook;
use App\Jobs\PublishPostOnTwitter;
use App\Jobs\PublishPostOnVk;
use Illuminate\Console\Command;

class PublishOnSocial extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:social';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $filmHistories = FilmsHistory::today()
//            ->whereIn('itunes_country', ['ru', 'us'])
            ->where('itunes_country', 'us')
            ->get();

        foreach ($filmHistories as $filmHistory) {

            // Publish on Facebook just for US
//            if (in_array($filmHistory->itunes_country, ['ru', 'us']))
            dispatch((new PublishPostOnFacebook($filmHistory))->onQueue('publish'));

            // Publish on VK just for RU
//            if ($filmHistory->itunes_country == 'ru')
//                dispatch((new PublishPostOnVk($filmHistory))->onQueue('publish'));

            // Publish on Twitter just for US
//            if (in_array($filmHistory->itunes_country, ['us']))
//            dispatch((new PublishPostOnTwitter($filmHistory))->onQueue('publish'));
        }
    }
}
