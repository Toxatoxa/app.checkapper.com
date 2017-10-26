<?php

namespace App\Console\Commands;

use App\Jobs\PublishPostOnVKMarket;
use App\VkMarketsNew;
use Illuminate\Console\Command;

class PublishOnVKMarket extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:vk_market';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish on vk market group';

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
     * @return mixed
     */
    public function handle()
    {
        $vkMarketsNews = VkMarketsNew::today()->get();

        foreach ($vkMarketsNews as $news) {
            dispatch((new PublishPostOnVKMarket($news))->onQueue('vk'));
            sleep(10);
        }
    }
}
