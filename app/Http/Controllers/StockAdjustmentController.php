<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItems;
use App\Models\Variation;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $branchs = Branch::latest()->get();
        $warehouses = Warehouse::all();

        return view('pos.warehouses.stock_adjustments.stock_adjustment', compact('branchs', 'warehouses'));
    }

    public function adujustmentRackView(Request $request)
    {
        $warehouseId = $request->warehouse_id;
        $rackId = $request->rack_id;
        $racks = Stock::with('product', 'variation.variationSize')->where('warehouse_id', $warehouseId)->where('rack_id', $rackId)->get();

        return response()->json([
            'racks' => $racks,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'adjustment_type' => 'required',
            // 'warehouse_id' => 'required',
            // 'rack_id' => 'required',
        ]);
        if ($validator->passes()) {
            foreach ($request->product_id as $index => $variantId) {
                $variant = Variation::findOrFail($variantId);
                $brachId = Branch::findOrFail($request->branch_id);
                // dd($brachId);
                $productId = $request->adjustProductId[$index];
                $remainingQty = $request->quantity[$index];
                $adjustQty = $remainingQty;
                // dd($remainingQty);
                // Fetch stocks for the branch and variant
                if ($request->adjustment_type === 'increase') {
                    $stocks = Stock::where('branch_id', $request->branch_id)
                        ->where('variation_id', $variant->id) // Use $variant->id
                        ->where('product_id', $productId)
                        ->where('is_Current_stock', 1)
                        ->orderBy('created_at')
                        ->get();
                    if ($stocks->isEmpty()) {
                        $stock = Stock::create([
                            'branch_id' => $request->branch_id,
                            'variation_id' => $variant->id,
                            'product_id' => $productId,
                            'stock_quantity' => 0, // Initial quantity before adjustment
                            'stock_age' => 'new',
                            'is_Current_stock' => 1,
                            // Add any other required fields based on your Stock model
                        ]);
                        $stocks = collect([$stock]);
                    }
                }
                //   }
                if ($request->adjustment_type === 'decrease') {
                    $stocks = Stock::where('branch_id', $request->branch_id)
                        ->where('variation_id', $variant->id) // Use $variant->id
                        ->where('product_id', $productId)
                        ->orderBy('created_at')
                        ->get();
                }
                $prevstocks = Stock::where('branch_id', $request->branch_id)
                    ->where('variation_id', $variant->id) // Use $variant->id
                    ->where('product_id', $productId)
                    ->orderBy('created_at')
                    ->get();
                $previousQuantity = $prevstocks->sum('stock_quantity') ?? 0;

                foreach ($stocks as $stock) {

                    //  $stockCurrent =$stock->is_Current_stock == 1;

                    if ($request->adjustment_type === 'increase') {

                        $newqty = $previousQuantity + $remainingQty;
                        $stock->stock_quantity += $remainingQty;
                        $stock->save();
                    } elseif ($request->adjustment_type === 'decrease') {
                        $newqtyDecrease = $previousQuantity - $adjustQty;
                        //  $stock->stock_quantity -= $remainingQty;

                        $deductible = min($remainingQty, $stock->stock_quantity);
                        $stock->stock_quantity -= $deductible;
                        $remainingQty -= $deductible;

                        if ($stock->stock_quantity <= 0) {
                            // dd($stock->delete());
                            $stock->delete();

                            $nextStock = Stock::where('branch_id', Auth::user()->branch_id)
                                ->where('variation_id', $variant->id)
                                ->where('product_id', $productId)
                                ->orderBy('created_at')
                                ->first();

                            if ($nextStock) {
                                $nextStock->is_Current_stock = true;
                                $nextStock->save();
                            }
                        } else {
                            $stock->save();
                        }

                    }

                }

                $adjustmentNumber = rand(100000, 999999); // Generate a random 6-digit number

                // Check if the generated number already exists to ensure uniqueness
                while (StockAdjustment::where('adjustment_number', $adjustmentNumber)->exists()) {
                    $adjustmentNumber = rand(100000, 999999); // Regenerate if it already exists
                }
                $stockAdjustID = StockAdjustment::create(
                    [
                        'adjustment_number' => $adjustmentNumber,
                        'branch_id' => $brachId->id,
                        'warehouse_id' => $request->warehouse_id ?? null,
                        'rack_id' => $request->rack_id ?? null,
                        'reason' => $request->reason,
                        'adjustment_type' => $request->adjustment_type,
                        'adjusted_by' => Auth::id(),
                    ]

                );
                if ($request->adjustment_type === 'increase') {
                    StockAdjustmentItems::create(
                        [
                            'adjustment_id' => $stockAdjustID->id,
                            'product_id' => $productId,
                            'variation_id' => $variant->id,
                            'previous_quantity' => $previousQuantity,
                            'adjusted_quantity' => $adjustQty,
                            'final_quantity' => $newqty,
                        ]
                    );
                } elseif ($request->adjustment_type === 'decrease') {
                    StockAdjustmentItems::create(
                        [
                            'adjustment_id' => $stockAdjustID->id,
                            'product_id' => $productId,
                            'variation_id' => $variant->id,
                            'previous_quantity' => $previousQuantity,
                            'adjusted_quantity' => $adjustQty,
                            'final_quantity' => $newqtyDecrease,
                        ]
                    );
                }

            }

            return response()->json([
                'status' => 200,
                'message' => 'Stock adjustment successfully saved.',
            ]);
        } else {

            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }
    }

    public function productDefault()
    {
        $products = Product::withSum(
            [
                'stockQuantity as stock_quantity_sum' => function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                },
            ],
            'stock_quantity',
        )
            // ->having('stock_quantity_sum', '>', 0) // Use having method here
            ->orderBy('stock_quantity_sum', 'asc')
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }

    // end
    public function adjustmentView()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $adjustments = StockAdjustment::latest()->with('items.product', 'items.variation.variationSize')->get();
        } else {
            $adjustments = StockAdjustment::where('branch_id', Auth::user()->branch_id)
                ->latest()
                ->with('items.product', 'items.variation.variationSize')
                ->get();
        }

        return view('pos.report.warehouses.stock_adjustments.view_stock_adjustment', compact('adjustments'));
    }
}// End Main
