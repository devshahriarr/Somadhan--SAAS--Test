<?php

namespace App\Http\Controllers;

// use Validator;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    public function TaxAdd()
    {
        return view('pos.products.tax.tax');
    }

    public function TaxStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
            'percentage' => 'required|max:39',
        ]);

        if ($validator->passes()) {
            $tax = new Tax;
            $tax->name = $request->name;
            $tax->percentage = $request->percentage;

            $tax->save();

            return response()->json([
                'status' => 200,
                'message' => 'Tax Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    //
    public function TaxView()
    {
        $tax = Tax::get();

        return response()->json([
            'status' => 200,
            'data' => $tax,
        ]);
    }

    //
    public function TaxEdit($id)
    {
        $tax = Tax::findOrFail($id);
        if ($tax) {
            return response()->json([
                'status' => 200,
                'tax' => $tax,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    //
    public function TaxUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
            'percentage' => 'required|max:39',

        ]);
        if ($validator->passes()) {
            $tax = Tax::findOrFail($id);
            $tax->name = $request->name;
            $tax->percentage = $request->percentage;
            $tax->save();

            return response()->json([
                'status' => 200,
                'message' => 'Tax Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    //
    public function TaxDelete($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Tax Deleted Successfully',
        ]);
    }
}
