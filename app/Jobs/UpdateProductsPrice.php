<?php

namespace App\Jobs;

use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateProductsPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $offset = 0;
        $limit = 1000;
        $token = env('WB_TOKEN_PRICES');
        $hasMoreItems = true;

        while ($hasMoreItems) {
            $response = $client->get('https://discounts-prices-api.wb.ru/api/v2/list/goods/filter', [
                'query' => [
                    'limit' => $limit,
                    'offset' => $offset,
                ],
                'headers' => [
                    'Authorization' => $token,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $items = $data['data']['listGoods'];

            if (empty($items)) {
                $hasMoreItems = false;
                break;
            }

            foreach ($items as $item) {
                $product = Product::where('wb_id', $item['nmID'])->first();

                if ($product && $item['sizes'][0]['discountedPrice'] > 0) {
                    $discountedPrice = $item['sizes'][0]['discountedPrice'];
                    $costPrice = $product->cost_price;
                    $markup = $discountedPrice / $costPrice;

                    if ($markup < $product->min_markup) {
                        $product->price_status = 1;
                    } elseif ($markup >= $product->min_markup && $markup <= $product->max_markup) {
                        $product->price_status = 2;
                    } else {
                        $product->price_status = 3;
                    }

                    $product->discounted_price = $discountedPrice;
                    $product->save();
                }
            }

            $offset += $limit;
        }
    }
}
