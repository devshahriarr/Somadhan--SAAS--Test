<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Stock;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Log;

class StockObserver
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    /**
     * Handle the Stock "created" event.
     */
    public function created(Stock $stock)
    {
        $this->updateProductInElasticsearch($stock);
    }

    /**
     * Handle the Stock "updated" event.
     */
    public function updated(Stock $stock)
    {
        $this->updateProductInElasticsearch($stock);
    }

    /**
     * Handle the Stock "deleted" event.
     */
    public function deleted(Stock $stock)
    {
        $this->updateProductInElasticsearch($stock);
    }

    /**
     * Update the product document in Elasticsearch with the latest stock quantity.
     */
    protected function updateProductInElasticsearch(Stock $stock)
    {
        try {
            // Get the variation and its product
            $variation = $stock->variation;
            if (! $variation) {
                Log::warning("No variation found for stock ID {$stock->id}");

                return;
            }

            $product = $variation->product()->with([
                'variations.stocks',
                'variations.variationSize',
                'variations.colorName',
            ])->first();

            if (! $product) {
                Log::warning("No product found for variation ID {$variation->id}");

                return;
            }

            // Prepare the product data for Elasticsearch
            $data = [
                'id' => $product->id,
                'name' => $product->name ?? '',
                'description' => $product->description ?? '',
                'category_id' => $product->category_id ?? null,
                'unit' => $product->unit ?? '',
                'status' => $product->status ?? '',
                'variations' => $product->variations->map(function ($variation) {
                    return [
                        'variation_id' => $variation->id ?? '',
                        'variation_name' => $variation->variation_name ?? '',
                        'barcode' => $variation->barcode ?? '',
                        'cost_price' => $variation->cost_price ?? 0.0,
                        'b2b_price' => $variation->b2b_price ?? 0.0,
                        'b2c_price' => $variation->b2c_price ?? 0.0,
                        'size' => $variation->variationSize ? $variation->variationSize->size : null,
                        'color' => $variation->colorName ? $variation->colorName->name : null,
                        'stock_quantity' => $variation->stocks->sum('stock_quantity') ?? 0,
                        'status' => $variation->status ?? '',
                    ];
                })->toArray(),
            ];

            // Update the product in Elasticsearch
            $this->elasticsearchService->index('products', $product->id, $data);
            Log::info("Updated Elasticsearch index for product ID {$product->id} due to stock change (stock ID {$stock->id})");
        } catch (\Exception $e) {
            Log::error("Failed to update Elasticsearch index for stock ID {$stock->id}: ".$e->getMessage()."\nStack trace: ".$e->getTraceAsString());
        }
    }
}
