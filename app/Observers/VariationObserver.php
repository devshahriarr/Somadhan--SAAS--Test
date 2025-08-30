<?php

// namespace App\Observers;
// use App\Models\Variation;
// use App\Services\ElasticsearchService;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\DB;

// class VariationObserver
// {
//     protected $elasticsearchService;

//     public function __construct(ElasticsearchService $elasticsearchService)
//     {
//         $this->elasticsearchService = $elasticsearchService;
//     }

//     public function created(Variation $variation)
//     {
//         Log::info("VariationObserver::created triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
//         Log::info("Elasticsearch config value: " . config('elasticsearch.enabled'));
//         if (config('elasticsearch.enabled')) {
//             Log::info("Elasticsearch enabled for variation ID {$variation->id}");
//             // Delay indexing until after the transaction commits
//             DB::afterCommit(function () use ($variation) {
//                 $this->indexProduct($variation);
//             });
//         } else {
//             Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
//         }
//     }

//     public function updated(Variation $variation)
//     {
//         Log::info("VariationObserver::updated triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
//         Log::info("Elasticsearch config value: " . config('elasticsearch.enabled'));
//         if (config('elasticsearch.enabled')) {
//             Log::info("Elasticsearch enabled for variation ID {$variation->id}");
//             $this->indexProduct($variation);
//         } else {
//             Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
//         }
//     }

//     public function deleted(Variation $variation)
//     {
//         Log::info("VariationObserver::deleted triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
//         Log::info("Elasticsearch config value: " . config('elasticsearch.enabled'));
//         if (config('elasticsearch.enabled')) {
//             Log::info("Elasticsearch enabled for variation ID {$variation->id}");
//             $this->indexProduct($variation);
//         } else {
//             Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
//         }
//     }

//     private function indexProduct(Variation $variation)
//     {
//         try {
//             $product = $variation->product()->with(['variations.stocks', 'variations.variationSize', 'variations.colorName'])->first();
//             if (!$product) {
//                 Log::error("No product found for variation ID {$variation->id}");
//                 return;
//             }
//             // Refresh product to ensure latest data
//             $product->refresh();
//             Log::info("Loaded relationships for product ID {$product->id}: " . json_encode($product->toArray(), JSON_PRETTY_PRINT));
//             $data = [
//                 'id' => $product->id,
//                 'name' => $product->name,
//                 'description' => $product->description ?? '',
//                 'variations' => $product->variations->map(function ($variation) {
//                     // Refresh variation to ensure latest stock data
//                     $variation->refresh();
//                     return [
//                         'id' => $variation->id,
//                         'variation_name' => $variation->variation_name ?? '',
//                         'barcode' => $variation->barcode ?? '',
//                         'b2c_price' => $variation->b2c_price ?? 0,
//                         'stock_quantity' => $variation->stocks->sum('stock_quantity') ?? 0,
//                         'size' => $variation->variationSize ? $variation->variationSize->size : null,
//                         'color' => $variation->colorName ? $variation->colorName->name : $variation->color,
//                     ];
//                 })->toArray(),
//             ];
//             Log::info("Indexing product ID {$product->id}: " . json_encode($data, JSON_PRETTY_PRINT));
//             $this->elasticsearchService->index('products', $product->id, $data);
//             Log::info("Indexed product ID {$product->id}: {$product->name}");
//         } catch (\Exception $e) {
//             Log::error("Failed to index product for variation ID {$variation->id}: {$e->getMessage()}");
//         }
//     }
// }

namespace App\Observers;

use App\Models\Variation;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VariationObserver
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function created(Variation $variation)
    {
        Log::info("VariationObserver::created triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled')) {
            Log::info("Elasticsearch enabled for variation ID {$variation->id}");
            DB::afterCommit(function () use ($variation) {
                sleep(1);
                $variation->refresh();
                $variation->load('stocks');
                Log::info("Stocks for variation ID {$variation->id}: ".json_encode($variation->stocks->toArray(), JSON_PRETTY_PRINT));
                $this->indexProduct($variation);
            });
        } else {
            Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
        }
    }

    public function updated(Variation $variation)
    {
        Log::info("VariationObserver::updated triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled')) {
            Log::info("Elasticsearch enabled for variation ID {$variation->id}");
            $variation->refresh();
            $variation->load('stocks');
            Log::info("Stocks for variation ID {$variation->id}: ".json_encode($variation->stocks->toArray(), JSON_PRETTY_PRINT));
            $this->indexProduct($variation);
        } else {
            Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
        }
    }

    public function deleted(Variation $variation)
    {
        Log::info("VariationObserver::deleted triggered for variation ID {$variation->id}, product ID {$variation->product_id}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled')) {
            Log::info("Elasticsearch enabled for variation ID {$variation->id}");
            $this->indexProduct($variation);
        } else {
            Log::warning("Elasticsearch disabled for variation ID {$variation->id}");
        }
    }

    private function indexProduct(Variation $variation)
    {
        try {
            $product = $variation->product()->with(['variations.stocks', 'variations.variationSize', 'variations.colorName'])->first();
            if (! $product) {
                Log::error("No product found for variation ID {$variation->id}");

                return;
            }
            $product->refresh();
            Log::info("Loaded relationships for product ID {$product->id}: ".json_encode($product->toArray(), JSON_PRETTY_PRINT));

            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description ?? '',
                'category_id' => $product->category_id ?? '',
                'variations' => $product->variations->map(function ($var) {
                    $var->refresh();
                    $var->load('stocks');
                    Log::info("Stocks for variation ID {$var->id}: ".json_encode($var->stocks->toArray(), JSON_PRETTY_PRINT));

                    return [
                        'variation_id' => $var->id,
                        'variation_name' => $var->variation_name ?? '',
                        'barcode' => $var->barcode ?? '',
                        'cost_price' => $var->cost_price ?? 0,
                        'b2b_price' => $var->b2b_price ?? 0,
                        'b2c_price' => $var->b2c_price ?? 0,
                        'stock_quantity' => $var->stocks->sum('stock_quantity') ?? 0,
                        'size' => $var->variationSize ? $var->variationSize->size : null,
                        'color' => $var->colorName ? $var->colorName->name : $var->color,
                    ];
                })->toArray(),
            ];
            Log::info("Indexing product ID {$product->id}: ".json_encode($data, JSON_PRETTY_PRINT));
            $this->elasticsearchService->index('products', $product->id, $data);
            Log::info("Indexed product ID {$product->id}: {$product->name}");
        } catch (\Exception $e) {
            Log::error("Failed to index product for variation ID {$variation->id}: {$e->getMessage()}");
        }
    }
}
