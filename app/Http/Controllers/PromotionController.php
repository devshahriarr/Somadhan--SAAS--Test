<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Validator;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function PromotionAdd()
    {
        return view('pos.promotion.promotion_add');
    }

    //
    public function PromotionStore(Request $request)
    {
        Promotion::insert([
            'promotion_name' => $request->promotion_name,
            'branch_id' => Auth::user()->branch_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'description' => $request->description,
            'created_at' => Carbon::now(),
        ]);
        $notification = [
            'message' => 'Promotion Added Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('promotion.view')->with($notification);
    } // End Method

    public function PromotionView()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotions = Promotion::all();
        } else {
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.promotion.promotion_view', compact('promotions'));
    }

    // End Method
    public function PromotionEdit($id)
    {
        $promotion = Promotion::findOrFail($id);

        return view('pos.promotion.promotion_edit', compact('promotion'));
    }

    // End Method
    public function PromotionUpdate(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id)->update([
            'promotion_name' => $request->promotion_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'description' => $request->description,
            'updated_at' => Carbon::now(),
        ]);
        $notification = [
            'message' => 'Promotion Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('promotion.view')->with($notification);
    }

    // End Method
    public function PromotionDelete($id)
    {
        Promotion::findOrFail($id)->delete();
        $notification = [
            'message' => 'Promotion Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('promotion.view')->with($notification);
    }

    // End Method
    // find
    public function find($id)
    {
        $promotion = Promotion::findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $promotion,
        ]);
    } // End Method

    // /////////////////////Start Promotion Details All Method ////////////////////////
    public function PromotionDetailsAdd()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $product = Product::latest()->get();
            $promotions = Promotion::latest()->get();
        } else {
            $product = Product::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.promotion.promotion_details_add', compact('product', 'promotions'));
    }

    //
    public function PromotionDetailsStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required',
            'promotion_type' => 'required',
        ]);

        if ($validator->passes()) {
            // dd($request->all());
            $promotionalDetails = new PromotionDetails;
            $promotionalDetails->branch_id = Auth::user()->branch_id;
            $promotionalDetails->promotion_id = $request->promotion_id;
            $promotionalDetails->promotion_type = $request->promotion_type;
            $promotionalDetails->logic = $request->logic;
            $promotionalDetails->additional_conditions = $request->additional_conditions;
            $promotionalDetails->created_at = Carbon::now();
            $promotionalDetails->save();

            return response()->json([
                'status' => 200,
                'message' => 'Promotion Details Added Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'errors' => $validator->errors(),
            ]);
        }
    }

    // End Method
    public function PromotionDetailsView()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotion_details = PromotionDetails::all();
        } else {
            $promotion_details = PromotionDetails::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.promotion.promotion_details_view', compact('promotion_details'));
    }

    // End Method
    public function PromotionDetailsEdit($id)
    {
        // $product = Product::latest()->get();
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $promotions = Promotion::latest()->get();
        } else {
            $promotions = Promotion::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        $promotion_details = PromotionDetails::findOrFail($id);

        return view('pos.promotion.promotion_details_edit', compact('promotion_details', 'promotions'));
    }

    // End Method
    public function PromotionDetailsUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'promotion_id' => 'required',
            'promotion_type' => 'required',
        ]);

        if ($validator->passes()) {
            // dd($request->all());
            $promotionalDetails = PromotionDetails::findOrFail($id);
            $promotionalDetails->promotion_id = $request->promotion_id;
            $promotionalDetails->promotion_type = $request->promotion_type;
            $promotionalDetails->logic = $request->logic;
            $promotionalDetails->additional_conditions = $request->additional_conditions;
            $promotionalDetails->created_at = Carbon::now();
            $promotionalDetails->save();

            return response()->json([
                'status' => 200,
                'message' => 'Promotion Details Added Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'errors' => $validator->errors(),
            ]);
        }
    }

    //
    public function PromotionDetailsDelete($id)
    {
        PromotionDetails::findOrFail($id)->delete();
        $notification = [
            'message' => 'Promotion Details Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('promotion.details.view')->with($notification);
    }

    public function PromotionDetailsFind(Request $request)
    {
        $type = $request->type;

        if ($type) {
            if ($type == 'wholesale') {
                // $wholesale = Product::where('branch_id', Auth::user()->branch_id)->where('stock', ">", 0)->get();
                $wholesale = Product::where('branch_id', Auth::user()->branch_id)
                    ->withSum('stockQuantity', 'stock_quantity')
                    ->having('stock_quantity_sum_stock_quantity', '>', 0)
                    ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
                    ->get();

                return response()->json([
                    'status' => 200,
                    'wholesale' => $wholesale,
                ]);
            } elseif ($type == 'products') {
                $products = Product::where('branch_id', Auth::user()->branch_id)
                    ->withSum('stockQuantity', 'stock_quantity')
                    ->having('stock_quantity_sum_stock_quantity', '>', 0)
                    ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
                    ->get();

                return response()->json([
                    'status' => 200,
                    'products' => $products,
                ]);
            } elseif ($type == 'customers') {
                $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->get();

                return response()->json([
                    'status' => 200,
                    'customers' => $customers,
                ]);
            } elseif ($type == 'branch') {
                $branch = Branch::get();

                return response()->json([
                    'status' => 200,
                    'branch' => $branch,
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Data not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data not found',
            ]);
        }
    }

    public function allProduct()
    {
        $products = Product::where('branch_id', Auth::user()->branch_id)
            ->withSum('stockQuantity', 'stock_quantity')
            ->having('stock_quantity_sum_stock_quantity', '>', 0)
            ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // or 'desc'
            ->get();

        return response()->json([
            'status' => 200,
            'products' => $products,
        ]);
    }

    public function allCustomers()
    {
        $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->get();

        return response()->json([
            'status' => 200,
            'customers' => $customers,
        ]);
    }

    public function allBranch()
    {
        $branch = Branch::get();

        return response()->json([
            'status' => 200,
            'branch' => $branch,
        ]);
    }
}
