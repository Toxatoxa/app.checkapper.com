<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;

class PublishPostOnVk extends Job implements ShouldQueue
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
    public function handle()
    {
        $filmHistory = $this->filmHistory;

        $filmUpdates = $filmHistory->showUpdates();
        $message = view('publications.vk_post', compact('filmHistory', 'filmUpdates'))->render();

        $client = new Client();

        $res = $client->request('POST', 'https://api.vk.com/method/wall.post', [
            'form_params' => [
                'access_token' => env('VK_API_TOKEN'),
                'owner_id'     => '-' . env('VK_PAGE_ID'),
                'from_group'   => '1',
                'message'      => $message,
                'attachments'  => $filmHistory->film->track_view_url,
                //'guid'         => '4',
            ]
        ]);

    }
}
