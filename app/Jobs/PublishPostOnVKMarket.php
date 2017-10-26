<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;

class PublishPostOnVKMarket extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    private $news;

    /**
     * Create a new job instance.
     *
     */
    public function __construct($news)
    {
        $this->news = $news;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $news = $this->news;

        $message = view('publications.vk_post_market', compact('news'))->render();

        $client = new Client();

        $res = $client->request('POST', 'https://api.vk.com/method/wall.post', [
            'form_params' => [
                'access_token' => env('VK_API_TOKEN'),
                'owner_id'     => '-' . env('VK_MARKET_PAGE_ID'),
                'from_group'   => '1',
                'message'      => $message,
                'attachments'  => $news->item->vkAttachmentLink,
            ]
        ]);
    }
}
