<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\WarehouseRack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RackController extends Controller
{
    public function index()
    {
        $Stocks = Stock::with('product')->get();
        $warehouses = Warehouse::all();

        return view('pos.warehouses.racks.racks', compact('warehouses', 'Stocks'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            'rack_name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $racks = new WarehouseRack;
            $racks->warehouse_id = $request->warehouse_id;
            $racks->rack_name = $request->rack_name;
            $racks->max_capacity = $request->max_capacity;
            $racks->save();

            return response()->json([
                'status' => 200,
                'message' => 'Racks Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);

        }
    }

    public function view()
    {
        $racks = WarehouseRack::with('warehouse', 'stock')->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $racks,
        ]);
    }

    public function edit($id)
    {

        $racks = WarehouseRack::findOrFail($id);

        if ($racks) {
            return response()->json([
                'status' => 200,
                'racks' => $racks,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            'rack_name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $racks = WarehouseRack::findOrFail($id);
            $racks->warehouse_id = $request->warehouse_id;
            $racks->rack_name = $request->rack_name;
            $racks->max_capacity = $request->max_capacity;
            $racks->save();

            return response()->json([
                'status' => 200,
                'message' => 'Racks Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);

        }
    }

    public function destroy($id)
    {
        $warehouse = WarehouseRack::findOrFail($id);
        $warehouse->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Racks Deleted Successfully',
        ]);
    }

    // ----------------------------Assign Rack ----------------------------//
    public function assignRack()
    {
        $warehouses = Warehouse::all();

        return view('pos.warehouses.assign_rack.assign-rack', compact('warehouses'));
    }

    public function getracks(Request $request)
    {
        $warehouseId = $request->query('warehouse_id');
        $racks = WarehouseRack::where('warehouse_id', $warehouseId)->get();

        return response()->json($racks);
    }

    public function CheckAlreadyStock(Request $request)
    {
        $rackStocks = Stock::with('product', 'variation.variationSize')
            ->whereNull('rack_id')
            ->whereNull('warehouse_id')
            ->get();

        return response()->json($rackStocks);
    }

    public function assignStore(Request $request, $stockId)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            // 'racks_id' => 'required',
            'stock_id' => 'required',
        ]);
        if ($validator->passes()) {
            $stocks = Stock::findOrFail($stockId);
            $stocks->warehouse_id = $request->warehouse_id;
            $stocks->rack_id = $request->racks_id;
            $stocks->save();

            return response()->json([
                'status' => 200,
                'message' => 'Racks Assign Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);

        }
    }

    public function assignView()
    {
        $racks = Stock::with('warehouse', 'racks', 'product', 'variation.variationSize')
            ->whereNotNull('rack_id')
            ->whereNotNull('warehouse_id')
            ->latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $racks,
        ]);
    }
}
