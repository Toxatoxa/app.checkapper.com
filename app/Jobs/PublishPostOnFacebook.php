<?php

namespace App\Jobs;

use App\Jobs\Job;
use Facebook\Facebook;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishPostOnFacebook extends Job implements ShouldQueue
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
    public function handle(Facebook $fb)
    {
        $filmHistory = $this->filmHistory;
        $filmUpdates = $filmHistory->showUpdates();

        $message = view('publications.fb_post', compact('filmHistory', 'filmUpdates'))->render();
        $params = [
            'message' => $message,
            'link'    => $filmHistory->getAppAffiliateLink('facebook'),
        ];

        $fb->post(env('FACEBOOK_US_PAGE_ID') . '/feed', $params, env('FACEBOOK_US_PAGE_TOKEN'));
    }
}
