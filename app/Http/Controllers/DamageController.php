<?php

namespace App\Http\Controllers;

use App\Models\Damage;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Variation;
use App\Repositories\RepositoryInterfaces\DamageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DamageController extends Controller
{
    private $damage_repo;

    public function __construct(DamageInterface $damage_interface)
    {
        $this->damage_repo = $damage_interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pos.damage.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'date' => 'required',
        ]);

        // $variants = $request->product_id;
        //    dd($request->all());
        foreach ($request->product_id as $index => $variantId) {
            $variant = Variation::findOrFail($variantId);
            $remainingQty = $request->quantity[$index];
            $productId = $request->damageProductId[$index];

            // Fetch stocks for the branch and variant
            $stocks = Stock::where('branch_id', Auth::user()->branch_id)
                ->where('variation_id', $variantId)
                ->orderBy('created_at')
                ->get();

            foreach ($stocks as $stock) {

                $stock->stock_quantity -= $remainingQty;

                if ($stock->stock_quantity <= 0) {
                    $stock->delete(); // Delete fully consumed stock
                } else {
                    $stock->save();
                }
            }

            // Create a Damage record for the remaining quantity
            if ($remainingQty > 0) {
                $damage = new Damage;
                $damage->variation_id = $variantId;
                $damage->product_id = $productId;
                $damage->qty = $remainingQty;
                $damage->damage_cost = $variant->cost_price * $remainingQty;
                $damage->branch_id = Auth::user()->branch_id;
                $damage->date = date('Y-m-d H:i:s', strtotime($request->date));
                $damage->note = $request->note;
                $damage->save();
            }
        }

        // Redirect with success notification
        $notification = [
            'message' => 'Damage added successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('report.damage')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function view()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $damages = Damage::all();
        } else {
            $damages = Damage::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.damage.view_damage', compact('damages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function ShowQuantity($id)
    {
        $show_qty = Product::with('unit')
            ->withSum(['stockQuantity' => function ($query) {
                // This ensures you're not filtering by branch_id
                $query->where('branch_id', Auth::user()->branch_id); // or remove any condition on branch_id if not needed
            }], 'stock_quantity')
            ->having('stock_quantity_sum_stock_quantity', '>', 0)
            ->orderBy('stock_quantity_sum_stock_quantity', 'asc')
            ->findOrFail($id);

        return response()->json([
            'all_data' => $show_qty,
            'unit' => $show_qty->unit,
            'stock_quantity' => $show_qty->stock_quantity_sum_stock_quantity,
        ]);
    }

    public function edit($id)
    {
        $damage_info = Damage::findOrFail($id);

        return view('pos.damage.edit', compact('damage_info'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'pc' => 'required',
            'date' => 'required',
        ]);
        if ($validator->passes()) {
            $data = $request->all();

            $product_qty = Product::findOrFail($request->product_id);
            // dd($request->all());

            $damage = Damage::findOrFail($id);

            $damage->product_id = $request->product_id;
            $stock = Stock::where('branch_id', Auth::user()->branch_id)->where('product_id', $request->product_id)->first();
            // dd($damage->qty, $request->pc);
            if ($damage->qty > $request->pc) {
                $updatedValue = $damage->qty - $request->pc;
                $stock->stock_quantity += $updatedValue;
            } elseif ($damage->qty < $request->pc) {
                $updatedValue2 = $request->pc - $damage->qty;
                $stock->stock_quantity -= $updatedValue2;
            } else {
                $stock->stock_quantity = $stock->stock_quantity;
            }
            $stock->save();
            $damage->qty = $request->pc;
            $product_price = $product_qty->cost * $request->pc;
            $damage->damage_cost = $product_price;
            $damage->branch_id = Auth::user()->branch_id;
            $formattedDate = date('Y-m-d H:i:s', strtotime($request->date));
            $damage->date = $formattedDate;
            $damage->note = $request->note;
            $damage->update();
        }

        $notification = [
            'message' => 'Damage Update Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('report.damage')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($damage_id, $product_id)
    // {
    //     $damage_info = Damage::findOrFail($damage_id);
    //     $stock = Stock::where('product_id', $product_id)->first();
    //     $stock->stock_quantity += $damage_info->qty;
    //     $stock->save();
    //     $damage_info->delete();
    //     $notification = array(
    //         'message' => 'Damage Deleted successfully',
    //         'alert-type' => 'info'
    //     );
    //     return back()->with($notification);
    // }
}
