<?php

namespace App\Console\Commands;

use App\VkMarket;
use App\VkMarketItem;
use App\VkMarketsNew;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class UpdateVkMarketNews extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vkmarket_news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Vk Market News';

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
        $markets = VkMarket::get(['group_id'])
            ->toArray();

        foreach ($markets as $market) {

            $amountPerQuery = 200;
            $offset = 0;
            $bool = true;

            while ($bool) {

                $params = [
                    'owner_id' => '-' . $market['group_id'],
                    'album_id' => '0',
                    'count'    => $amountPerQuery,
                    'offset'   => $offset,
                ];

                $httpQuery = 'https://api.vk.com/method/market.get?access_token=' . env('VK_API_MARKET_TOKEN') . '&' . http_build_query($params);

                $client = new Client();
                $res = $client->get($httpQuery);
                $response = \GuzzleHttp\json_decode($res->getBody(), true);

                if(!isset($response['response'])) {
                    $bool = false;
                    continue;
                }

                $countItems = (count($response['response']) - 1);

                for ($i = 1; $i <= $countItems; $i ++) {
                    $apiItem = $response['response'][$i];

                    $apiItemPrice = ($apiItem['price']['amount'] / 100);

                    $markerItem = VkMarketItem::where('vk_market_id', $market['group_id'])
                        ->where('vk_id', $apiItem['id'])->first();

                    if ($markerItem) {
                        // Check if price is less than in the previous time
                        if ($apiItemPrice < $markerItem->price && $apiItem['price']['currency']['name'] == $markerItem->currency) {
                            VkMarketsNew::create([
                                'vk_market_item_id' => $markerItem->ID,
                                'price'             => $apiItemPrice,
                                'previous_price'    => $markerItem->price,
                                'currency'          => $apiItem['price']['currency']['name'],
                            ]);
                        }

                        // Update all info
                        $markerItem->title = $apiItem['title'];
                        $markerItem->description = $apiItem['description'];
                        $markerItem->price = $apiItemPrice;
                        $markerItem->currency = $apiItem['price']['currency']['name'];
                        $markerItem->thumb_photo = $apiItem['thumb_photo'];
                        $markerItem->save();
                    } else {
                        VkMarketItem::create([
                            'vk_market_id' => $market['group_id'],
                            'vk_id'        => $apiItem['id'],
                            'title'        => $apiItem['title'],
                            'description'  => $apiItem['description'],
                            'price'        => $apiItemPrice,
                            'currency'     => $apiItem['price']['currency']['name'],
                            'thumb_photo'  => $apiItem['thumb_photo'],
                        ]);
                    }
                }

                // Check it quit;
                if ($countItems < $amountPerQuery) {
                    $bool = false;
                } else {
                    $offset += $amountPerQuery;
                }
            }
        }
    }
}
