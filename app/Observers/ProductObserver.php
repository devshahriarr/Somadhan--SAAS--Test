<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    // public function created(Product $product)
    // {
    //     Log::info("ProductObserver::created triggered for product ID {$product->id}: {$product->name}");
    //     Log::info("Elasticsearch config value: " . config('elasticsearch.enabled'));
    //     if (config('elasticsearch.enabled')) {
    //         Log::info("Elasticsearch enabled for product ID {$product->id}");
    //         // Delay indexing until after the transaction commits
    //         DB::afterCommit(function () use ($product) {
    //             $this->indexProduct($product);
    //         });
    //     } else {
    //         Log::warning("Elasticsearch disabled for product ID {$product->id}");
    //     }
    // }

    public function created(Product $product)
    {
        Log::info("ProductObserver::created triggered for product ID {$product->id}: {$product->name}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled') && ! config('app.is_creating_variation', false)) {
            Log::info("Elasticsearch enabled for product ID {$product->id}");
            DB::afterCommit(function () use ($product) {
                $product->refresh();
                $product->load(['variations.stocks', 'variations.variationSize', 'variations.colorName']);
                Log::info("Stocks for product ID {$product->id}: ".json_encode($product->variations->pluck('stocks')->flatten()->toArray(), JSON_PRETTY_PRINT));
                $this->indexProduct($product);
            });
        } else {
            Log::info("Skipping ProductObserver indexing for product ID {$product->id} due to variation creation or Elasticsearch disabled");
        }
    }

    public function updated(Product $product)
    {
        Log::info("ProductObserver::updated triggered for product ID {$product->id}: {$product->name}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled')) {
            Log::info("Elasticsearch enabled for product ID {$product->id}");
            $this->indexProduct($product);
        } else {
            Log::warning("Elasticsearch disabled for product ID {$product->id}");
        }
    }

    public function deleted(Product $product)
    {
        Log::info("ProductObserver::deleted triggered for product ID {$product->id}: {$product->name}");
        Log::info('Elasticsearch config value: '.config('elasticsearch.enabled'));
        if (config('elasticsearch.enabled')) {
            Log::info("Elasticsearch enabled for product ID {$product->id}");
            try {
                $this->elasticsearchService->delete('products', $product->id);
                Log::info("Deleted product ID {$product->id} from Elasticsearch");
            } catch (\Exception $e) {
                Log::error("Failed to delete product ID {$product->id} from Elasticsearch: {$e->getMessage()}");
            }
        } else {
            Log::warning("Elasticsearch disabled for product ID {$product->id}");
        }
    }

    private function indexProduct(Product $product)
    {
        try {
            $product->refresh();
            $product->load(['variations.stocks', 'variations.variationSize', 'variations.colorName']);
            Log::info("Loaded relationships for product ID {$product->id}: ".json_encode($product->toArray(), JSON_PRETTY_PRINT));
            $data = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description ?? '',
                'category_id' => $product->category_id ?? '',
                'variations' => $product->variations->map(function ($variation) {
                    return [
                        'variation_id' => $variation->id,
                        'variation_name' => $variation->variation_name ?? '',
                        'barcode' => $variation->barcode ?? '',
                        'cost_price' => $variation->cost_price ?? 0,
                        'b2b_price' => $variation->b2b_price ?? 0,
                        'b2c_price' => $variation->b2c_price ?? 0,
                        'stock_quantity' => $variation->stocks->sum('stock_quantity') ?? 0,
                        'size' => $variation->variationSize ? $variation->variationSize->size : null,
                        'color' => $variation->colorName ? $variation->colorName->name : $variation->color,
                    ];
                })->toArray(),
            ];
            Log::info("Indexing product ID {$product->id}: ".json_encode($data, JSON_PRETTY_PRINT));
            $this->elasticsearchService->index('products', $product->id, $data);
            Log::info("Indexed product ID {$product->id}: {$product->name}");
        } catch (\Exception $e) {
            Log::error("Failed to index product ID {$product->id}: {$e->getMessage()}");
        }
    }
}
