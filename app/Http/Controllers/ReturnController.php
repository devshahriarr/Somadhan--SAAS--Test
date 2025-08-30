<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\ReturnItem;
use App\Models\Returns;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    public function Return($id)
    {
        $sale = Sale::findOrFail($id);

        return view('pos.return.return', compact('sale'));
    }

    public function ReturnItems($id)
    {
        $sales = SaleItem::with('product', 'variant.variationSize', 'variant.colorName')->findOrFail($id);

        // dd($sales);
        return response()->json([
            'status' => '200',
            'sale_items' => $sales,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'sale_id' => 'required',
            'customer_id' => 'required',
            'formattedReturnDate' => 'required',
            'refund_amount' => 'required',
            'paymentMethod' => 'required',
        ]);
        if ($validator->passes()) {
            $oldBalance = AccountTransaction::where('account_id', $request->paymentMethod)->latest('created_at')->first();
            // dd($oldBalance);
            if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >= $request->refund_amount ?? 0) {
                $total_return_profit = 0;
                foreach ($request->sale_items as $sale_item) {
                    $saleItem = SaleItem::findOrFail($sale_item['sale_item_id']);

                    // Calculate the average selling price
                    $avg_selling_price = $saleItem->sub_total / $saleItem->qty;

                    // Calculate the actual total return price and return profit
                    $actual_total_return_price = $avg_selling_price * $sale_item['quantity'];
                    $return_profit = $actual_total_return_price - $sale_item['total_price'];

                    // Accumulate the total return profit
                    $total_return_profit += $return_profit;

                    $stock = Stock::where('branch_id', Auth::user()->branch_id)->where('variation_id', $saleItem->variant_id)->where('is_current_stock', true)->first();
                    if ($stock) {
                        $stock->stock_quantity += $sale_item['quantity'];
                        $stock->save();
                    } else {
                        $stock = new Stock;
                        $stock->branch_id = Auth::user()->branch_id ?? 1;
                        $stock->product_id = $saleItem->product_id;
                        $stock->variation_id = $saleItem->variant_id;
                        $stock->stock_quantity = $sale_item['quantity'];
                        $stock->save();
                    }
                }
                $return = new Returns;
                $return->return_invoice_number = rand(123456, 99999);
                $return->branch_id = Auth::user()->branch_id;
                $return->sale_id = $request->sale_id;
                $return->customer_id = $request->customer_id;
                $return->return_date = $request->formattedReturnDate;
                $return->refund_amount = $request->refund_amount;
                $return->return_reason = $request->note ?? '';
                $return->total_return_profit = $total_return_profit;
                $return->status = 1;
                $return->processed_by = Auth::user()->id;
                $return->save();

                foreach ($request->sale_items as $sale_item) {
                    $saleItem = SaleItem::findOrFail($sale_item['sale_item_id']);

                    // Create and populate ReturnItem
                    $returnItems = ReturnItem::create([
                        'return_id' => $return->id,
                        'product_id' => (int) $saleItem->product_id,
                        'variant_id' => (int) $saleItem->variant_id,
                        'quantity' => (int) $sale_item['quantity'],
                        'return_price' => (int) $sale_item['return_price'],
                        'product_total' => (int) $sale_item['total_price'],
                        'return_profit' => ($saleItem->sub_total / $saleItem->qty * $sale_item['quantity']) - $sale_item['total_price'],
                    ]);

                    // Calculate profit adjustments
                    $actual_selling_price = $saleItem->sub_total / $saleItem->qty;
                    $purchase_cost = $saleItem->total_purchase_cost / $saleItem->qty;
                    $sell_profit = ($actual_selling_price - $purchase_cost) * $sale_item['quantity'];

                    // Update sale item profit
                    $saleItem->total_profit = $saleItem->total_profit - $sell_profit + $returnItems->return_profit;
                    $saleItem->save();
                }

                $sales = Sale::findOrFail($request->sale_id);
                $sales->returned = $request->refund_amount;
                $sales->profit = $sales->profit - $total_return_profit;
                $sales->save();

                // Fetch customer and their due balance
                $customer = Customer::findOrFail($request->customer_id);
                $customerDue = $customer->wallet_balance;

                // Fetch the latest account transaction
                $lastTransaction = AccountTransaction::where('account_id', $request->paymentMethod)->latest('created_at')->first();

                // Initialize account transaction
                $accountTransaction = new AccountTransaction([
                    'branch_id' => Auth::user()->branch_id,
                    'reference_id' => $return->id,
                    'account_id' => $request->paymentMethod,
                    'created_at' => Carbon::now(),
                ]);

                // Initialize transaction
                $transaction = new Transaction([
                    'date' => $request->formattedReturnDate,
                    'others_id' => $return->id,
                    'branch_id' => Auth::user()->branch_id,
                    'payment_method' => $request->paymentMethod,
                    'created_at' => Carbon::now(),
                ]);

                // Handle refund logic based on adjustDue flag
                if ($request->adjustDue == 'yes') {
                    if ($customerDue > $request->refund_amount) {
                        // Adjust due balance
                        $dueBalance = $customerDue - $request->refund_amount;
                        $customer->wallet_balance -= $dueBalance;

                        // Set account transaction details
                        $accountTransaction->purpose = 'Adjust Due';
                        $accountTransaction->credit = $request->refund_amount;
                        $accountTransaction->balance = $lastTransaction->balance + $request->refund_amount;

                        // Set transaction details
                        $transaction->particulars = 'Adjust Due Collection';
                        $transaction->payment_type = 'receive';
                        $transaction->credit = $request->refund_amount;
                        $transaction->balance = $request->refund_amount;
                    } else {
                        // Handle return balance
                        $returnBalance = $request->refund_amount - $customerDue;
                        $customer->wallet_balance = 0;

                        // Set account transaction details
                        $accountTransaction->purpose = 'Return';
                        $accountTransaction->debit = $returnBalance;
                        $accountTransaction->balance = $lastTransaction->balance - $returnBalance;

                        // Set transaction details
                        $transaction->particulars = 'Return';
                        $transaction->payment_type = 'pay';
                        $transaction->debit = $returnBalance;
                        $transaction->balance = $returnBalance;
                    }
                } else {
                    // Handle non-adjust due case
                    $accountTransaction->purpose = 'Return';
                    $accountTransaction->debit = $request->refund_amount;
                    $accountTransaction->balance = $lastTransaction->balance - $request->refund_amount;

                    $transaction->particulars = 'Return';
                    $transaction->payment_type = 'pay';
                    $transaction->debit = $request->refund_amount;
                    $transaction->balance = $request->refund_amount;
                }

                // Save changes
                $customer->save();
                $accountTransaction->save();
                $transaction->save();

                return response()->json([
                    'status' => '200',
                    'message' => 'Product Return successful',
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Not Enough Balance in this Account. Please choose Another Account or Deposit Account Balance.',
                ]);
            }
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function returnProductsList()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $returns = Returns::get();
        } else {
            $returns = Returns::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.return.return-view', compact('returns'));
    }

    public function returnProductsInvoice($id)
    {

        $return = Returns::findOrFail($id);
        $customer = Customer::findOrFail($return->customer_id);
        $returnItems = ReturnItem::where('return_id', $return->id)->get();
        $branch = Branch::findOrFail($return->branch_id);

        return view('pos.return.return-invoice', compact('return', 'customer', 'returnItems', 'branch'));
    }
}
