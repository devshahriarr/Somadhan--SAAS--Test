<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class IndexProducts extends Command
{
    // protected $signature = 'products:index';
    // protected $description = 'Index all products in Elasticsearch';
    // protected $elasticsearchService;
    // public function __construct(ElasticsearchService $elasticsearchService)
    // {
    //     parent::__construct();
    //     $this->elasticsearchService = $elasticsearchService;
    // }
    // public function handle()
    // {
    //     try {
    //         Log::info('Starting product indexing process.');
    //         $this->elasticsearchService->createIndex('products');
    //         Product::with(['variations.stocks'])->chunk(50, function ($products) {
    //             foreach ($products as $product) {
    //                 $data = [
    //                     'name' => $product->name,
    //                     'description' => $product->description,
    //                     'variations' => $product->variations->map(function ($variation) {
    //                         return [
    //                             'variation_name' => $variation->variation_name,
    //                             'barcode' => $variation->barcode,
    //                             'b2c_price' => $variation->b2c_price,
    //                             'size' => $variation->size ? optional($variation->psize)->name : null,
    //                             'color' => $variation->color ? optional($variation->colorRelation)->name : null,
    //                             'stock_quantity' => $variation->stocks->sum('stock_quantity'),
    //                         ];
    //                     })->toArray(),
    //                 ];
    //                 $this->elasticsearchService->index('products', $product->id, $data);
    //                 $this->info("Indexed product: {$product->name}");
    //             }
    //         });
    //         $this->info('All products indexed successfully.');
    //     } catch (\Exception $e) {
    //         Log::error('Product indexing failed: ' . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());
    //         $this->error('Indexing failed: ' . $e->getMessage());
    //     }
    // }

    protected $signature = 'products:index';

    protected $description = 'Index all products in Elasticsearch';

    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        parent::__construct();
        $this->elasticsearchService = $elasticsearchService;
    }

    public function handle()
    {
        try {
            Log::info('Starting product indexing process.');
            $this->elasticsearchService->createIndex('products');
            Product::with(['variations.stocks', 'variations.variationSize', 'variations.colorName'])->chunk(50, function ($products) {
                foreach ($products as $product) {
                    $data = [
                        'id' => $product->id,
                        'name' => $product->name ?? '',
                        'description' => $product->description ?? '',
                        'category_id' => $product->category_id ?? '',
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
                                'size_id' => $variation->size ?? null,
                                'color_id' => $variation->color ?? null,
                                'size' => $variation->variationSize ? $variation->variationSize->size : null, // Add size name
                                'color' => $variation->colorName ? $variation->colorName->name : null, // Add color name
                                'stock_quantity' => $variation->stocks->sum('stock_quantity') ?? 0,
                                'status' => $variation->status ?? '',
                            ];
                        })->toArray(),
                    ];
                    $this->elasticsearchService->index('products', $product->id, $data);
                    $this->info("Indexed product: {$product->name}");
                }
            });
            $this->info('All products indexed successfully.');
        } catch (\Exception $e) {
            Log::error('Product indexing failed: '.$e->getMessage()."\nStack trace: ".$e->getTraceAsString());
            $this->error('Indexing failed: '.$e->getMessage());
        }
    }
}
