<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::get();

        return view('pos.products.stock.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stock_quantity' => 'required|max:255',
            'product_id' => 'required|integer',
        ]);

        if ($validator->passes()) {

            Stock::create([
                'branch_id' => Auth::user()->branch_id,
                'stock_quantity' => $request->stock_quantity,
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Stock Saved Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        } //
    }

    //
    public function view()
    {
        $stocks = Stock::with('product')->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $stocks,
        ]);
    }

    //
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $stock,
        ]);
    }

    //
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'stock_quantity' => 'required|max:255',
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 500,
                'error' => $validator->messages(),
            ]);
        }

        // Find the stock record by ID
        $stock = Stock::find($id);

        if (! $stock) {
            return response()->json([
                'status' => 404,
                'message' => 'Stock not found',
            ]);
        }

        // Update the stock record
        $stock->update([
            'stock_quantity' => $request->stock_quantity,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Stock updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Stock Deleted Successfully',
        ]);
    } //
}
