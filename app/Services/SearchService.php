<?php

namespace App\Services;

use App\Models\PosSetting;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class SearchService
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function search($query)
    {
        Log::info("Search initiated with query: {$query}");
        $setting = PosSetting::select('elastic_search')->where('id', 1)->first();
        $elasticsearchEnabled = $setting->elastic_search ?? '0';
        Log::info("Elasticsearch enabled: {$elasticsearchEnabled}");

        if ($elasticsearchEnabled == '1') {
            try {
                Log::info("Performing Elasticsearch search for query: {$query}");
                $results = $this->elasticsearchService->search('products', $query);
                Log::info('Elasticsearch search results: '.json_encode([
                    'total' => $results['hits']['total']['value'] ?? 0,
                    'hits' => array_map(function ($hit) {
                        return [
                            'id' => $hit['_id'],
                            'source' => $hit['_source'],
                            'variations' => $hit['_source']['variations'],
                        ];
                    }, $results['hits']['hits'] ?? []),
                ], JSON_PRETTY_PRINT));

                return collect($results['hits']['hits'])->map(function ($hit) use ($query) {
                    $product = (object) $hit['_source'];
                    Log::info("Processing product: {$product->name} with query: {$query}");
                    $product->id = $hit['_id'];
                    $product->variations = collect($product->variations)->map(function ($variation) {
                        return (object) $variation;
                    })->values();

                    return $product;
                })->filter(function ($product) {
                    return $product->variations->isNotEmpty();
                });
            } catch (\Exception $e) {
                Log::error('Elasticsearch error: '.$e->getMessage()."\nStack trace: ".$e->getTraceAsString());

                return collect([]); // Return empty results instead of falling back
            }
        }

        Log::info("Falling back to MySQL search for query: {$query}");

        return Product::whereHas('variations', function ($q) use ($query) {
            $q->where('variation_name', 'like', "%{$query}%")
                ->orWhere('barcode', 'like', "%{$query}%");
        })->with(['variations' => function ($q) use ($query) {
            $q->where('variation_name', 'like', "%{$query}%")
                ->orWhere('barcode', 'like', "%{$query}%")
                ->with(['variationSize', 'colorName', 'stocks']);
        }])->get()->map(function ($product) {
            $product->category_id = $product->category_id ?? null;
            $product->unit = $product->unit ?? '';
            $product->status = $product->status ?? '';
            $product->variations = $product->variations->map(function ($variation) {
                $variation->variation_id = $variation->id ?? null;
                $variation->product_id = $variation->product_id ?? null;
                $variation->cost_price = $variation->cost_price ?? 0.0;
                $variation->b2b_price = $variation->b2b_price ?? 0.0;
                $variation->b2c_price = $variation->b2c_price ?? 0.0;
                $variation->size_id = $variation->size ?? null;
                $variation->size = $variation->variationSize ? $variation->variationSize->size : null;
                $variation->color_id = $variation->color ?? null;
                $variation->color = $variation->colorName ? $variation->colorName->name : null;
                $variation->stock_quantity = $variation->stocks->sum('stock_quantity') ?? 0;
                $variation->status = $variation->status ?? '';

                return $variation;
            });

            return $product;
        });
    }
}
