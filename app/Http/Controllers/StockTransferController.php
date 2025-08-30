<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockTransferController extends Controller
{
    public function index()
    {

        $stocks = Stock::where(function ($query) {
            $query->whereNotNull('warehouse_id')
                ->orWhereNotNull('rack_id');
        })
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('stocks')
                    ->where(function ($q) {
                        $q->whereNotNull('warehouse_id')
                            ->orWhereNotNull('rack_id');
                    })
                    ->groupBy('variation_id', 'warehouse_id', 'rack_id');
            })
            ->get();
        $warehouses = Warehouse::all();

        return view('pos.stock_transfer.stock_transfer', compact('stocks', 'warehouses'));
    }

    public function stockWarehouse(Request $request)
    {

        // dd($request->stock_id);
        $stock = Stock::with('warehouse', 'racks', 'branch')->findOrFail($request->stock_id);
        $variationId = $stock->variation_id;
        $warehouse_id = $stock->warehouse_id;
        $racks_id = $stock->rack_id;
        $warehouses = Stock::with('warehouse', 'branch')->where('variation_id', $variationId)
            ->pluck('warehouse_id');
        if ($racks_id) {
            $totalStockQuantity = Stock::where('variation_id', $variationId)
                ->where('warehouse_id', $warehouse_id)
                ->where('rack_id', $racks_id)
                ->sum('stock_quantity');
        } else {
            $totalStockQuantity = Stock::where('variation_id', $variationId)
                ->where('warehouse_id', $warehouse_id)
            // ->where('rack_id',$racks_id)
                ->sum('stock_quantity');
        }

        // dd($totalStockQuantity);
        if (! $stock || ! $stock->warehouse_id) {
            return response()->json(['warehouse' => null]);
        }

        return response()->json([
            'stock' => $stock,
            'warehouses' => $warehouses,
            'totalStockQuantity' => $totalStockQuantity,
        ]);
    }

    public function stocTransferStore(Request $request)
    {

        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'stock_id' => 'required|exists:stocks,id',
            'from_quantity' => 'required|integer|min:1',
            'to_warehouse_id' => 'required',
            'to_branch_id' => 'required',

        ]);

        if ($validator->passes()) {
            $oldStock = Stock::findOrFail($request->stock_id);
            $variationId = $oldStock->variation_id;
            $productId = $oldStock->product_id;
            $warehouse_id = $oldStock->warehouse_id;
            $racks_id = $oldStock->rack_id;
            if ($racks_id) {
                $totalStockQuantity = Stock::where('variation_id', $variationId)
                    ->where('warehouse_id', $warehouse_id)
                    ->where('rack_id', $racks_id)
                    ->sum('stock_quantity');
            } else {
                $totalStockQuantity = Stock::where('variation_id', $variationId)
                    ->where('warehouse_id', $warehouse_id)
                // ->where('rack_id',$racks_id)
                    ->sum('stock_quantity');
            }
            $remainingQty = $request->from_quantity;
            if ($remainingQty > $totalStockQuantity) {
                return response()->json([
                    'status' => 400,
                    'error' => [
                        'from_quantity' => ['The requested quantity exceeds the available stock in the selected warehouse and rack.'],
                    ],
                ]);
            }

            $stocks = Stock::where('variation_id', $variationId)
                ->get();
            foreach ($stocks as $stock) {
                if ($remainingQty <= 0) {
                    break;
                }

                $deductible = min($remainingQty, $stock->stock_quantity);
                $stock->stock_quantity -= $deductible;
                $remainingQty -= $deductible;

                if ($stock->stock_quantity <= 0) {
                    $stock->delete();

                    // Set the next stock as the current stock
                    $nextStock = Stock::where('variation_id', $variationId)
                        ->orderBy('created_at')
                        ->first();

                    if ($nextStock) {
                        $nextStock->is_Current_stock = true;
                        $nextStock->save();
                    }
                } else {
                    $stock->save(); // Save the updated stock quantity
                }
            }
            if (Stock::where('variation_id', $variationId)->count() == 0) {
                Stock::create([
                    'variation_id' => $variationId,
                    'product_id' => $productId,
                    'branch_id' => $request->to_branch_id,
                    'stock_quantity' => $request->from_quantity,
                    'warehouse_id' => $request->to_warehouse_id ?? null,
                    'rack_id' => $request->to_racks_id ?? null,
                    'is_Current_stock' => 1, // The new stock becomes the current stock
                ]);
            } else {
                Stock::create([
                    'variation_id' => $variationId,
                    'product_id' => $productId,
                    'branch_id' => $request->to_branch_id,
                    'warehouse_id' => $request->to_warehouse_id ?? null,
                    'rack_id' => $request->to_racks_id ?? null,
                    'stock_quantity' => $request->from_quantity,
                    'is_Current_stock' => 0,
                ]);
            }
            // dd($request->all());
            StockTransfer::create([
                'variation_id' => $variationId,
                'product_id' => $productId,
                'transfer_date' => Carbon::now(),
                'quantity' => $request->from_quantity,
                'from_warehouse_id' => $request->to_warehouse_id ?? null,
                'to_warehouse_id' => $request->to_warehouse_id,
                'from_rack_id' => $request->to_warehouse_id,
                'to_rack_id' => $request->to_racks_id,
                'branch_id' => Auth::user()->branch->id,
                'from_branch_id' => $request->from_branch,
                'to_branch_id' => $request->to_branch_id,
                // 'note' =>
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Stock Transfer Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->messages(),
            ]);
        }
    }

    public function view()
    {
        $stockTransfers = StockTransfer::all();

        return view('pos.stock_transfer.view', compact('stockTransfers'));
    }
}
