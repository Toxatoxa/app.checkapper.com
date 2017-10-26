<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Thujohn\Twitter\Facades\Twitter;

//use Thujohn\Twitter\Twitter;

class PublishPostOnTwitter extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $filmHistory;

    /**
     * Create a new job instance.
     *
     */
    public function __construct($filmHistory)
    {
        $this->filmHistory = $filmHistory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Twitter $witter)
    {
        $filmHistory = $this->filmHistory;
        $filmUpdates = $filmHistory->showUpdates();

        $message = view('publications.twitter_post', compact('filmHistory', 'filmUpdates'))->render();

        Twitter::post('statuses/update.json', [
            'status' => $message
        ]);
    }
}
