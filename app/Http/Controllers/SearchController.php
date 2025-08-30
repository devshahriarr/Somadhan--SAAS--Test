<?php

namespace App\Http\Controllers;

use App\Models\PosSetting;
use App\Models\Variation;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
    //     $results = $this->searchService->search($query);
    //     return view('pos.search.results', compact('results', 'query'));
    // }
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            Log::info('No query provided for search, returning empty results.');

            return view('pos.search.results', ['results' => collect([]), 'query' => '']);
        }

        try {
            $results = $this->searchService->search($query);

            // dd($results);
            return view('pos.search.results', compact('results', 'query'));
        } catch (\Exception $e) {
            Log::error('Search failed: '.$e->getMessage());

            return view('pos.search.results', ['results' => collect([]), 'query' => $query])
                ->withErrors(['error' => 'Search failed: '.$e->getMessage()]);
        }
    }

    // public function productsSearch(Request $request)
    // {
    //     dd("hello");
    //     $query = $request->input('query');
    //     $results = $this->searchService->search($query);
    //     return view('pos.search.results', compact('results', 'query'));
    // }
    public function globalSearch2(Request $request, $search_value)
    {
        $query = $search_value;
        $formattedProducts = [];
        $posSetting = PosSetting::first();
        $results = $this->searchService->search($query);
        foreach ($results as $product) {
            // Skip if product doesn't have variations
            if (! isset($product->variations) || ! is_iterable($product->variations)) {
                continue;
            }
            // dd($product);
            foreach ($product->variations as $variation) {
                // dd($variation);
                $totalVariationStock = 0;
                // $totalVariationStock = $variation->stocks ? $variation->stocks->sum('stock_quantity') : 0;
                if (isset($variation->stocks) && is_iterable($variation->stocks)) {
                    $totalVariationStock = array_reduce($variation->stocks, function ($carry, $stock) {
                        return $carry + ($stock->stock_quantity ?? 0);
                    }, 0);
                } elseif (isset($variation->stock_quantity)) {
                    $totalVariationStock = $variation->stock_quantity;
                }
                $formattedProducts[] = [
                    'variant_id' => $variation->variation_id ?? null,
                    'name' => $product->name ?? 'N/A',
                    'totalVariationStock' => $totalVariationStock,
                    'color' => $variation->color ?? null,
                    'cost_price' => $variation->cost_price ?? 'N/A',
                    'b2b_price' => $variation->b2b_price ?? 'N/A',
                    'b2c_price' => $variation->b2c_price ?? 'N/A',
                    'size' => $variation->size ?? null,
                    'variation_size' => $variation->size ?? 'N/A',
                    'variation_color' => $variation->color ?? 'N/A',
                    'price' => $posSetting && $posSetting->sale_price_type === 'b2b_price' ? $variation->b2b_price : $variation->b2c_price,
                    'stock_quantity' => $product->stock_quantity_sum_stock_quantity ?? 0,
                    'relevance_score' => $variation->relevance_score ?? 0,
                ];
            }
        }

        return response()->json([
            'products' => $formattedProducts,
            'query' => $query,
            'status' => 200,
        ]);
    }

    public function rateKitPriceGet(Request $request)
    {
        $variant_id = $request->variant_id;
        $customer_id = $request->customer_id;
        // dd($request->all());/
        $variation = Variation::with([
            'product' => function ($query) {
                $query->select('id', 'name')->with(['category', 'subcategory', 'brand']);
            },
            'stocks',
            'variationSize',
            'colorName',
            'saleItem' => function ($query) use ($customer_id) {
                $query->whereHas('saleId', function ($q) use ($customer_id) {
                    $q->when($customer_id, function ($subQ) use ($customer_id) {
                        $subQ->where('customer_id', $customer_id);
                    });
                })
                    ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                    ->latest('sale_items.created_at')
                    ->take(5)
                    ->select([
                        'sale_items.id',
                        'sale_items.sale_id',
                        'sale_items.product_id',
                        'sale_items.variant_id',
                        'sale_items.rate',
                        'sale_items.qty',
                        'sales.sale_date',
                    ]);
            },
        ])
            ->where('id', $variant_id)
            ->first();

        if (! $variation) {
            return response()->json([
                'success' => false,
                'message' => 'Variation not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $variation,
        ]);
    }

    // full text index search with natural language mode
    // public function globalSearch(Request $request, $search_value)
    // {

    //     // Sanitize input
    //     $search_value = trim(strip_tags($search_value));
    //     if (empty($search_value)) {
    //         return response()->json(['error' => 'Search value cannot be empty'], 400);
    //     }

    //     // Check if user is authenticated
    //     if (!Auth::check()) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Combine search terms for full-text search
    //     $search_term = implode(' ', array_filter(explode(' ', $search_value)));
    //     // dd($search_term);
    //     // Log search input
    //     \Log::info('Global search started', [
    //         'search_value' => $search_value,
    //         'search_term' => $search_term
    //     ]);

    //     // Fetch POS settings
    //     $customer_id = $request->query('customerId');
    //     $posSetting = PosSetting::first();

    //     // Base query for variations with eager loading
    //         $query = Variation::with([
    //             'product' => function ($query) {
    //                 $query->select('id', 'name')->with(['category', 'subcategory', 'brand']);
    //             },
    //             'stocks',
    //             'variationSize',
    //             'colorName',
    //                 'saleItem' => function ($query) use ($customer_id) {
    //                     $query->whereHas('saleId', function ($q) use ($customer_id) {
    //                         $q->when($customer_id, function ($subQ) use ($customer_id) {
    //                             $subQ->where('customer_id', $customer_id);
    //                         });
    //                     })
    //                         ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
    //                         ->latest('sale_items.created_at')
    //                         ->take(5)
    //                         ->select([
    //                             'sale_items.id',
    //                             'sale_items.sale_id',
    //                             'sale_items.product_id',
    //                             'sale_items.variant_id',
    //                             'sale_items.rate',
    //                             'sale_items.qty',
    //                             'sales.sale_date'
    //                         ]);
    //                 }
    //         ])
    //         ->selectRaw('variations.id, variations.product_id, variations.b2b_price, variations.b2c_price, variations.color, variations.size, variations.cost_price, 0 as relevance_score');

    //     // Apply full-text search with joins
    //     $query->leftJoin('products', 'variations.product_id', '=', 'products.id')
    //         ->leftJoin('psizes', 'variations.size', '=', 'psizes.id')
    //         ->leftJoin('colors', 'variations.color', '=', 'colors.id')
    //         ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
    //         ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
    //         ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
    //         ->where('variations.productStatus', 'active')
    //         ->where(function ($q) use ($search_term) {
    //             $q->orWhereRaw('MATCH(products.name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term])
    //                 ->orWhereRaw('MATCH(psizes.size) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term])
    //                 ->orWhereRaw('MATCH(colors.name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term])
    //                 ->orWhereRaw('MATCH(categories.name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term])
    //                 ->orWhereRaw('MATCH(sub_categories.name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term])
    //                 ->orWhereRaw('MATCH(brands.name) AGAINST(? IN NATURAL LANGUAGE MODE)', [$search_term]);
    //         });

    //     // Calculate weighted relevance score
    //     $query->selectRaw(
    //         '
    //         (CASE WHEN MATCH(products.name) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(products.name) AGAINST(? IN NATURAL LANGUAGE MODE) * 5 ELSE 0 END +
    //         CASE WHEN MATCH(psizes.size) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(psizes.size) AGAINST(? IN NATURAL LANGUAGE MODE) * 2 ELSE 0 END +
    //         CASE WHEN MATCH(colors.name) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(colors.name) AGAINST(? IN NATURAL LANGUAGE MODE) * 2 ELSE 0 END +
    //         CASE WHEN MATCH(categories.name) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(categories.name) AGAINST(? IN NATURAL LANGUAGE MODE) * 1 ELSE 0 END +
    //         CASE WHEN MATCH(sub_categories.name) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(sub_categories.name) AGAINST(? IN NATURAL LANGUAGE MODE) * 1 ELSE 0 END +
    //         CASE WHEN MATCH(brands.name) AGAINST(? IN NATURAL LANGUAGE MODE) THEN MATCH(brands.name) AGAINST(? IN NATURAL LANGUAGE MODE) * 1 ELSE 0 END) as relevance_score',
    //         array_fill(0, 12, $search_term)
    //     );

    //     // Order by relevance score
    //     $query->orderByRaw('relevance_score DESC')
    //         ->orderBy('variations.product_id', 'ASC');

    //     // Log query for debugging
    //     \Log::debug('Global search query', [
    //         'sql' => $query->toSql(),
    //         'bindings' => $query->getBindings()
    //     ]);

    //     // Execute query with pagination and caching
    //     try {
    //         $variations = Cache::remember('search_' . md5($search_value . $customer_id), 300, function () use ($query) {
    //             return $query->paginate(50);
    //         });
    //     } catch (\Exception $e) {
    //         \Log::error('Global search error: ' . $e->getMessage(), [
    //             'sql' => $query->toSql(),
    //             'bindings' => $query->getBindings(),
    //             'search_value' => $search_value,
    //             'customer_id' => $customer_id
    //         ]);
    //         return response()->json(['error' => 'Search failed', 'message' => $e->getMessage()], 500);
    //     }

    //     // Format products
    //     $formattedProducts = [];
    //     foreach ($variations as $variation) {
    //         $totalVariationStock = $variation->stocks ? $variation->stocks->sum('stock_quantity') : 0;
    //         $salePartyKitPrice = $variation->saleItem ?: [];
    //         $formattedProducts[] = [
    //             'variant_id' => $variation->id,
    //             'name' => $variation->product ? $variation->product->name : 'N/A',
    //             'totalVariationStock' => $totalVariationStock,
    //             'color' => $variation->color,
    //             'salePartyKitPrice' => $salePartyKitPrice,
    //             'cost_price' => $variation->cost_price,
    //             'b2b_price' => $variation->b2b_price,
    //             'b2c_price' => $variation->b2c_price,
    //             'size' => $variation->size,
    //             'variation_size' => $variation->variationSize ? $variation->variationSize->size : 'N/A',
    //             'variation_color' => $variation->colorName ? $variation->colorName->name : $variation->color,
    //             'price' => $posSetting && $posSetting->sale_price_type === 'b2b_price' ? $variation->b2b_price : $variation->b2c_price,
    //             'product_id' => $variation->product_id,
    //             'variation_id' => $variation->id,
    //             'stock_quantity' => $variation->product ? ($variation->product->stock_quantity_sum_stock_quantity ?? 0) : 0,
    //             'relevance_score' => $variation->relevance_score ?? 0
    //         ];
    //     }

    //     // Add debug information
    //     $debug = [
    //         'search_value' => $search_value,
    //         'search_terms' => array_filter(explode(' ', $search_value)),
    //         'total_products' => $variations->total(),
    //         'returned_products' => $variations->count(),
    //         'sample_results' => $variations->take(5)->map(function ($variation) {
    //             return [
    //                 'variant_id' => $variation->id,
    //                 'name' => $variation->product ? $variation->product->name : 'N/A',
    //                 'relevance_score' => $variation->relevance_score ?? 0
    //             ];
    //         })->toArray()
    //     ];

    //     return response()->json([
    //         'products' => $formattedProducts,
    //         'pos_setting' => $posSetting,
    //         'debug' => $debug,
    //         'status' => 200
    //     ]);
    // }

    // public function globalSearch(Request $request, $search_value)
    // {
    //     // Sanitize input
    //     $search_value = trim(strip_tags($search_value));
    //     if (empty($search_value)) {
    //         return response()->json(['error' => 'Search value cannot be empty'], 400);
    //     }

    //     // Check if user is authenticated
    //     if (!Auth::check()) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Combine search terms for full-text search
    //     $search_term = implode(' ', array_filter(explode(' ', $search_value)));
    //     // Add wildcards for BOOLEAN MODE and LIKE
    //     $boolean_term = '+' . str_replace(' ', ' +', $search_term) . '*';
    //     $like_term = '%' . $search_term . '%';

    //     // Log search input
    //     Log::info('Global search started', [
    //         'search_value' => $search_value,
    //         'search_term' => $search_term,
    //         'boolean_term' => $boolean_term,
    //         'like_term' => $like_term
    //     ]);

    //     // Fetch POS settings
    //     $customer_id = $request->query('customerId');
    //     $posSetting = PosSetting::first();

    //     // Base query for variations with eager loading
    //     $query = Variation::with([
    //         'product' => function ($query) {
    //             $query->select('id', 'name')->with(['category', 'subcategory', 'brand']);
    //         },
    //         'stocks',
    //         'variationSize',
    //         'colorName',
    //         'saleItem' => function ($query) use ($customer_id) {
    //             $query->whereHas('saleId', function ($q) use ($customer_id) {
    //                 $q->when($customer_id, function ($subQ) use ($customer_id) {
    //                     $subQ->where('customer_id', $customer_id);
    //                 });
    //             })
    //             ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
    //             ->latest('sale_items.created_at')
    //             ->take(5)
    //             ->select([
    //                 'sale_items.id',
    //                 'sale_items.sale_id',
    //                 'sale_items.product_id',
    //                 'sale_items.variant_id',
    //                 'sale_items.rate',
    //                 'sale_items.qty',
    //                 'sales.sale_date'
    //             ]);
    //         }
    //     ])
    //     ->selectRaw('variations.id, variations.product_id, variations.b2b_price, variations.b2c_price, variations.color, variations.size, variations.cost_price, 0 as relevance_score');

    //     // Apply full-text search with joins
    //     $query->leftJoin('products', 'variations.product_id', '=', 'products.id')
    //         ->leftJoin('psizes', 'variations.size', '=', 'psizes.id')
    //         ->leftJoin('colors', 'variations.color', '=', 'colors.id')
    //         ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
    //         ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
    //         ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
    //         ->where('variations.productStatus', 'active')
    //         ->where(function ($q) use ($boolean_term, $like_term) {
    //             // Full-text search in BOOLEAN MODE
    //             $q->orWhereRaw('MATCH(products.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               ->orWhereRaw('MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               ->orWhereRaw('MATCH(colors.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               ->orWhereRaw('MATCH(categories.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               ->orWhereRaw('MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               ->orWhereRaw('MATCH(brands.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
    //               // Fallback LIKE queries for partial matches
    //               ->orWhere('products.name', 'LIKE', $like_term)
    //               ->orWhere('psizes.size', 'LIKE', $like_term)
    //               ->orWhere('colors.name', 'LIKE', $like_term)
    //               ->orWhere('categories.name', 'LIKE', $like_term)
    //               ->orWhere('sub_categories.name', 'LIKE', $like_term)
    //               ->orWhere('brands.name', 'LIKE', $like_term);
    //         });

    //     // Calculate weighted relevance score
    //     $query->selectRaw(
    //         '
    //         (CASE WHEN MATCH(products.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(products.name) AGAINST(? IN BOOLEAN MODE) * 5 ELSE 0 END +
    //          CASE WHEN MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE) THEN MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE) * 2 ELSE 0 END +
    //          CASE WHEN MATCH(colors.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(colors.name) AGAINST(? IN BOOLEAN MODE) * 2 ELSE 0 END +
    //          CASE WHEN MATCH(categories.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(categories.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
    //          CASE WHEN MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
    //          CASE WHEN MATCH(brands.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(brands.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
    //          CASE WHEN products.name LIKE ? THEN 3 ELSE 0 END +
    //          CASE WHEN psizes.size LIKE ? THEN 1 ELSE 0 END +
    //          CASE WHEN colors.name LIKE ? THEN 1 ELSE 0 END +
    //          CASE WHEN categories.name LIKE ? THEN 0.5 ELSE 0 END +
    //          CASE WHEN sub_categories.name LIKE ? THEN 0.5 ELSE 0 END +
    //          CASE WHEN brands.name LIKE ? THEN 0.5 ELSE 0 END) as relevance_score',
    //         array_fill(0, 12, $boolean_term) + array_fill(0, 6, $like_term)
    //     );

    //     // Order by relevance score
    //     $query->orderByRaw('relevance_score DESC')
    //         ->orderBy('variations.product_id', 'ASC');

    //     // Log query for debugging
    //     Log::debug('Global search query', [
    //         'sql' => $query->toSql(),
    //         'bindings' => $query->getBindings()
    //     ]);

    //     // Execute query with pagination and caching
    //     try {
    //         $variations = Cache::remember('search_' . md5($search_value . $customer_id), 300, function () use ($query) {
    //             return $query->paginate(50);
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Global search error: ' . $e->getMessage(), [
    //             'sql' => $query->toSql(),
    //             'bindings' => $query->getBindings(),
    //             'search_value' => $search_value,
    //             'customer_id' => $customer_id
    //         ]);
    //         return response()->json(['error' => 'Search failed', 'message' => $e->getMessage()], 500);
    //     }

    //     // Format products
    //     $formattedProducts = [];
    //     foreach ($variations as $variation) {
    //         $totalVariationStock = $variation->stocks ? $variation->stocks->sum('stock_quantity') : 0;
    //         $salePartyKitPrice = $variation->saleItem ?: [];
    //         $formattedProducts[] = [
    //             'variant_id' => $variation->id,
    //             'name' => $variation->product ? $variation->product->name : 'N/A',
    //             'totalVariationStock' => $totalVariationStock,
    //             'color' => $variation->color,
    //             'salePartyKitPrice' => $salePartyKitPrice,
    //             'cost_price' => $variation->cost_price,
    //             'b2b_price' => $variation->b2b_price,
    //             'b2c_price' => $variation->b2c_price,
    //             'size' => $variation->size,
    //             'variation_size' => $variation->variationSize ? $variation->variationSize->size : 'N/A',
    //             'variation_color' => $variation->colorName ? $variation->colorName->name : $variation->color,
    //             'price' => $posSetting && $posSetting->sale_price_type === 'b2b_price' ? $variation->b2b_price : $variation->b2c_price,
    //             'product_id' => $variation->product_id,
    //             'variation_id' => $variation->id,
    //             'stock_quantity' => $variation->product ? ($variation->product->stock_quantity_sum_stock_quantity ?? 0) : 0,
    //             'relevance_score' => $variation->relevance_score ?? 0
    //         ];
    //     }

    //     // Add debug information
    //     $debug = [
    //         'search_value' => $search_value,
    //         'search_terms' => array_filter(explode(' ', $search_value)),
    //         'total_products' => $variations->total(),
    //         'returned_products' => $variations->count(),
    //         'sample_results' => $variations->take(5)->map(function ($variation) {
    //             return [
    //                 'variant_id' => $variation->id,
    //                 'name' => $variation->product ? $variation->product->name : 'N/A',
    //                 'relevance_score' => $variation->relevance_score ?? 0
    //             ];
    //         })->toArray()
    //     ];

    //     return response()->json([
    //         'products' => $formattedProducts,
    //         'pos_setting' => $posSetting,
    //         'debug' => $debug,
    //         'status' => 200
    //     ]);
    // }

    public function globalSearch(Request $request, $search_value)
    {
        // Sanitize input
        $search_value = trim(strip_tags($search_value));
        if (empty($search_value)) {
            return response()->json(['error' => 'Search value cannot be empty'], 400);
        }

        // Check if user is authenticated
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Combine search terms
        $search_term = implode(' ', array_filter(explode(' ', $search_value)));
        $boolean_term = '+'.str_replace(' ', ' +', $search_term).'*';
        $like_term = '%'.$search_term.'%';

        // Log search input
        Log::info('Global search started', [
            'search_value' => $search_value,
            'search_term' => $search_term,
            'boolean_term' => $boolean_term,
            'like_term' => $like_term,
        ]);

        // Fetch POS settings
        $customer_id = $request->query('customerId');
        $posSetting = PosSetting::first();
        $isElasticsearchEnabled = $posSetting->elasticsearch_enabled ?? false;

        if ($isElasticsearchEnabled) {
            // Elasticsearch search
            try {
                $results = $this->elasticsearchService->search($search_value, 'products');
                $formattedProducts = $results->map(function ($result) use ($posSetting) {
                    return [
                        'variant_id' => $result['variations'][0]['variation_id'] ?? null,
                        'name' => $result['name'] ?? 'N/A',
                        'totalVariationStock' => collect($result['variations'])->sum('stock_quantity') ?? 0,
                        'color' => $result['variations'][0]['color_id'] ?? null,
                        'salePartyKitPrice' => [], // Adjust based on your needs
                        'cost_price' => $result['variations'][0]['cost_price'] ?? 0,
                        'b2b_price' => $result['variations'][0]['b2b_price'] ?? 0,
                        'b2c_price' => $result['variations'][0]['b2c_price'] ?? 0,
                        'size' => $result['variations'][0]['size_id'] ?? null,
                        'variation_size' => $result['variations'][0]['size_name'] ?? 'N/A',
                        'variation_color' => $result['variations'][0]['color_name'] ?? null,
                        'price' => $posSetting && $posSetting->sale_price_type === 'b2b_price' ?
                            ($result['variations'][0]['b2b_price'] ?? 0) :
                            ($result['variations'][0]['b2c_price'] ?? 0),
                        'product_id' => $result['id'] ?? null,
                        'variation_id' => $result['variations'][0]['variation_id'] ?? null,
                        'stock_quantity' => collect($result['variations'])->sum('stock_quantity') ?? 0,
                        'relevance_score' => $result['_score'] ?? 0,
                    ];
                })->toArray();

                $debug = [
                    'search_value' => $search_value,
                    'search_terms' => array_filter(explode(' ', $search_value)),
                    'total_products' => count($results),
                    'returned_products' => count($formattedProducts),
                    'sample_results' => array_slice($formattedProducts, 0, 5),
                ];

                return response()->json([
                    'products' => $formattedProducts,
                    'pos_setting' => $posSetting,
                    'debug' => $debug,
                    'status' => 200,
                ]);
            } catch (\Exception $e) {
                Log::error('Elasticsearch search failed: '.$e->getMessage());
                // Fall back to MySQL
            }
        }

        // MySQL full-text search
        $query = Variation::with([
            'product' => function ($query) {
                $query->select('id', 'name')->with(['category', 'subcategory', 'brand']);
            },
            'stocks',
            'variationSize',
            'colorName',
            'saleItem' => function ($query) use ($customer_id) {
                $query->whereHas('saleId', function ($q) use ($customer_id) {
                    $q->when($customer_id, function ($subQ) use ($customer_id) {
                        $subQ->where('customer_id', $customer_id);
                    });
                })
                    ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                    ->latest('sale_items.created_at')
                    ->take(5)
                    ->select([
                        'sale_items.id',
                        'sale_items.sale_id',
                        'sale_items.product_id',
                        'sale_items.variant_id',
                        'sale_items.rate',
                        'sale_items.qty',
                        'sales.sale_date',
                    ]);
            },
        ])
            ->selectRaw('variations.id, variations.product_id, variations.b2b_price, variations.b2c_price, variations.color, variations.size, variations.cost_price, 0 as relevance_score');

        $query->leftJoin('products', 'variations.product_id', '=', 'products.id')
            ->leftJoin('psizes', 'variations.size', '=', 'psizes.id')
            ->leftJoin('colors', 'variations.color', '=', 'colors.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'products.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('variations.productStatus', 'active')
            ->where(function ($q) use ($boolean_term, $like_term) {
                $q->orWhereRaw('MATCH(products.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhereRaw('MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhereRaw('MATCH(colors.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhereRaw('MATCH(categories.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhereRaw('MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhereRaw('MATCH(brands.name) AGAINST(? IN BOOLEAN MODE)', [$boolean_term])
                    ->orWhere('products.name', 'LIKE', $like_term)
                    ->orWhere('psizes.size', 'LIKE', $like_term)
                    ->orWhere('colors.name', 'LIKE', $like_term)
                    ->orWhere('categories.name', 'LIKE', $like_term)
                    ->orWhere('sub_categories.name', 'LIKE', $like_term)
                    ->orWhere('brands.name', 'LIKE', $like_term);
            });

        $query->selectRaw(
            '
            (CASE WHEN MATCH(products.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(products.name) AGAINST(? IN BOOLEAN MODE) * 5 ELSE 0 END +
             CASE WHEN MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE) THEN MATCH(psizes.size) AGAINST(? IN BOOLEAN MODE) * 2 ELSE 0 END +
             CASE WHEN MATCH(colors.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(colors.name) AGAINST(? IN BOOLEAN MODE) * 2 ELSE 0 END +
             CASE WHEN MATCH(categories.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(categories.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
             CASE WHEN MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(sub_categories.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
             CASE WHEN MATCH(brands.name) AGAINST(? IN BOOLEAN MODE) THEN MATCH(brands.name) AGAINST(? IN BOOLEAN MODE) * 1 ELSE 0 END +
             CASE WHEN products.name LIKE ? THEN 3 ELSE 0 END +
             CASE WHEN psizes.size LIKE ? THEN 1 ELSE 0 END +
             CASE WHEN colors.name LIKE ? THEN 1 ELSE 0 END +
             CASE WHEN categories.name LIKE ? THEN 0.5 ELSE 0 END +
             CASE WHEN sub_categories.name LIKE ? THEN 0.5 ELSE 0 END +
             CASE WHEN brands.name LIKE ? THEN 0.5 ELSE 0 END) as relevance_score',
            [
                $boolean_term, $boolean_term,
                $boolean_term, $boolean_term,
                $boolean_term, $boolean_term,
                $boolean_term, $boolean_term,
                $boolean_term, $boolean_term,
                $boolean_term, $boolean_term,
                $like_term, $like_term, $like_term,
                $like_term, $like_term, $like_term,
            ]
        );

        $query->orderByRaw('relevance_score DESC')
            ->orderBy('variations.product_id', 'ASC');

        try {
            $variations = Cache::remember('search_'.md5($search_value.$customer_id), 300, function () use ($query) {
                return $query->paginate(50);
            });
        } catch (\Exception $e) {
            Log::error('Global search error: '.$e->getMessage(), [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'search_value' => $search_value,
                'customer_id' => $customer_id,
            ]);

            return response()->json(['error' => 'Search failed', 'message' => $e->getMessage()], 500);
        }

        $formattedProducts = [];
        foreach ($variations as $variation) {
            $totalVariationStock = $variation->stocks ? $variation->stocks->sum('stock_quantity') : 0;
            $salePartyKitPrice = $variation->saleItem ?: [];
            $formattedProducts[] = [
                'variant_id' => $variation->id,
                'name' => $variation->product ? $variation->product->name : 'N/A',
                'totalVariationStock' => $totalVariationStock,
                'color' => $variation->color,
                'salePartyKitPrice' => $salePartyKitPrice,
                'cost_price' => $variation->cost_price,
                'b2b_price' => $variation->b2b_price,
                'b2c_price' => $variation->b2c_price,
                'size' => $variation->size,
                'variation_size' => $variation->variationSize ? $variation->variationSize->size : 'N/A',
                'variation_color' => $variation->colorName ? $variation->colorName->name : $variation->color,
                'price' => $posSetting && $posSetting->sale_price_type === 'b2b_price' ? $variation->b2b_price : $variation->b2c_price,
                'product_id' => $variation->product_id,
                'variation_id' => $variation->id,
                'stock_quantity' => $variation->product ? ($variation->product->stock_quantity_sum_stock_quantity ?? 0) : 0,
                'relevance_score' => $variation->relevance_score ?? 0,
            ];
        }

        $debug = [
            'search_value' => $search_value,
            'search_terms' => array_filter(explode(' ', $search_value)),
            'total_products' => $variations->total(),
            'returned_products' => $variations->count(),
            'sample_results' => $variations->take(5)->map(function ($variation) {
                return [
                    'variant_id' => $variation->id,
                    'name' => $variation->product ? $variation->product->name : 'N/A',
                    'relevance_score' => $variation->relevance_score ?? 0,
                ];
            })->toArray(),
        ];

        return response()->json([
            'products' => $formattedProducts,
            'pos_setting' => $posSetting,
            'debug' => $debug,
            'status' => 200,
        ]);
    }
}
