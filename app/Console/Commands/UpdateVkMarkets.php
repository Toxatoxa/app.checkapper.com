<?php

namespace App\Console\Commands;

use App\VkMarket;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class UpdateVkMarkets extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vkmarkets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the list of VK markets';

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
        $client = new Client();
        $res = $client->get('https://api.vk.com/method/groups.getCatalog?category_id=11&access_token=' . env('VK_API_TOKEN'));

        $response = \GuzzleHttp\json_decode($res->getBody(), true);

        if (isset($response['response']) && $response['response']) {

            $currentMarkets = VkMarket::get(['group_id'])
                ->toArray();

            $marketsArray = [];
            foreach ($currentMarkets as $currentMarket) {
                array_push($marketsArray, $currentMarket['group_id']);
            }

            $i = 0;
            foreach ($response['response'] as $group) {

                if (!$i || in_array($group['gid'], $marketsArray)) {
                    $i ++;
                    continue;
                }

                VkMarket::create([
                    'group_id'     => $group['gid'],
                    'name'         => $group['name'],
                    'screen_name'  => $group['screen_name'],
                    'is_closed'    => $group['is_closed'],
                    'photo'        => $group['photo'],
                    'photo_medium' => $group['photo_medium'],
                    'photo_big'    => $group['photo_big'],
                ]);
            }
        }

    }
}
