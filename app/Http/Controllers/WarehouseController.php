<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index()
    {

        return view('pos.warehouses.warehouse');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'warehouse_name' => 'required|max:255',

        ]);
        if ($validator->passes()) {
            $warehouse = new Warehouse;
            $warehouse->warehouse_name = $request->warehouse_name;
            $warehouse->branch_id = Auth::user()->branch_id;
            $warehouse->location = $request->location;
            $warehouse->contact_person = $request->contact_person;
            $warehouse->contact_number = $request->contact_number;
            $warehouse->save();

            return response()->json([
                'status' => 200,
                'message' => 'Warehouse Save Successfully',
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
        $warehouse = Warehouse::latest()->get();

        return response()->json([
            'status' => 200,
            'data' => $warehouse,
        ]);
    }

    public function edit($id)
    {

        $warehouse = Warehouse::findOrFail($id);

        if ($warehouse) {
            return response()->json([
                'status' => 200,
                'warehouse' => $warehouse,
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
        $validator = Validator::make($request->all(), [
            'warehouse_name' => 'required|max:255',
        ]);
        if ($validator->passes()) {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->warehouse_name = $request->warehouse_name;
            $warehouse->location = $request->location;
            $warehouse->contact_person = $request->contact_person;
            $warehouse->contact_number = $request->contact_number;
            $warehouse->save();

            return response()->json([
                'status' => 200,
                'message' => 'Warehouse Update Successfully',
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
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Warehouse Deleted Successfully',
        ]);
    }
}
