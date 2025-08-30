<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\ActualPayment;
use App\Models\Affiliator;
use App\Models\AffliateCommission;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\CouerierOrder;
use App\Models\Customer;
use App\Models\PosSetting;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\PromotionDetails;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Variation;
use App\Models\ViaSale;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    // sale index function
    public function index()
    {
        try {
            // Attempt to return the specified view
            return view('pos.sale.sale');
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in index method: '.$e->getMessage());

            // Return a custom error view with a user-friendly message and a 500 status code
            return response()->view('errors.500', ['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    public function saleWithoutSidebar()
    {
        return view('pos.sale.sale_without_sidebar');
    }

    // Get Customer data
    public function getCustomer()
    {
        try {
            // Retrieve customers associated with the authenticated user's branch
            $data = Customer::where('branch_id', Auth::user()->branch_id)->where('party_type', '<>', 'supplier')->get();

            // Check if any customers were found
            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No customers found.',
                ]);
            }

            // Return the customer data with a success message
            return response()->json([
                'status' => 200,
                'message' => 'Customers retrieved successfully.',
                'allData' => $data,
            ]);
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in getCustomer method: '.$e->getMessage());

            // Return a JSON response with a generic error message
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function getCustomer2()
    {
        try {
            // Retrieve customers associated with the authenticated user's branch
            $data = Customer::where('branch_id', Auth::user()->branch_id)
                ->where('party_type', '<>', 'supplier')
                ->orderBy('created_at', 'asc')
                ->get();

            // Check if any customers were found
            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No customers found.',
                ]);
            }

            // Return the customer data with a success message
            return response()->json([
                'status' => 200,
                'message' => 'Customers retrieved successfully.',
                'allData' => $data,
            ]);
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in getCustomer method: '.$e->getMessage());

            // Return a JSON response with a generic error message
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    // Add customer function
    public function addCustomer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|unique:users,phone',
                'opening_receivable' => 'nullable|numeric|max_digits:12',
                'opening_payable' => 'nullable|numeric|max_digits:12',
                'address' => 'nullable|string|max:250',
                'email' => 'nullable|email|unique:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            }
            $balance = $request->opening_receivable - $request->opening_payable;

            $customer = new Customer;
            $customer->branch_id = Auth::user()->branch_id;
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->email = $request->email;
            $customer->address = $request->address;
            $customer->opening_receivable = $request->opening_receivable ?? 0;
            $customer->opening_payable = $request->opening_payable ?? 0;
            $customer->total_receivable = $request->opening_receivable ?? 0;
            $customer->total_payable = $request->opening_payable ?? 0;
            $customer->wallet_balance = $balance;
            $customer->party_type = 'customer';
            $customer->created_at = Carbon::now();
            $customer->save();

            if ($request->opening_payable > 0 || $request->opening_receivable > 0) {
                $transaction = new Transaction;
                $transaction->date = Carbon::now();
                $transaction->processed_by = Auth::user()->id;
                $transaction->payment_type = 'receive';
                if ($balance > 0) {
                    $transaction->particulars = 'OpeningDue';
                } else {
                    $transaction->particulars = 'OpeningBalance';
                }
                $transaction->customer_id = $customer->id;
                $transaction->credit = $request->opening_payable ?? 0;
                $transaction->debit = $request->opening_receivable ?? 0;
                $transaction->balance = $balance;
                $transaction->branch_id = Auth::user()->branch_id;
                $transaction->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Saved',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => 500,
                'error' => 'An unexpected error occurred. Please try again later.',
            ]);
        }
    }

    // store function for sale
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|numeric',
                'sale_date' => 'required|date',
                'payment_method' => 'required',
                'paid' => 'required|numeric',
                'variants' => 'required|array',
                // 'quantity' => 'required|array',
                'invoice_number' => 'nullable|unique:sales,invoice_number',
            ], [
                'customer_id.numeric' => 'Please add a valid customer.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            }

            $settings = PosSetting::first();
            // check invoice payment on or off
            $invoice_payment = $settings?->invoice_payment ?? 0;
            // dd($request->invoice_total);

            // calculate total product cost price
            $totalCostPrice = 0;
            $variants = $request->variants;
            foreach ($variants as $variant) {
                $items = Variation::findOrFail($variant['variant_id']);

                // dd($items);
                $total = $items->cost_price * $variant['quantity'];
                $totalCostPrice += $total;
            }
            // Sale Table CRUD
            $sale = new Sale;
            $sale->branch_id = Auth::user()->branch_id;
            $sale->customer_id = $request->customer_id;
            $sale->sale_date = $request->sale_date;
            $sale->sale_by = Auth::user()->id;
            $sale->invoice_number = $request->invoice_number;
            $sale->order_type = 'general';
            $sale->quantity = $request->quantity;
            // dd($request->sale_discount_type);
            $sale->discount_type = $request->sale_discount_type;
            $sale->total = $request->product_total;
            $sale->change_amount = $request->invoice_total;
            $sale->discount = $request->discount;
            if ($request->sale_discount_type === 'percentage') {
                $total = $request->product_total;
                $percentage = $request->discount;
                $sale->actual_discount = ($total * $percentage) / 100;
            } else {
                $sale->actual_discount = $request->discount ?? 0;
            }
            $sale->total_purchase_cost = $totalCostPrice;
            $sale->tax = $request->tax;
            $sale->receivable = $request->grand_total;
            $sale->due = $request->due;
            if ($invoice_payment === 1) {
                if ($request->paid > 0) {
                    if ($request->paid >= $request->invoice_total) {
                        $sale->paid = $request->invoice_total;
                        $sale->status = 'paid';
                    } else {
                        $sale->status = 'partial';
                        $sale->paid = $request->paid;
                    }
                } else {
                    $sale->status = 'unpaid';
                    $sale->paid = 0;
                }
            } else {
                if ($request->paid > 0) {
                    if ($request->paid >= $request->invoice_total) {
                        $sale->status = 'paid';
                    } else {
                        $sale->status = 'partial';
                    }
                } else {
                    $sale->status = 'unpaid';
                }
                $sale->paid = $request->paid;
            }
            // Determine the status based on paid amount

            $sale->order_status = 'completed';
            $sale->final_receivable = $request->grand_total;
            $sale->payment_method = $request->payment_method;
            // $totalSell = $request->total_amount - $request->actual_discount;
            $sale->profit = $request->invoice_total - $totalCostPrice;

            $sale->note = $request->note;
            $sale->created_at = Carbon::now();
            $sale->save();

            $saleId = $sale->id;

            // affiliate calculation start

            if ($request->affiliator_id != null) {
                foreach ($request->affiliator_id as $affiliateId) {
                    $affiliate = Affiliator::find($affiliateId);

                    if (! $affiliate) {
                        continue; // Skip if not found
                    }

                    $affiliateCommission = new AffliateCommission;
                    $affiliateCommission->sale_id = $saleId;
                    $affiliateCommission->branch_id = Auth::user()->branch_id;
                    $affiliateCommission->affiliator_id = $affiliateId;

                    if ($affiliate->commission_type == 'fixed') {
                        $affiliateCommission->commission_amount = $affiliate->commission_rate;
                    } elseif ($affiliate->commission_type == 'percentage') {
                        if ($affiliate->commission_state == 'against_sale_amount') {
                            $affiliateCommission->commission_amount = $sale->change_amount * ($affiliate->commission_rate / 100);
                        } else {
                            $affiliateCommission->commission_amount = $sale->profit * ($affiliate->commission_rate / 100);
                        }
                    }

                    $affiliateCommission->save();
                }
            }

            // affiliate calculation end
            // Sale Commission Of Seller
            if ($settings->sale_commission == 1) {
                $saleCommissionerCheck = Affiliator::where('user_id', Auth::user()->id)->first();
                if ($saleCommissionerCheck) {
                    $affiliateSaleCommission = new AffliateCommission;
                    $affiliateSaleCommission->sale_id = $saleId;
                    $affiliateSaleCommission->branch_id = Auth::user()->branch_id;
                    $affiliateSaleCommission->affiliator_id = $saleCommissionerCheck->id;

                    if ($saleCommissionerCheck->commission_type == 'fixed') {
                        $affiliateSaleCommission->commission_amount = $saleCommissionerCheck->commission_rate;
                    } elseif ($saleCommissionerCheck->commission_type == 'percentage') {
                        if ($affiliate->commission_state == 'against_sale_amount') {
                            $affiliateSaleCommission->commission_amount = $sale->change_amount * ($saleCommissionerCheck->commission_rate / 100);
                        } else {
                            $affiliateSaleCommission->commission_amount = $sale->profit * ($saleCommissionerCheck->commission_rate / 100);
                        }
                    }

                    $affiliateSaleCommission->save();
                }
            }

            foreach ($variants as $item) {
                $variant = Variation::findOrFail($item['variant_id']);
                $remainingQty = $item['quantity'];
                // $product = Product::finOrFail($variant->product_id);

                $stocks = Stock::where('branch_id', Auth::user()->branch_id)
                    ->where('variation_id', $item['variant_id'])
                    ->orderBy('created_at')
                    ->get();

                // save saleItems
                if ($settings->selling_price_update === 1) {
                    if ($settings->sale_price_type === 'b2b_price') {
                        $variant->b2b_price = $item['unit_price'];
                    } elseif ($settings->sale_price_type === 'b2c_price') {
                        $variant->b2c_price = $item['unit_price'];
                    }
                    $variant->save();
                }
                $saleItem = new SaleItem;
                $saleItem->sale_id = $saleId;
                $saleItem->product_id = $variant->product_id;
                $saleItem->variant_id = $variant->id;
                $saleItem->rate = $item['unit_price'];
                $saleItem->qty = $item['quantity'];
                $saleItem->wa_status = isset($item['wa_duration']) ? 'yes' : 'no';
                // Check if 'wa_duration' exists in the $item array
                $saleItem->wa_duration = isset($item['wa_duration']) ? $item['wa_duration'] : null;
                $saleItem->discount = $item['product_discount'];
                $saleItem->sub_total = $item['total_price'];
                $saleItem->total_purchase_cost = $variant->cost_price * $item['quantity'];
                $saleItem->total_profit = $item['total_price'] - ($variant->cost_price * $item['quantity']);
                $saleItem->sell_type = 'normal sell';
                $saleItem->save();

                // Loop through each stock to deduct the quantity
                foreach ($stocks as $stock) {
                    if ($remainingQty <= 0) {
                        break; // Stop if no more quantity is left to deduct
                    }

                    // Deduct the minimum of remaining quantity and current stock quantity
                    $deductible = min($remainingQty, $stock->stock_quantity);
                    $stock->stock_quantity -= $deductible;
                    $remainingQty -= $deductible;

                    // If the stock is fully used, delete it
                    if ($stock->stock_quantity <= 0) {
                        $stock->delete();

                        // Set the next stock as the current stock
                        $nextStock = Stock::where('branch_id', Auth::user()->branch_id)
                            ->where('variation_id', $item['variant_id'])
                            // ->where('id', '>', $stock->id)
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

                // If there is still remaining quantity, create a new stock with negative quantity
                if ($remainingQty > 0) {
                    $newStock = new Stock;
                    $newStock->branch_id = Auth::user()->branch_id ?? 1;
                    $newStock->variation_id = $item['variant_id'];
                    $newStock->product_id = $variant->product_id;
                    $newStock->stock_quantity = -$remainingQty;
                    $newStock->status = 'stock_out';
                    $newStock->is_Current_stock = true; // Mark this as the current stock
                    $newStock->save();
                }
            }

            // customer table update //
            $customer = Customer::findOrFail($request->customer_id);
            $customer->total_receivable += $request->invoice_total;
            if ($invoice_payment === 1) {
                // dd($request->invoice_total);
                $customer->total_payable += ($request->paid >= $request->invoice_total) ? $request->invoice_total : $request->paid;
                // $customer->wallet_balance +=  ($request->paid >= $request->invoice_total) ? 0 : ($request->invoice_total - $request->paid);
                $customer->wallet_balance = $customer->total_receivable - $customer->total_payable;
            } else {
                // dd($customer->total_payable);
                $customer->total_payable += $request->paid;
                // $customer->wallet_balance += $request->invoice_total - $request->paid;
                $customer->wallet_balance = $request->grand_total - $request->paid;
                // $customer->wallet_balance = $customer->total_receivable - $customer->total_payable;
            }
            // dd($customer->total_payable);
            // dd('hello'. $customer->wallet_balance);
            $customer->save();

            // actual Payment table create
            $actualPayment = new ActualPayment;
            $actualPayment->branch_id = Auth::user()->branch_id;
            $actualPayment->payment_type = 'receive';
            $actualPayment->payment_method = $request->payment_method;
            $actualPayment->customer_id = $request->customer_id;
            $actualPayment->amount = $request->paid;
            $actualPayment->date = $request->sale_date;
            $actualPayment->save();

            // create new accountTransaction
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'Sale';
            $accountTransaction->reference_id = $saleId;
            $accountTransaction->account_id = $request->payment_method;
            $accountTransaction->credit = $request->paid;
            $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
            if ($oldBalance) {
                $accountTransaction->balance = $oldBalance->balance + $request->paid;
            } else {
                $accountTransaction->balance = $request->paid;
            }
            $accountTransaction->processed_by = Auth::user()->id;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            $transaction = new Transaction;
            $transaction->date = $request->sale_date;
            $transaction->processed_by = Auth::user()->id;
            $transaction->payment_type = 'receive';
            $transaction->particulars = 'Sale#'.$saleId;
            $transaction->customer_id = $request->customer_id;
            $transaction->payment_method = $request->payment_method;
            $transaction->credit = $request->paid;
            $transaction->debit = $request->invoice_total;
            $transaction->balance = $request->invoice_total - $request->paid;

            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->save();
            // ------------------------------------sms-------------------------------//
            $settings = PosSetting::first();
            $sale_sms = $settings->sale_sms;
            $dueCalculate = $request->invoice_total - $request->paid;

            if ($sale_sms == 1) {
                $dueCalculate = (float) $dueCalculate;
                if ($dueCalculate < 0) {
                    $message = "Dear {$customer->name}, your sale (Invoice: {$sale->invoice_number}) has been successfully processed. Total Amount: {$request->invoice_total}, Paid: {$request->paid}. Extra Collection: ".abs($dueCalculate).'. Thank you for your purchase.';
                } else {
                    $message = "Dear {$customer->name}, your sale (Invoice: $sale->invoice_number) has been successfully processed. Total Amount: {$request->invoice_total}, Paid: {$request->paid}. Due : {$dueCalculate}  Thank you for your purchase.";
                }
                $number = $customer->phone;
                $api_key = '0yRu5BkB8tK927YQBA8u';
                $senderid = '8809617615171';

                $url = 'http://bulksmsbd.net/api/smsapi';
                $data = [
                    'api_key' => $api_key,
                    'number' => $number,
                    'senderid' => $senderid,
                    'message' => $message,
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response, true);
            }
            // ------------------------------------sms End-------------------------------//
            // ////// For Alert////////
            $variantStockAlerts = []; // Initialize an array to store all variant data
            $lowStockVariants = [];   // Initialize an array for low-stock variants

            foreach ($variants as $item) {
                // Fetch each variant with its stock quantity and add to the array
                $variation = Variation::findOrFail($item['variant_id'])->load(
                    'stockQuantity',
                    'product',
                    'colorName',
                    'variationSize'
                );
                $variantStockAlerts[] = $variation;

                // Check if stock_quantity is less than low_stock_alert
                $stockQuantity = $variation->stockQuantity ? $variation->stockQuantity->stock_quantity : 0;
                if ($stockQuantity < $variation->low_stock_alert) {
                    $lowStockVariants[] = [
                        'id' => $variation->id,
                        'stock_quantity' => $stockQuantity,
                        'low_stock_alert' => $variation->low_stock_alert,
                        'product' => $variation->product ? ['name' => $variation->product->name] : null,
                        'color' => $variation->colorName ? ['name' => $variation->colorName->name] : null,
                        'size' => $variation->variationSize ? ['name' => $variation->variationSize->size] : null,
                    ];
                }
            }
            // ////// For Alert End////////

            return response()->json([
                'status' => 201,
                'saleId' => $saleId,
                'variantStockAlert' => $variantStockAlerts,
                'lowStockVariants' => $lowStockVariants,
                'message' => 'Successfully save',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.', 'error_message' => $e->getMessage()], 500);
        }
    }

    public function draftInvoice(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|numeric',
                'sale_date' => 'required|date',
                'variants' => 'required|array',
            ], [
                'customer_id.numeric' => 'Please add a valid customer.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            }
            // Sale Table CRUD
            $sale = new Sale;
            $sale->branch_id = Auth::user()->branch_id;
            $sale->customer_id = $request->customer_id;
            $sale->sale_date = $request->sale_date;
            $sale->sale_by = Auth::user()->id;
            $sale->invoice_number = $request->invoice_number;
            $sale->order_type = 'general';
            $sale->quantity = $request->quantity;
            $sale->total = $request->product_total;
            $sale->change_amount = $request->invoice_total;
            // $sale->actual_discount = $request->actual_discount;
            $sale->discount = $request->discount;
            if ($request->sale_discount_type === 'percentage') {
                $total = $request->product_total;
                $percentage = $request->discount;
                $sale->actual_discount = ($total * $percentage) / 100;
            } else {
                $sale->actual_discount = $request->discount ?? 0;
            }
            $sale->tax = $request->tax;
            $sale->receivable = $request->grand_total;
            $sale->due = 00;
            $sale->paid = 00;
            $sale->status = 'unpaid';
            // Determine the status based on paid amount
            $sale->order_status = 'draft';
            $sale->final_receivable = $request->grand_total;
            $sale->payment_method = $request->payment_method;
            // $totalSell = $request->total_amount - $request->actual_discount;
            $sale->profit = 00;
            $sale->note = $request->note;
            $sale->created_at = Carbon::now();
            $sale->save();

            $saleId = $sale->id;

            if ($request->affiliator_id != null) {
                foreach ($request->affiliator_id as $affiliateId) {
                    $affiliate = Affiliator::find($affiliateId);

                    if (! $affiliate) {
                        continue; // Skip if not found
                    }

                    $affiliateCommission = new AffliateCommission;
                    $affiliateCommission->sale_id = $saleId;
                    $affiliateCommission->branch_id = Auth::user()->branch_id;
                    $affiliateCommission->affiliator_id = $affiliateId;

                    if ($affiliate->commission_type == 'fixed') {
                        $affiliateCommission->commission_amount = $affiliate->commission_rate;
                    } elseif ($affiliate->commission_type == 'percentage') {
                        if ($affiliate->commission_state == 'against_sale_amount') {
                            $affiliateCommission->commission_amount = $sale->change_amount * ($affiliate->commission_rate / 100);
                        } else {
                            $affiliateCommission->commission_amount = $sale->profit * ($affiliate->commission_rate / 100);
                        }
                    }

                    $affiliateCommission->save();
                }
            }

            $variants = $request->variants;
            foreach ($variants as $item) {
                $variant = Variation::findOrFail($item['variant_id']);

                // save saleItems
                $saleItem = new SaleItem;
                $saleItem->sale_id = $saleId;
                $saleItem->product_id = $variant->product_id;
                $saleItem->variant_id = $variant->id;
                $saleItem->rate = $item['unit_price'];
                $saleItem->qty = $item['quantity'];
                $saleItem->wa_status = isset($item['wa_duration']) ? 'yes' : 'no';
                // Check if 'wa_duration' exists in the $item array
                $saleItem->wa_duration = isset($item['wa_duration']) ? $item['wa_duration'] : null;
                $saleItem->discount = $item['product_discount'];
                $saleItem->sub_total = $item['total_price'];
                $saleItem->total_purchase_cost = $variant->cost_price * $item['quantity'];
                $saleItem->total_profit = $item['total_price'] - ($variant->cost_price * $item['quantity']);
                $saleItem->sell_type = 'normal sell';
                $saleItem->save();
            }

            return response()->json([
                'status' => 201,
                'message' => 'successfully save draft invoice',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function invoice($id)
    {
        $sale = Sale::findOrFail($id);

        return view('pos.sale.invoice', compact('sale'));
    }

    public function print($id)
    {
        $sale = Sale::findOrFail($id);

        return view('pos.sale.pos-print', compact('sale'));
    }

    public function view()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            // $sales = Sale::latest()->get();
            $sales = Sale::with(['saleItem.product.productUnit'])->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->whereDate('sale_date', now()->toDateString())
                ->get();
        } else {
            $sales = Sale::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', now()->toDateString())
                ->get();
        }

        // $sales = Sale::where('branch_id', Auth::user()->branch_id)->latest()->get();
        return view('pos.sale.view', compact('sales', 'salesInvoice'));
    }

    public function viewAll(Request $request)
    {
        // Fetch sales based on user role
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $sales = Sale::with(['saleItem.product.productUnit', 'customer'])->latest()->get();
        } elseif (Auth::user()->role === 'salesman') {
            $sales = Sale::where('branch_id', Auth::user()->branch_id)
                ->where('sale_by', Auth::user()->id)
                ->with(['saleItem.product.productUnit', 'customer'])
                ->latest()
                ->get();
        } else {
            $sales = Sale::where('branch_id', Auth::user()->branch_id)
                ->with(['saleItem.product.productUnit', 'customer'])
                ->latest()
                ->get();
        }

        // dd($settings);
        // Handle AJAX request for DataTables
        if ($request->ajax()) {

            return DataTables::of($sales)
                ->addColumn('invoice_number', function ($sale) {
                    return '<a href="'.route('sale.invoice', $sale->id).'">
                            #'.($sale->invoice_number ?? 0).'
                        </a>';
                })
                ->addColumn('customer_name', function ($sale) {
                    $customerId = optional($sale->customer)->id;
                    $customerName = optional($sale->customer)->name ?? 'N/A';

                    return '<a href="'.route('customer.profile', $customerId).'">'.$customerName.'</a>';
                })
                ->addColumn('previous_due', function ($sale) {
                    return '৳ '.number_format($sale->receivable - $sale->change_amount, 2);
                })

                ->addColumn('product_returned', function ($sale) {
                    return $sale->returned > 0 ? 'Yes' : 'No';
                })

                ->addColumn('total_purchase_cost', function ($sale) {
                    if (! $sale->total_purchase_cost) {
                        $totalCost = $sale->saleItem->sum('total_purchase_cost');

                        return '৳ '.number_format($totalCost, 2);
                    }

                    return '৳ '.number_format($sale->total_purchase_cost);
                })
                // ->addColumn('profit', function ($sale) {
                //     $totalCost = $sale->saleItem->sum(function ($item) {
                //         return optional($item->product)->cost ?? 0;
                //     });
                //     $totalSale = $sale->saleItem->sum(function ($item) {
                //         return optional($item->product)->price ?? 0;
                //     });
                //     return '৳ ' . number_format($totalSale - $totalCost, 2);
                // })
                ->addColumn('receive_account', function ($sale) {
                    return $sale->accountReceive->name;
                })
                ->addColumn('sale_by', function ($sale) {
                    return $sale->saleBy->name;
                })
                ->addColumn('status', function ($sale) {
                    return $sale->status === 'paid'
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-warning">Unpaid</span>';
                })

                ->addColumn('order_status', function ($sale) {
                    if ($sale->order_status === 'completed') {
                        return '<span class="badge bg-success">Completed</span>';
                    } elseif ($sale->order_status === 'draft') {
                        return '<span class="badge bg-warning">Draft</span>';
                    } elseif ($sale->order_status === 'return') {
                        return '<span class="badge bg-danger">Return</span>';
                    } elseif ($sale->order_status === 'updated') {
                        return '<span class="badge bg-info">Updated</span>';
                    } else {
                        return '<span class="badge bg-secondary">Unknown</span>'; // Jodi kono unexpected value thake
                    }
                })

                ->addColumn('courier_status', function ($sale) {
                    $settings = PosSetting::latest()->first();
                    if ($settings->courier_management === 1) {
                        if ($sale->courier_status === 'not_send') {
                            $sendQuerierButton = '<a title="Send Courier" href="'.route('sale.send.courier', $sale->id).'" class="btn btn-sm btn-primary text-white text-center table-btn courier"><i class="fa-solid fa-paper-plane"></i></a>';

                            return $sendQuerierButton;
                        } elseif ($sale->courier_status === 'send') {
                            return '<span class="badge bg-warning">Send</span>';
                        }
                    }
                })

                ->addColumn('action', function ($sale) {
                    // dd($sale->order_status === 'draft');
                    // $invoiceBtn = '<a title="Invoice" href="' . route('sale.invoice', $sale->id) . '" class="btn btn-sm btn-info text-white table-btn"><i class="fa-solid fa-file-invoice"></i></a>';
                    $returnBtn = '';
                    $paymentBtn = '';
                    $deleteBtn = '';
                    if ($sale->order_status === 'draft') {
                        $invoiceBtn = '';
                        $returnBtn = '';
                        $paymentBtn = '';
                        $deleteBtn = '<a title="Delete" class="btn btn-sm btn-danger text-white table-btn delete_invoice" href="'.route('sale.destroy', $sale->id).'" data-id="'.$sale->id.'"><i class="fa-solid fa-trash-can"></i></a>';
                    } else {
                        $invoiceBtn = '<a title="Invoice" href="'.route('sale.invoice', $sale->id).'" class="btn btn-sm btn-info text-white table-btn"><i class="fa-solid fa-file-invoice"></i></a>';
                        if ($sale->returned <= 0) {
                            $returnBtn = '';
                            if (Auth::user()->can('pos.manage.return')) {
                                $returnBtn = '<a title="Return" href="'.route('return', $sale->id).'" class="btn btn-sm btn-warning text-white table-btn"><i class="fa-solid fa-rotate-left"></i></a>';
                            }
                        }
                        $settings = PosSetting::latest()->first();
                        if ($settings->invoice_payment === 1) {
                            if ($sale->due > 0) {
                                $paymentBtn = '<a title="Payment" class="add_payment btn btn-sm btn-primary text-white table-btn" href="#" data-bs-toggle="modal"
                            data-bs-target="#paymentModal" data-id="'.$sale->id.'"><i class="fa-solid fa-credit-card"></i></a>';
                            }
                        }
                    }
                    $editBtn = '';
                    if (Auth::user()->can('pos-manage.edit')) {
                        $editBtn = '<a title="Edit" href="'.route('sale.edit', $sale->id).'" class="btn btn-sm btn-success text-white table-btn"><i class="fa-solid fa-pen"></i></a>';
                    }
                    $invoiceDuplicateBtn = '<a title="Duplicate Invoice" href="'.route('duplicate.sale.invoice', $sale->id).'" class="btn btn-sm btn-secondary text-white table-btn"><i class="fa-solid fa-copy"></i></a>';

                    return $invoiceBtn.' '.$returnBtn.' '.$paymentBtn.' '.$editBtn.' '.$invoiceDuplicateBtn.' '.$deleteBtn;
                })
                ->rawColumns(['invoice_number', 'customer_name', 'status', 'order_status', 'action', 'courier_status']) // Add all columns with HTML here
                ->make(true); // Finalize DataTable response
        }

        // Fetch sales for the current day (for non-AJAX requests)
        $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
            ->whereDate('sale_date', now()->toDateString())
            ->get();

        return view('pos.sale.view-all', compact('salesInvoice'));
    }

    public function viewDetails($id)
    {
        $sale = Sale::findOrFail($id);

        return view('pos.sale.show', compact('sale'));
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $branch = Branch::findOrFail($sale->branch_id);
        $selectedCustomer = Customer::findOrFail($sale->customer_id);
        $sale_items = SaleItem::where('sale_id', $sale->id)->get();
        $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->latest()->get();
        $products = Product::whereHas('variations', function ($query) {
            $query->where('productStatus', 'active');
        })->get();
        $payments = Bank::where('branch_id', Auth::user()->branch_id)->latest()->get();

        return view('pos.sale.edit', compact('sale', 'branch', 'selectedCustomer', 'products', 'customers', 'sale_items', 'payments'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'variants' => 'required',
                'sale_date' => 'required',
                'payment_method' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            }

            $settings = PosSetting::first();
            // check invoice payment on or off
            $invoice_payment = $settings?->invoice_payment ?? 0;

            // calculate total product cost price
            $productCost = 0;
            $varaints = $request->variants;
            foreach ($varaints as $varaint) {
                $items = Variation::findOrFail($varaint['variant_id']);
                $total = $items->cost * $varaint['quantity'];
                $productCost += $total;
            }

            // Sale Table CRUD
            $sale = Sale::findOrFail($id);
            $saleReceivableDiff = $request->invoice_total - $sale->change_amount;
            //  dd($sale->paid >= $request->paid);
            if ($sale->paid >= $request->paid) {
                $paiddiff = $sale->paid - $request->paid;
            } else {
                $paiddiff = $sale->paid - $request->paid;
                //  dd( $paiddiff);
            }

            // dd( $saleReceivableDiff);
            $sale->sale_date = $request->sale_date;
            $sale->quantity = $request->quantity;
            $sale->total = $request->product_total;
            $sale->change_amount = $request->invoice_total;
            $sale->actual_discount = $request->actual_discount;
            $sale->tax = $request->tax;
            $sale->receivable = $request->grand_total;
            $sale->due = $request->due;
            if ($invoice_payment === 1) {
                if ($request->paid > 0) {
                    if ($request->paid >= $request->invoice_total) {
                        $sale->paid = $request->invoice_total;
                        $sale->status = 'paid';
                    } else {
                        $sale->status = 'partial';
                        $sale->paid = $request->paid;
                    }
                } else {
                    $sale->status = 'unpaid';
                    $sale->paid = 0;
                }
            } else {
                if ($request->paid > 0) {
                    if ($request->paid >= $request->invoice_total) {
                        $sale->status = 'paid';
                    } else {
                        $sale->status = 'partial';
                    }
                } else {
                    $sale->status = 'unpaid';
                }
                $sale->paid = $request->paid;
            }
            $sale->final_receivable = $request->grand_total;
            $sale->payment_method = $request->payment_method;
            $totalSell = $request->total_amount - $request->actual_discount;
            $sale->profit = $totalSell - $productCost;
            $sale->note = $request->note;
            $sale->order_status = 'completed';
            $sale->created_at = Carbon::now();
            $customer = Customer::findOrFail($sale->customer_id);
            // dd( $request->invoice_total);
            $customer->total_receivable += $saleReceivableDiff;
            // dd( $paiddiff);
            if ($invoice_payment === 1) {
                // $customer->total_payable += ($request->paid >= $request->invoice_total) ? $request->invoice_total : $request->paid;
                $customer->total_payable += $paiddiff;
                // $customer->wallet_balance = $customer->total_receivable - $customer->total_payable;
                $customer->wallet_balance = ($customer->wallet_balance + $saleReceivableDiff) - $paiddiff;
            } else {

                if ($sale->paid >= $request->paid) {
                    $customer->total_payable -= $paiddiff;
                    $customer->wallet_balance = ($customer->wallet_balance + $saleReceivableDiff) + $paiddiff;
                } else {
                    // dd($saleReceivableDiff);
                    $customer->total_payable += $paiddiff;
                    $customer->wallet_balance = ($customer->wallet_balance + $saleReceivableDiff) - $paiddiff;
                }

            }

            $sale->save();
            $customer->save();
            // sale items related CRUD
            $allVariants = collect($request->variants);
            $saleItems = SaleItem::where('sale_id', $sale->id)->get();
            // dd($allVariants);

            // Step 1: Delete sale items that are not in the new product list
            $saleItemsToDelete = $saleItems->whereNotIn('variant_id', $allVariants->pluck('variant_id'));
            SaleItem::whereIn('id', $saleItemsToDelete->pluck('id'))->delete();

            // dd($products);

            // Step 2: Process new and existing sale items
            foreach ($allVariants as $item) {
                // $product = Product::findOrFail($item['product_id']);
                $variant = Variation::findOrFail($item['variant_id']);
                $remainingQty = $item['quantity'];
                // $product = Product::finOrFail($variant->product_id);

                $stocks = Stock::where('branch_id', Auth::user()->branch_id)
                    ->where('variation_id', $item['variant_id'])
                    ->orderBy('created_at')
                    ->get();

                // Check if sale item already exists
                $saleItem = SaleItem::where('sale_id', $sale->id)
                    ->where('variant_id', $item['variant_id'])
                    ->first();

                if (! $saleItem) {
                    // Create new sale item
                    $saleItem = new SaleItem;
                    $saleItem->sale_id = $sale->id;
                    $saleItem->product_id = $variant->product->id;
                    $saleItem->variant_id = $item['variant_id'];
                }

                // Update sale item details
                $saleItem->rate = $item['unit_price'];
                $saleItem->qty = $item['quantity'];
                $saleItem->wa_status = isset($item['wa_duration']) ? 'yes' : 'no';
                $saleItem->wa_duration = isset($item['wa_duration']) ? $item['wa_duration'] : null;
                $saleItem->discount = $item['product_discount'];
                $saleItem->sub_total = $item['total_price'];
                $saleItem->total_purchase_cost = $variant->cost * $item['quantity'];
                $saleItem->total_profit = $item['total_price'] - ($variant->cost * $item['quantity']);
                $saleItem->save(); // Save or update the sale item

                // Update or create stock
                foreach ($stocks as $stock) {
                    if ($remainingQty <= 0) {
                        break; // Stop if no more quantity is left to deduct
                    }

                    // Deduct the minimum of remaining quantity and current stock quantity
                    $deductible = min($remainingQty, $stock->stock_quantity);
                    $stock->stock_quantity -= $deductible;
                    $remainingQty -= $deductible;

                    // If the stock is fully used, delete it
                    if ($stock->stock_quantity <= 0) {
                        // dd($stock);
                        $stock->delete();

                        // Set the next stock as the current stock
                        $nextStock = Stock::where('branch_id', Auth::user()->branch_id)
                            ->where('variation_id', $item['variant_id'])
                            ->orderBy('created_at')
                            ->first();

                        // dd($nextStock);
                        if ($nextStock) {
                            $nextStock->is_Current_stock = 1;
                            $nextStock->save();
                        }
                    } else {
                        $stock->save(); // Save the updated stock quantity
                    }
                    $stock->save();
                }

                // If there is still remaining quantity, create a new stock with negative quantity
                if ($remainingQty > 0) {
                    $newStock = new Stock;
                    $newStock->branch_id = Auth::user()->branch_id ?? 1;
                    $newStock->variation_id = $item['variant_id'];
                    $newStock->product_id = $variant->product_id;
                    $newStock->stock_quantity = -$remainingQty;
                    $newStock->status = 'stock_out';
                    $newStock->is_Current_stock = 1; // Mark this as the current stock
                    $newStock->save();
                }
            }

            // ////////////

            // ///////////

            // actual Payment
            $actualPayment = new ActualPayment;
            $actualPayment->branch_id = Auth::user()->branch_id;
            $actualPayment->payment_type = 'receive';
            $actualPayment->payment_method = $request->payment_method;
            $actualPayment->customer_id = $sale->customer_id;
            $actualPayment->amount = $request->paid;
            $actualPayment->date = $request->sale_date;
            $actualPayment->save();

            // accountTransaction table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->reference_id = $sale->id;
            $accountTransaction->purpose = 'Sale';
            $accountTransaction->account_id = $request->payment_method;
            $accountTransaction->credit = $request->paid;
            $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
            // dd($oldBalance);
            $accountTransaction->balance = $oldBalance->balance + $request->paid;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            $transaction = Transaction::where('particulars', 'Sale#'.$id)->latest()->first() ?? new Transaction;

            $transaction->date = $request->sale_date;
            $transaction->processed_by = Auth::user()->id;
            $transaction->payment_type = 'receive';
            $transaction->customer_id = $sale->customer_id;
            $transaction->payment_method = $request->payment_method;
            $transaction->credit = $request->paid;
            $transaction->debit = $invoice_payment == 1 ? $request->invoice_total : $request->grand_total;
            $transaction->balance = $transaction->debit - $request->paid;
            $transaction->branch_id = Auth::user()->branch_id;

            $transaction->save();

            return response()->json([
                'status' => 200,
                'saleId' => $sale->id,
                'message' => 'successfully Updated',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleted Succesfully',
        ]);
    }

    public function filterSaleItem(Request $request)
    {
        // dd($request->all());
        // $saleQuery = Sale::query();
        $saleQuery2 = Sale::with(['saleItem.product.productUnit']);

        // return view('pos.sale.table', compact('sales'))->render();
        // Filter by product_id if provided
        if ($request->product_id != 'Select Product') {
            $saleQuery2->whereHas('saleItem', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            });
        }

        // Filter by customer_id if provided
        if ($request->customer_id != 'Select Customer') {
            $saleQuery2->where('customer_id', $request->customer_id);
        }
        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $saleQuery2->whereBetween('sale_date', [$request->startDate, $request->endDate]);
        }
        if ($request->sale_by_id != 'Select Sales Man') {
            $saleQuery2->where('sale_by', $request->sale_by_id);
        }
        if ($request->account_id != 'Select Account Name') {
            $saleQuery2->where('payment_method', $request->account_id);

        }
        $saleQuery = SaleItem::with(['product', 'variant', 'saleId']);
        // return view('pos.sale.table', compact('sales'))->render();
        // Filter by product_id if provided

        if ($request->product_id != 'Select Product') {
            // $saleQuery->whereHas('saleId', function ($query) use ($request) {
            //     $query->where('product_id', $request->product_id);
            // });
            $saleQuery->where('product_id', $request->product_id);
        }

        // Filter by customer_id if provided
        if ($request->customer_id != 'Select Customer') {
            $saleQuery->whereHas('saleId', function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            });

        }
        if ($request->sale_by_id != 'Select Sales Man') {
            $saleQuery->whereHas('saleId', function ($query) use ($request) {
                $query->where('sale_by', $request->sale_by_id);
            });

        }
        if ($request->account_id != 'Select Account Name') {
            $saleQuery->whereHas('saleId', function ($query) use ($request) {
                $query->where('payment_method', $request->account_id);
            });

        }
        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $saleQuery->whereHas('saleId', function ($query) use ($request) {
                $query->whereBetween('sale_date', [$request->startDate, $request->endDate]);
            });
        }

        // Execute the query
        $saleItem = $saleQuery->get();
        //  dd($saleItem);
        $salesInvoice = $saleQuery2->get();
        $salesTable = view('pos.sale.sale_filter.table', compact('saleItem'))->render();
        $saleInvoiceTable = view('pos.sale.all-invoice-print', compact('salesInvoice'))->render();

        return response()->json([
            'salesTable' => $salesTable,
            'saleInvoiceTable' => $saleInvoiceTable,
        ]);
    }

    public function find($id)
    {
        // dd($id);
        // $purchaseId = 'Purchase#' + $id;
        $sale = Sale::findOrFail($id);

        return response()->json([
            'status' => 200,
            'data' => $sale,
        ]);
    }

    public function saleTransaction(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'transaction_account' => 'required',
            'amount' => 'required',
        ]);

        $validator->after(function ($validator) use ($id, $request) {
            $sale = Sale::findOrFail($id);
            if ($request->amount > $sale->due) {
                $validator->errors()->add('amount', 'The amount cannot be greater than the due amount.');
            }
        });
        if ($validator->passes()) {
            $sales = Sale::all();
            $sale = Sale::findOrFail($id);
            $sale->paid = $sale->paid + $request->amount;
            $sale->due = $sale->due - $request->amount;
            $sale->status = 'paid';
            $sale->save();

            $customer = Customer::findOrFail($sale->customer_id);
            $customer->total_payable = $customer->total_payable + $request->amount;
            $customer->wallet_balance = $customer->wallet_balance - $request->amount;
            $customer->save();

            // accountTransaction table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'Sale';
            $accountTransaction->reference_id = $id;
            $accountTransaction->account_id = $request->transaction_account;
            $accountTransaction->credit = $request->amount;
            $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
            $accountTransaction->balance = $oldBalance->balance + $request->amount;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = $request->payment_date;
            $transaction->payment_type = 'receive';
            $transaction->particulars = 'Sale#'.$id;
            $transaction->customer_id = $customer->id;
            // $transaction->debit = $transaction->debit + $request->amount;
            $transaction->credit = $transaction->credit + $request->amount;
            $transaction->balance = $transaction->debit - $transaction->credit;
            $transaction->payment_method = $request->transaction_account;
            $transaction->save();

            // return view('pos.sale.table', compact('sales'))->render();

            return response()->json([
                'status' => 200,
                'message' => 'Payment Successful',
                'sales' => $sales,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->errors(),
            ]);
        }
    }

    public function findQty($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }

    public function saleCustomer($id)
    {

        $status = 'active';
        $customer = Customer::findOrFail($id);
        $promotionDetails = PromotionDetails::whereHas('promotion', function ($query) use ($status) {
            return $query->where('status', '=', $status);
        })->where('promotion_type', 'customers')->where('logic', 'like', '%'.$id.'%')->get();
        $promotions = [];
        foreach ($promotionDetails as $promo) {
            $promotions[] = $promo->promotion;
        }
        // dd($promotion);
        if ($promotions) {
            return response()->json([
                'status' => '200',
                'data' => $customer,
                'promotions' => $promotions,
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'data' => $customer,
            ]);
        }
    }

    public function salePromotions($id)
    {
        $promotions = Promotion::findOrFail($id);

        return response()->json([
            'status' => '200',
            'promotions' => $promotions,
        ]);
    }

    public function findProductWithBarcode(Request $request, $id)
    {
        // dd($request->selectedCustomerId);
        $customerId = $request->selectedCustomerId;

        $variant = Variation::where('barcode', $id)->with('product.productUnit', 'variationSize', 'colorName')->latest()->first();
        $saleItemsPrice = SaleItem::where('variant_id', $variant->id)
            ->whereHas('saleId', function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            })
            ->latest()
            ->take(5)
            ->get(['id', 'sale_id', 'product_id', 'variant_id', 'rate']);
        // dd($saleItemsPrice);
        if ($variant) {
            return response()->json([
                'status' => 200,
                'variant' => $variant,
                'saleItemsPrice' => $saleItemsPrice,
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => 'Product Not Available',
            ]);
        }
    }

    // public function saleProductFind($id)
    // {
    //     // $status = 'active';
    //     $saleItems = SaleItem::where('sale_id', $id)->with('variant.variationSize', 'product.stockQuantity')->get();

    //     // $items = $saleItems->map(function ($saleItem) use ($status) {
    //     //     $product = Product::find($saleItem->product_id);
    //     //     $promotionDetails = PromotionDetails::whereHas('promotion', function ($query) use ($status) {
    //     //         return $query->where('status', '=', $status);
    //     //     })->where('promotion_type', 'products')->where('logic', 'like', '%' . $product->id . "%")->latest()->first();
    //     //     // dd($saleItem->qty);
    //     //     return [
    //     //         'product' => $product,
    //     //         'promotion' => $promotionDetails ? $promotionDetails->promotion : null,
    //     //         'quantity' => $saleItem->qty,
    //     //     ];
    //     // });

    //     return response()->json([
    //         'status' => '200',
    //         'saleItems' => $saleItems
    //     ]);
    // }
    public function saleProductFind($id)
    {
        try {
            // Attempt to fetch sale items with related data
            $saleItems = SaleItem::where('sale_id', $id)
                ->with('variant.variationSize', 'variant.product', 'product.stockQuantity', 'variant.colorName', 'variant.stocks')
                ->get();

            // Return a successful response with the sale items
            return response()->json([
                'status' => '200',
                'saleItems' => $saleItems,
            ]);
        } catch (\Exception $e) {
            // Log the error (optional)
            Log::error('Error fetching sale items: '.$e->getMessage());

            // Return an error response
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching sale items.',
                'error' => $e->getMessage(), // Only include this in development for security reasons
            ]);
        }
    }

    public function saleCustomerDue($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'status' => '200',
            'customer' => $customer,
        ]);
    }

    // $products  = Product::where('branch_id', Auth::user()->branch_id)
    // ->withSum('stockQuantity', 'stock_quantity')
    // // ->having('stock_quantity_sum_stock_quantity', '>', 0)
    // ->orderBy('stock_quantity_sum_stock_quantity', 'asc')
    // ->get();

    public function saleViewProduct()
    {
        $products = Product::withSum(['stockQuantity as stock_quantity_sum' => function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        }], 'stock_quantity')
            ->with('defaultVariations')
            // New Added This line for status
            ->whereHas('variations', function ($query) {
                $query->where('productStatus', 'active');
            })
            ->orderBy('stock_quantity_sum', 'asc')
            ->get();
        $userRole = Auth::user()->role;

        return response()->json([
            'status' => '200',
            'products' => $products,
            'user_role' => $userRole,
        ]);
    }

    public function saleViaProductAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'price' => 'required',
            'cost' => 'required',
            'stock' => 'required',
            'transaction_account' => 'required',
            'via_supplier_name' => 'required',
            'color' => 'nullable', // Initially nullable
            'size' => 'nullable',  // Initially nullable
            'category_id' => 'nullable',  // Initially nullable
        ], [
            'color.required_without' => 'At least one of color or size is required',
            'size.required_without' => 'At least one of color or size is required',
            'category_id.required' => 'Category is required',
        ]);

        // Apply conditional validation for category_id when name is non-numeric
        $validator->sometimes('category_id', 'required', function ($input) {
            return ! is_numeric($input->name);
        });

        // Apply conditional validation for color and size when name is non-numeric
        $validator->sometimes(['color', 'size'], 'required_without_all:color,size', function ($input) {
            return ! is_numeric($input->name);
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }

        $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();

        // Balance Check for Payment
        if ($request->via_paid > 0 && (! $oldBalance || $oldBalance->balance < $request->via_paid)) {
            return response()->json([
                'status' => 400,
                'message' => 'Not Enough Balance in Account. Please choose Another Account or Deposit Account Balance',
            ]);
        }

        $settings = PosSetting::first();

        if (! is_numeric($request->name)) {
            // $maxBarcode = Variation::where('branch_id', Auth::user()->branch_id)->max('barcode');
            $product = new Product;
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->subcategory_id = null;
            $product->brand_id = null;
            $product->product_type = 'via_goods';

            $product->unit = 1;
            $product->save();

            $variant = new Variation;
            $variant->product_id = $product->id;
            $variant->barcode = rand(000000000, 999999999);
            $variant->cost_price = $request->cost;
            if ($settings->sale_price_type === 'b2b_price') {
                $variant->b2b_price = $request->price;
            }
            $variant->b2c_price = $request->price;
            $variant->size = $request->size;
            $variant->color = $request->color;
            $variant->status = 'default';
            $variant->save();

            $stock = new Stock;
            $stock->branch_id = Auth::user()->branch_id;
            $stock->barcode = rand(000000000, 999999999);
            $stock->product_id = $product->id;
            $stock->variation_id = $variant->id;
            $stock->stock_quantity = $request->stock;
            $stock->is_Current_stock = true;
            $stock->save();
        } else {
            $variant = Variation::findOrFail($request->name);
        }

        // Via Sale Stock
        $prevStock = Stock::where('variation_id', $variant->id)->where('branch_id', Auth::user()->branch_id)->get();
        $stock = new Stock;
        $stock->branch_id = Auth::user()->branch_id;
        $stock->barcode = rand(000000000, 999999999);
        $stock->product_id = $variant->product_id;
        $stock->variation_id = $variant->id;
        $stock->stock_quantity = $request->stock;
        if ($prevStock) {
            $stock->is_Current_stock = false;
        }
        $stock->is_Current_stock = true;
        $stock->save();

        $viaSale = new ViaSale;
        $viaSale->invoice_date = Carbon::now();
        $viaSale->branch_id = Auth::user()->branch_id;
        $viaSale->invoice_number = $request->invoice_number;
        $viaSale->supplier_name = $request->via_supplier_name;
        $viaSale->product_id = $variant->product_id;
        $viaSale->variant_id = $variant->id;
        $viaSale->product_name = $request->name;
        $viaSale->quantity = $request->stock;
        $viaSale->cost_price = $request->cost;
        $viaSale->sale_price = $request->price;
        $viaSale->sub_total = $request->via_total_pay;
        if ($request->via_paid == null) {
            $viaSale->paid = 0;
            $viaSale->due = $request->via_total_pay;
        } else {
            $viaSale->paid = $request->via_paid;
            $viaSale->due = $request->via_due;
        }
        if ($request->via_paid >= $request->via_total_pay) {
            $viaSale->status = 1;
        }
        $viaSale->save();

        $supplier = Customer::findOrFail($request->via_supplier_name);
        $supplier->total_payable += $request->via_total_pay;
        $supplier->total_receivable += $request->via_paid;
        $supplier->wallet_balance += ($request->via_total_pay - $request->via_paid);
        $supplier->save();

        if ($request->via_paid > 0) {
            // account Transaction crud
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'Via Purchase';
            $accountTransaction->reference_id = $viaSale->id;
            $accountTransaction->account_id = $request->transaction_account;
            $accountTransaction->debit = $request->via_paid;
            $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
            $accountTransaction->balance = $oldBalance->balance - $request->via_paid;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            // transaction CRUD is here
            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = Carbon::now();
            $transaction->payment_type = 'pay';
            $transaction->particulars = 'ViaSale#'.$viaSale->id;
            $transaction->supplier_id = $request->via_supplier_name;
            $transaction->credit = $request->via_paid;
            $transaction->debit = $request->via_total_pay;
            $transaction->balance = $request->via_total_pay - $request->via_paid;
            $transaction->payment_method = $request->transaction_account;
            $transaction->save();
        }
        $variantInfo = Variation::with('stocks', 'product', 'variationSize')->findOrFail($variant->id);

        return response()->json([
            'status' => 200,
            'products' => $variantInfo,
            'quantity' => $request->stock,
            // 'totalStock' =>$totalStock,
            'message' => 'Via Product Save Successfully',
        ]);
    }

    public function duplicateSaleInvoice($id)
    {
        $sale = Sale::findOrFail($id);
        $branch = Branch::findOrFail($sale->branch_id);
        $selectedCustomer = Customer::findOrFail($sale->customer_id);
        $sale_items = SaleItem::where('sale_id', $sale->id)->get();
        $customers = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->latest()->get();
        $products = Product::whereHas('variations', function ($query) {
            $query->where('productStatus', 'active');
        })->get();
        $payments = Bank::where('branch_id', Auth::user()->branch_id)->latest()->get();

        return view('pos.sale.duplicateSaleInvoice.duplicateInvoice', compact('sale', 'branch', 'selectedCustomer', 'products', 'customers', 'sale_items', 'payments'));
    }

    public function sendQuerier($id)
    {

        $sale = Sale::findOrFail($id);
        // dd($sale);
        $sale->courier_status = 'send';
        $sale->save();
        $assignCourier = new CouerierOrder;
        $assignCourier->sale_id = $id;
        $assignCourier->branch_id = Auth::user()->branch_id;
        $assignCourier->save();

        return redirect()->back()->with('success', 'Courier Assigned Successfully');
    }

    public function SaleInvoiceFilter()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            // $sales = Sale::latest()->get();
            // $sales = Sale::with(['saleItem.product.productUnit'])->latest()->get();
            $saleItem = SaleItem::with(['product', 'variant', 'saleId'])->latest()->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->whereDate('sale_date', now()->toDateString())
                ->get();
            $banks = Bank::All();
        } else {
            // $sales = Sale::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $saleItem = SaleItem::where('branch_id', Auth::user()->branch_id)->with(['product', 'variant', 'saleId'])->latest()->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', now()->toDateString())
                ->get();
            $banks = Bank::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.sale.sale_filter.sale_filter', compact('salesInvoice', 'banks', 'saleItem'));
    }

    public function SaleMainInvoiceFilter()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            // $sales = Sale::latest()->get();
            $sales = Sale::with(['saleItem.product.productUnit'])->latest()->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->whereDate('sale_date', now()->toDateString())
                ->get();
            $banks = Bank::All();
        } else {
            $sales = Sale::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $salesInvoice = Sale::with(['saleItem.product.productUnit', 'customer', 'saleBy'])
                ->where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', now()->toDateString())
                ->get();
            $banks = Bank::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.sale.sale_filter.sale_main_filter', compact('sales', 'salesInvoice', 'banks'));
    }

    public function filterSale(Request $request)
    {
        // dd($request->all());
        // $saleQuery = Sale::query();
        $saleQuery = Sale::with(['saleItem.product.productUnit']);

        // return view('pos.sale.table', compact('sales'))->render();
        // Filter by product_id if provided
        if ($request->product_id != 'Select Product') {
            $saleQuery->whereHas('saleItem', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            });
        }

        // Filter by customer_id if provided
        if ($request->customer_id != 'Select Customer') {
            $saleQuery->where('customer_id', $request->customer_id);
        }
        if ($request->sale_by_id != 'Select Sales Man') {
            $saleQuery->where('sale_by', $request->sale_by_id);
        }
        if ($request->account_id != 'Select Account Name') {
            $saleQuery->where('payment_method', $request->account_id);
        }
        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $saleQuery->whereBetween('sale_date', [$request->startDate, $request->endDate]);
        }

        // Execute the query
        $sales = $saleQuery->get();
        $salesInvoice = $saleQuery->get();
        $salesTable = view('pos.sale.sale_filter.sale_main_filter_table', compact('sales'))->render();
        $saleInvoiceTable = view('pos.sale.all-invoice-print', compact('salesInvoice'))->render();

        return response()->json([
            'salesTable' => $salesTable,
            'saleInvoiceTable' => $saleInvoiceTable,
        ]);
    }

    public function salePharmacy()
    {
        return view('pos.sale.sale_pharmacy');
    }

    public function viewProducts()
    {
        $variations = Variation::with('stocks', 'product', 'variationSize', 'colorName')->get();

        return response()->json([
            'status' => 200,
            'variations' => $variations,
            'message' => ' Sent Successfully',
        ]);
    }
}
