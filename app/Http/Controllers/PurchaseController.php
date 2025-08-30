<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\ActualPayment;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\PosSetting;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseCostDetails;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Variation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index()
    {
        $category = Category::where('slug', 'via-sell')->first();
        $products = collect();

        if ($category) {
            $products = Product::withSum('stockQuantity', 'stock_quantity') // Sum the stock_quantity
                ->where('category_id', '!=', $category->id)
                ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // Explicitly reference the stock_quantity_sum
                ->get();
        } else {
            $products = Product::withSum('stockQuantity', 'stock_quantity')
                ->orderBy('stock_quantity_sum_stock_quantity', 'asc')
                ->get();
        }
        $branchId = Auth::user()->branch_id;
        $products = $products->map(function ($product) use ($branchId) {
            $branchStockQuantity = $product->stockQuantity->where('branch_id', $branchId)->sum('stock_quantity');

            return $product->setAttribute('branch_stock_quantity', $branchStockQuantity);
        });

        return view('pos.purchase.purchase', compact('products'));
    }

    public function purchase2()
    {
        $category = Category::where('slug', 'via-sell')->first();
        $products = collect();

        if ($category) {
            $products = Product::withSum('stockQuantity', 'stock_quantity') // Sum the stock_quantity
                ->where('category_id', '!=', $category->id)
                ->orderBy('stock_quantity_sum_stock_quantity', 'asc') // Explicitly reference the stock_quantity_sum
                ->get();
        } else {
            $products = Product::withSum('stockQuantity', 'stock_quantity')
                ->orderBy('stock_quantity_sum_stock_quantity', 'asc')
                ->get();
        }
        $branchId = Auth::user()->branch_id;
        $products = $products->map(function ($product) use ($branchId) {
            $branchStockQuantity = $product->stockQuantity->where('branch_id', $branchId)->sum('stock_quantity');

            return $product->setAttribute('branch_stock_quantity', $branchStockQuantity);
        });

        return view('pos.purchase.purchase2', compact('products'));
    }

    public function store(Request $request)
    {
        // // dd($request->all());
        // try {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required',
            'date' => 'required',
            'payment_method' => 'required',
            'document' => 'file|mimes:jpg,pdf,png,svg,webp,jpeg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->messages()]);
        }

        $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
        if ($request->total_payable > 0) {
            // Check if $oldBalance exists and has enough balance

            if (! $oldBalance || $oldBalance->balance < $request->total_payable) {
                return response()->json(['status' => 400, 'message' => 'Not enough balance in this account.']);
            }
        }

        $settings = PosSetting::first();
        // check invoice payment on or off
        $invoice_payment = $settings?->invoice_payment ?? 0;

        // $old_balance = $oldBalance->balance ?? 0;
        // dd($request->total_payable);

        // Continue with the rest of the logic

        // Calculate total quantity and amount
        $totalQty = array_sum($request->quantity);
        // $totalAmount = array_sum(array_map(fn($qty, $price) => $qty * $price, $request->quantity, $request->cost_price));

        // Format purchase date
        $purchaseDate = Carbon::createFromFormat('d-M-Y', $request->date)->format('Y-m-d');

        // $total Cost Price
        $total_cost_price = $request->extra_cost_total ?? ($request->carrying_cost ?? 0);

        // Create purchase
        $purchase = Purchase::create([
            'branch_id' => Auth::user()->branch_id,
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $purchaseDate,
            'total_quantity' => $totalQty,
            'total_amount' => $request->total,
            'invoice' => $request->invoice ?? $this->generateUniqueInvoice(),
            'discount_type' => $request->discount_type,
            'discount_amount' => $request->discount_amount,
            'sub_total' => $request->sub_total - $total_cost_price,
            'grand_total' => $request->grand_total,
            'paid' => $request->total_payable ?? 0,
            'due' => max(0, ($request->grand_total - ($request->total_payable ?? 0))),
            'total_purchase_cost' => $total_cost_price,
            'payment_method' => $request->payment_method,
            'order_status' => $request->order_status ?? 'completed',
            'payment_status' => $request->total_payable > 0 ? ($request->total_payable >= $request->sub_total ? 'paid' : 'partial') : 'unpaid',
            'note' => $request->note,
            'purchase_by' => Auth::user()->id,
            'document' => $request->document ? $this->uploadDocument($request->document) : null,
        ]);

        // Save purchase items and update stock
        foreach ($request->variant_id as $index => $variantId) {
            $variant = Variation::findOrFail($variantId);
            if ($settings->purchase_price_update == 1) {
                $variant->cost_price = $request->cost_price[$index];
                $variant->save();
            }
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $variant->product->id,
                'variant_id' => $variantId,
                'unit_price' => $request->cost_price[$index],
                'quantity' => $request->quantity[$index],
                'total_price' => $request->cost_price[$index] * $request->quantity[$index],
                'discount' => $request->variant_discount[$index] ?? 0,
            ]);

            // dd($request->all());
            $previousStock = Stock::where('variation_id', $variantId)->get();

            $stock = new Stock;
            $stock->branch_id = Auth::user()->branch_id;
            $stock->product_id = $variant->product->id;
            $stock->variation_id = $variant->id;
            $stock->stock_quantity = $request->quantity[$index];
            $stock->stock_age = Carbon::now()->toDateString();
            $stock->manufacture_date = $request->manufacture_date[$index] ?? null;
            $stock->expiry_date = $request->expiry_date[$index] ?? null;
            $stock->status = 'available';
            if ($previousStock->count() > 0) {
                $stock->is_Current_stock = false;
            } else {
                $stock->is_Current_stock = true;
            }
            $stock->save();
        }

        if ($request->purpose) {
            foreach ($request->purpose as $index => $purpose) {
                PurchaseCostDetails::create([
                    'purchase_id' => $purchase->id,
                    'purpose' => $purpose,
                    'amount' => $request->amount[$index],
                ]);
            }
        }

        // Create actual payment
        ActualPayment::create([
            'branch_id' => Auth::user()->branch_id,
            'payment_type' => 'pay',
            'payment_method' => $request->payment_method,
            'supplier_id' => $request->supplier_id,
            'amount' => $request->total_payable ?? 0,
            'date' => $purchaseDate,
        ]);

        // Update account transaction
        AccountTransaction::create([
            'branch_id' => Auth::user()->branch_id,
            'reference_id' => $purchase->id,
            'account_id' => $request->payment_method,
            'purpose' => 'Purchase',
            'debit' => $request->total_payable ?? 0,
            'balance' => $oldBalance->balance - ($request->total_payable ?? 0),
            'processed_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        // Create expense if carrying cost exists
        if ($purchase->total_purchase_cost > 0) {
            Expense::create([
                'branch_id' => Auth::user()->branch_id,
                'purpose' => 'Purchase'.$purchase->id,
                'expense_date' => now()->toDateString(),
                'amount' => $purchase->total_purchase_cost,
                'spender' => Auth::user()->name,
                'bank_account_id' => $request->payment_method,
            ]);
        }

        // Create transaction
        Transaction::create([
            'branch_id' => Auth::user()->branch_id,
            'date' => $purchaseDate,
            'payment_type' => 'pay',
            'particulars' => 'Purchase#'.$purchase->id,
            'supplier_id' => $request->supplier_id,
            'payment_method' => $request->payment_method,
            'debit' => $request->total_payable ?? 0,
            'credit' => $request->grand_total ?? 0,
            'balance' => ($request->total_payable ?? 0) - ($request->grand_total ?? 0),
        ]);

        // Update supplier
        $supplier = Customer::findOrFail($request->supplier_id);
        $supplier->total_payable += ($request->sub_total - $total_cost_price);
        if ($invoice_payment === 1) {
            $supplier->total_receivable += ($request->total_payable - $total_cost_price) >= ($request->sub_total - $total_cost_price) ? ($request->sub_total - $total_cost_price) : ($request->total_payable - $total_cost_price);
            $supplier->wallet_balance = $supplier->total_receivable - $supplier->total_payable;
        } else {
            $supplier->total_receivable += ($request->total_payable - $total_cost_price);
            $supplier->wallet_balance = ($request->sub_total - $supplier->total_payable) - $total_cost_price;
        }
        $supplier->update();

        // $supplier->update([
        //     'total_receivable' => $supplier->total_receivable + ($request->total_payable - $total_cost_price),
        //     'total_payable' => $supplier->total_payable + ($request->sub_total - $total_cost_price),
        //     // 'wallet_balance' => $supplier->wallet_balance + ($request->sub_total - $request->total_payable),
        //     'wallet_balance' => $supplier->wallet_balance - ($request->sub_total - $request->total_payable),
        // ]);

        return response()->json([
            'status' => 200,
            'purchaseId' => $purchase->id,
            'message' => 'Successfully saved',
        ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'An error occurred: ' . $e->getMessage(),
        //     ]);
        // }
    }

    public function draftInvoice(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'supplier_id' => 'required',
                'date' => 'required',

                'document' => 'file|mimes:jpg,pdf,png,svg,webp,jpeg,gif|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'error' => $validator->messages()]);
            }

            // Calculate total quantity and amount
            $totalQty = array_sum($request->quantity);
            $totalAmount = array_sum(array_map(fn ($qty, $price) => $qty * $price, $request->quantity, $request->cost_price));

            // Format purchase date
            $purchaseDate = Carbon::createFromFormat('d-M-Y', $request->date)->format('Y-m-d');

            // $total Cost Price
            $total_cost_price = $request->extra_cost_total ?? ($request->carrying_cost ?? 0);

            // Create purchase
            $purchase = Purchase::create([
                'branch_id' => Auth::user()->branch_id,
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $purchaseDate,
                'total_quantity' => $totalQty,
                'total_amount' => $totalAmount,
                'invoice' => $request->invoice ?? $this->generateUniqueInvoice(),
                'discount_amount' => $request->discount_amount,
                'sub_total' => $request->sub_total - $total_cost_price,
                'grand_total' => $request->grand_total,
                'paid' => $request->total_payable ?? 0,
                'due' => max(0, ($request->grand_total - ($request->total_payable ?? 0))),
                'total_purchase_cost' => $total_cost_price,
                'payment_method' => $request->payment_method,
                'order_status' => 'draft',
                'payment_status' => 'unpaid',
                'note' => $request->note,
                'document' => $request->document ? $this->uploadDocument($request->document) : null,
            ]);

            // Save purchase items and update stock
            foreach ($request->variant_id as $index => $variantId) {
                $variant = Variation::findOrFail($variantId);
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $variant->product->id,
                    'variant_id' => $variantId,
                    'unit_price' => $request->cost_price[$index],
                    'quantity' => $request->quantity[$index],
                    'total_price' => $request->cost_price[$index] * $request->quantity[$index],
                ]);
                // $previousStock = Stock::where('variation_id', $variantId)->get();
                // $stock = new Stock;
                // $stock->branch_id = Auth::user()->branch_id;
                // $stock->product_id = $variant->product->id;
                // $stock->variation_id = $variant->id;
                // $stock->stock_quantity = $request->quantity[$index];
                // $stock->status = 'available';
                // if ($previousStock->count() > 0) {
                //     $stock->is_Current_stock = false;
                // } else {
                //     $stock->is_Current_stock = true;
                // }
                // $stock->save();
            }

            if ($request->purpose) {
                foreach ($request->purpose as $index => $purpose) {
                    PurchaseCostDetails::create([
                        'purchase_id' => $purchase->id,
                        'purpose' => $purpose,
                        'amount' => $request->amount[$index],
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'purchaseId' => $purchase->id,
                'message' => 'Successfully saved',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: '.$e->getMessage(),
            ]);
        }
    }

    // Helper function to generate unique invoice
    private function generateUniqueInvoice()
    {
        do {
            $invoice = rand(123456, 999999);
        } while (Purchase::where('invoice', $invoice)->exists());

        return $invoice;
    }

    // Helper function to upload document
    private function uploadDocument($document)
    {
        $docName = rand().'.'.$document->getClientOriginalExtension();
        $document->move(public_path('uploads/purchase/'), $docName);

        return $docName;
    }

    // invoice function
    public function invoice($id)
    {
        $purchase = Purchase::findOrFail($id);

        return view('pos.purchase.invoice', compact('purchase'));
    }

    // Money Receipt
    public function moneyReceipt($id)
    {
        $purchase = Purchase::findOrFail($id);

        return view('pos.purchase.receipt', compact('purchase'));
    }

    // view Function
    public function view()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $purchase = Purchase::latest()->get();
            $purchaseInvoice = Purchase::with(['purchaseItem.product.productUnit'])
                ->whereDate('purchase_date', now()->toDateString())
                ->get();
        } else {
            $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $purchaseInvoice = Purchase::with(['purchaseItem.product.productUnit'])
                ->where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', now()->toDateString())
                ->get();
        }

        // return view('pos.purchase.view');
        return view('pos.purchase.view', compact('purchase', 'purchaseInvoice'));
    }

    public function viewAll(Request $request)
    {
        // Fetch sales based on user role
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $purchases = Purchase::with('purchaseItem.product.productUnit')->latest()->get();
            $purchaseInvoice = Purchase::with(['purchaseItem.product.productUnit'])
                ->whereDate('purchase_date', now()->toDateString())
                ->get();
        } else {
            $purchases = Purchase::where('branch_id', Auth::user()->branch_id)->with('purchaseItem.product.productUnit')->latest()->get();
            $purchaseInvoice = Purchase::with(['purchaseItem.product.productUnit'])
                ->where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', now()->toDateString())
                ->get();
        }
        $settings = PosSetting::first();

        // Handle AJAX request for DataTables
        if ($request->ajax()) {
            return DataTables::of($purchases)
                ->addColumn('invoice_number', function ($purchase) {
                    return '<a href="'.route('purchase.invoice', $purchase->id).'">
                            #'.($purchase->invoice ?? 0).'
                        </a>';
                })
                ->addColumn('supplier_name', function ($purchase) {
                    $supplierId = optional($purchase->supplier)->id; // Use optional() here
                    $supplierName = optional($purchase->supplier)->name ?? 'N/A'; // Use optional() here

                    return '<a href="'.route('customer.profile', $supplierId).'">'.$supplierName.'</a>';
                })
                ->addColumn('pruchase_by_name', function ($purchase) {
                    $purchaseName = optional($purchase->purchaseBy)->name ?? 'N/A'; // Use optional() Here//

                    return $purchaseName;
                })
                ->addColumn('items', function ($purchase) {
                    $totalItems = $purchase->purchaseItem->count();
                    $displayItems = $purchase->purchaseItem->take(5);
                    $remainingItems = $totalItems - 5;

                    $itemsHtml = '<ul>';
                    foreach ($displayItems as $item) {
                        $itemsHtml .= '<li>'.($item->product->name ?? '').'</li>';
                    }
                    if ($totalItems > 5) {
                        $itemsHtml .= '<li>and more '.$remainingItems.'...</li>';
                    }
                    $itemsHtml .= '</ul>';

                    return $itemsHtml;
                })
                ->addColumn('payment_status', function ($purchase) {
                    if ($purchase->payment_status === 'paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    } elseif ($purchase->payment_status === 'partial') {
                        return '<span class="badge bg-info">Partial</span>';
                    } else {
                        return '<span class="badge bg-warning">Unpaid</span>';
                    }
                })
                ->addColumn('order_status', function ($purchase) {
                    if ($purchase->order_status === 'completed') {
                        return '<span class="badge bg-success">Completed</span>';
                    } elseif ($purchase->order_status === 'draft') {
                        return '<span class="badge bg-warning">Draft</span>';
                    } elseif ($purchase->order_status === 'return') {
                        return '<span class="badge bg-danger">Return</span>';
                    } elseif ($purchase->order_status === 'updated') {
                        return '<span class="badge bg-info">Updated</span>';
                    } elseif ($purchase->order_status === 'pre_order') {
                        return '<span class="badge bg-primary">Pre Order</span>';
                    } else {
                        return '<span class="badge bg-secondary">Unknown</span>';
                    }
                })
                ->addColumn('action', function ($purchase) {
                    // $returnBtn = '';
                    $paymentBtn = '';
                    $deleteBtn = '';
                    $moneyReceiptBtn = '';
                    if ($purchase->order_status === 'draft') {
                        $invoiceBtn = '';
                        // $returnBtn = '';
                        $paymentBtn = '';
                        $moneyReceiptBtn = '';
                        $deleteBtn = '<a title="Delete" class="btn btn-sm btn-danger text-white table-btn delete_invoice" href="'.route('purchase.destroy', $purchase->id).'" data-id="'.$purchase->id.'"><i class="fa-solid fa-trash-can"></i></a>';
                    } else {
                        $invoiceBtn = '<a title="Invoice" href="'.route('purchase.invoice', $purchase->id).'" class="btn btn-sm btn-info text-white table-btn"><i class="fa-solid fa-file-invoice"></i></a>';
                        // if ($purchase->returned <= 0) {
                        //     $returnBtn = '<a title="Return" href="' . route('return', $purchase->id) . '" class="btn btn-sm btn-warning text-white table-btn"><i class="fa-solid fa-rotate-left"></i></a>';
                        // }
                        if ($purchase->due > 0) {
                            $paymentBtn = '<a title="Payment" class="add_payment btn btn-sm btn-primary text-white table-btn" href="#" data-bs-toggle="modal"
                            data-bs-target="#paymentModal" data-id="'.$purchase->id.'"><i class="fa-solid fa-credit-card"></i></a>';
                        }

                        // Money Receipt বাটন যোগ করুন
                        if ($purchase->document) {
                            $moneyReceiptBtn = '<a title="Money Receipt" class="btn btn-sm btn-success text-white table-btn" href="'.route('purchase.money.receipt', $purchase->id).'"><i class="fa-solid fa-receipt"></i></a>';
                        }
                    }
                    $editBtn = '<a title="Edit" href="'.route('purchase.edit', $purchase->id).'" class="btn btn-sm btn-success text-white table-btn"><i class="fa-solid fa-pen"></i></a>';
                    $invoiceDuplicateBtn = '<a title="Duplicate Invoice" href="'.route('duplicate.purchase.invoice', $purchase->id).'" class="btn btn-sm btn-secondary text-white table-btn"><i class="fa-solid fa-copy"></i></a>';

                    return $invoiceBtn.' '.$paymentBtn.' '.$deleteBtn.' '.$moneyReceiptBtn.' '.$editBtn;
                })
                ->rawColumns(['invoice_number', 'supplier_name', 'items', 'payment_status', 'action', 'order_status', 'editBtn']) // Add all columns with HTML here
                ->make(true); // Finalize DataTable response
        }

        return view('pos.purchase.view-all', compact('purchaseInvoice'));
    }

    // supplierName function
    public function supplierName($id)
    {
        $supplier = Customer::findOrFail($id);

        return response()->json([
            'status' => 200,
            'supplier' => $supplier,
        ]);
    }

    // edit function
    public function edit($id)
    {

        $purchase = Purchase::findOrFail($id);
        $branch = Branch::findOrFail($purchase->branch_id)->first();

        $selectedSupplier = Customer::findOrFail($purchase->supplier_id)->first();

        $suppliers = Customer::where('party_type', 'supplier')->get();

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $products = Product::get();
        } else {
            $products = Product::where('branch_id', Auth::user()->branch_id)
                // ->orderBy('stock', 'asc')
                ->get();
        }

        return view('pos.purchase.edit_copy', compact('purchase', 'branch', 'selectedSupplier', 'suppliers', 'products'));
    }

    public function findPurchase($id)
    {
        // $purchase = Purchase::findOrFail($id);
        $purchase = Purchase::with('purchaseItem.variant.variationSize', 'purchaseItem.variant.colorName', 'purchaseItem.product.productUnit')->findOrFail($id);

        // dd($purchase);
        return response()->json([
            'status' => 200,
            'data' => $purchase,
        ]);
    }

    public function purchaseTransaction(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'transaction_account' => 'required',
            'amount' => 'required',
        ]);

        $validator->after(function ($validator) use ($id, $request) {
            $purchase = Purchase::findOrFail($id);
            if ($request->amount > $purchase->due) {
                $validator->errors()->add('amount', 'The amount cannot be greater than the due amount.');
            }
        });
        if ($validator->passes()) {
            $purchase = purchase::findOrFail($id);
            $purchase->paid = $purchase->paid + $request->amount;
            $purchase->due = $purchase->due - $request->amount;
            $purchase->payment_status = 'paid';
            $purchase->save();

            $supplier = Customer::findOrFail($purchase->supplier_id);
            $supplier->total_receivable = $supplier->total_receivable + $request->amount;
            $supplier->wallet_balance = $supplier->wallet_balance - $request->amount;
            $supplier->save();

            // accountTransaction table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'Purchase';
            $accountTransaction->reference_id = $id;
            $accountTransaction->account_id = $request->transaction_account;
            $accountTransaction->debit = $request->amount;
            $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
            $accountTransaction->balance = $oldBalance->balance - $request->amount;
            $accountTransaction->processed_by = Auth::user()->id;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = $request->payment_date;
            $transaction->payment_type = 'pay';
            $transaction->particulars = 'Purchase#'.$id;
            $transaction->supplier_id = $supplier->id;
            $transaction->debit = $transaction->debit + $request->amount;
            $transaction->balance = $transaction->debit - $transaction->credit;
            $transaction->payment_method = $request->transaction_account;
            $transaction->save();

            // return view('pos.sale.table', compact('sales'))->render();

            return response()->json([
                'status' => 200,
                'message' => 'Payment Successful',
                'purchase' => $purchase,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->errors(),
            ]);
        }
    }

    // update function
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'total_payable' => 'required',
            'payment_method' => 'required',
            'document' => 'file|mimes:jpg,pdf,png,svg,webp,jpeg,gif|max:5120',
        ]);
        if ($validator->passes()) {
            $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
            if ($oldBalance != null) {
                if ($oldBalance->balance > 0 && $oldBalance->balance >= $request->total_payable) {

                    $settings = PosSetting::first();
                    // check invoice payment on or off
                    $invoice_payment = $settings?->invoice_payment ?? 0;
                    $totalQty = 0;
                    $totalAmount = 0;
                    // dd($request->quantity);
                    // Assuming all arrays have the same length
                    $arrayLength = count($request->quantity);
                    for ($i = 0; $i < $arrayLength; $i++) {
                        $totalQty += $request->quantity[$i];
                        $totalAmount += ($request->cost_price[$i] * $request->quantity[$i]);
                    }

                    $purchaseDate = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d');
                    $total_cost_price = $request->extra_cost_total ?? ($request->carrying_cost ?? 0);

                    // dd($totalAmount);
                    $purchase = Purchase::findOrFail($id);

                    // purchase Item
                    $existingItems = PurchaseItem::where('purchase_id', $id)->pluck('id')->toArray();
                    //  dd( $request->all());
                    $requestItems = array_filter($request->purchase_item_id);
                    $itemsToDelete = array_diff($existingItems, $requestItems);

                    if (! empty($itemsToDelete)) {
                        // Fetch deleted items with their quantities and variation_id
                        $deletedItems = PurchaseItem::whereIn('id', $itemsToDelete)->get();

                        foreach ($deletedItems as $deletedItem) {
                            $remainingQuantity = $deletedItem->quantity; // Quantity to deduct
                            $variationId = $deletedItem->variation_id;
                            $productId = $deletedItem->product_id;
                            $branchId = Auth::user()->branch_id;

                            // Fetch stock records with is_Current_stock = true first, then others, ordered by creation date (latest first)
                            $stocks = Stock::where('variation_id', $variationId)
                                ->where('branch_id', $branchId)
                                ->orderBy('is_Current_stock', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->get();

                            // Deduct stock starting from is_Current_stock = true
                            foreach ($stocks as $stock) {
                                if ($remainingQuantity <= 0) {
                                    break; // No more quantity to deduct
                                }

                                // Deduct as much as possible from the current stock
                                $deductibleQuantity = min($stock->stock_quantity, $remainingQuantity);
                                $stock->stock_quantity -= $deductibleQuantity;
                                $remainingQuantity -= $deductibleQuantity;

                                // Delete the stock record if quantity reaches zero, otherwise save
                                if ($stock->stock_quantity <= 0) {
                                    $stock->delete();
                                } else {
                                    $stock->save();
                                }
                            }

                            // If there is still remaining quantity and no stock records left, create a new stock entry with negative stock
                            if ($remainingQuantity > 0) {
                                Stock::create([
                                    'variation_id' => $variationId,
                                    'product_id' => $productId,
                                    'branch_id' => $branchId,
                                    'stock_quantity' => -$remainingQuantity, // Negative stock
                                    'stock_age' => Carbon::now()->toDateString(),
                                    'status' => 'stock_out',
                                    'is_Current_stock' => true,
                                ]);
                            }

                            // Set is_Current_stock = true for the latest remaining stock (if any)
                            $nextStock = Stock::where('variation_id', $variationId)
                                ->where('branch_id', $branchId)
                                ->orderBy('created_at', 'desc')
                                ->first();
                            if ($nextStock) {
                                $nextStock->is_Current_stock = true;
                                $nextStock->save();
                            }
                        }

                        // Delete the PurchaseItem records
                        PurchaseItem::whereIn('id', $itemsToDelete)->delete();
                    }

                    for ($i = 0; $i < $arrayLength; $i++) {
                        $variant = Variation::findOrFail($request->variant_id[$i]);
                        // dd($request->purchase_item_id[2] != null);
                        if (! empty($request->purchase_item_id[$i]) && is_numeric($request->purchase_item_id[$i]) && $request->purchase_item_id[$i] > 0) {
                            // Update existing item..//
                            $items = PurchaseItem::findOrFail($request->purchase_item_id[$i]);
                            $oldQuantity = $items->quantity; // Store old quantity for stock difference
                            $items->purchase_id = $id;
                            $items->product_id = $variant->product_id;
                            $items->discount = $request->variant_discount[$i] ?? 0;
                            $items->variant_id = $request->variant_id[$i];
                            $items->unit_price = $request->cost_price[$i];
                            $items->quantity = $request->quantity[$i];
                            $items->total_price = $request->cost_price[$i] * $request->quantity[$i];
                            $items->save();

                            $stockDifference = $request->quantity[$i] - $oldQuantity;

                            if ($stockDifference > 0) {
                                // Positive difference: Add to the latest stock
                                $latestStock = Stock::where('variation_id', $request->variant_id[$i])
                                    ->where('branch_id', Auth::user()->branch_id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                if ($latestStock) {
                                    $latestStock->stock_quantity += $stockDifference;
                                    $latestStock->status = 'available';
                                    $latestStock->is_Current_stock = true;
                                    $latestStock->save();
                                } else {
                                    // Create new stock if none exists
                                    $stock = new Stock;
                                    $stock->branch_id = Auth::user()->branch_id;
                                    $stock->product_id = $variant->product_id;
                                    $stock->variation_id = $request->variant_id[$i];
                                    $stock->stock_quantity = $stockDifference;
                                    $stock->stock_age = Carbon::now()->toDateString();
                                    $stock->manufacture_date = $request->manufacture_date[$i] ?? null;
                                    $stock->expiry_date = $request->expiry_date[$i] ?? null;
                                    $stock->status = 'available';
                                    $stock->is_Current_stock = true;
                                    $stock->save();
                                }
                            } elseif ($stockDifference < 0) {
                                // Negative difference: Deduct from is_Current_stock = true first
                                $remainingQuantity = abs($stockDifference);
                                $stocks = Stock::where('variation_id', $request->variant_id[$i])
                                    ->where('branch_id', Auth::user()->branch_id)
                                    ->orderBy('is_Current_stock', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->get();

                                foreach ($stocks as $stock) {
                                    if ($remainingQuantity <= 0) {
                                        break; // No more quantity to deduct
                                    }

                                    $deductibleQuantity = min($stock->stock_quantity, $remainingQuantity);
                                    $stock->stock_quantity -= $deductibleQuantity;
                                    $remainingQuantity -= $deductibleQuantity;

                                    if ($stock->stock_quantity <= 0) {
                                        $stock->delete();
                                    } else {
                                        $stock->save();
                                    }
                                }

                                // Set is_Current_stock = true for the latest remaining stock (if any)
                                $nextStock = Stock::where('variation_id', $request->variant_id[$i])
                                    ->where('branch_id', Auth::user()->branch_id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                if ($nextStock) {
                                    $nextStock->is_Current_stock = true;
                                    $nextStock->save();
                                }
                            }
                        } else {
                            // Create new item.
                            $items = new PurchaseItem;
                            $items->purchase_id = $id;
                            $items->product_id = $variant->product_id;
                            $items->discount = $request->variant_discount[$i] ?? 0;
                            $items->variant_id = $request->variant_id[$i];
                            $items->unit_price = $request->cost_price[$i];
                            $items->quantity = $request->quantity[$i];
                            $items->total_price = $request->cost_price[$i] * $request->quantity[$i];
                            $items->save();

                            $previousStock = Stock::where('variation_id', $request->variant_id[$i])->get();
                            $stock = new Stock;
                            $stock->branch_id = Auth::user()->branch_id;
                            $stock->product_id = $variant->product_id;
                            $stock->variation_id = $request->variant_id[$i];
                            $stock->stock_quantity = $request->quantity[$i];
                            $stock->stock_age = Carbon::now()->toDateString();
                            $stock->manufacture_date = $request->manufacture_date[$i] ?? null;
                            $stock->expiry_date = $request->expiry_date[$i] ?? null;
                            $stock->status = 'available';
                            if ($previousStock->count() > 0) {
                                $stock->is_Current_stock = false;
                            } else {
                                $stock->is_Current_stock = true;
                            }
                            $stock->save();
                        }
                        if ($settings->purchase_price_edit === 1) {
                            $variant->cost_price = $request->cost_price[$i];
                            $variant->save();
                        }
                    }

                    $accountTransaction = new AccountTransaction;
                    $accountTransaction->branch_id = Auth::user()->branch_id;
                    $accountTransaction->reference_id = $id;
                    $accountTransaction->account_id = $request->payment_method;
                    $accountTransaction->processed_by = Auth::user()->id;
                    $accountTransaction->created_at = Carbon::now();
                    if ($purchase->order_status === 'draft') {
                        $accountTransaction->purpose = 'Purchase';
                        $accountTransaction->debit = $request->total_payable;
                        $accountTransaction->balance = $oldBalance->balance - ($request->total_payable ?? 0);
                    } else {
                        $amount = $request->total_payable - $purchase->paid;
                        if ($amount >= 0) {
                            $accountTransaction->debit = $amount;
                            $accountTransaction->balance = $oldBalance->balance - $amount;
                        } else {
                            $accountTransaction->credit = $amount;
                            $accountTransaction->balance = $oldBalance->balance + (-$amount);
                        }
                        $accountTransaction->purpose = 'Purchase Edit';
                    }
                    $accountTransaction->save();

                    // Account Transaction
                    // AccountTransaction::create([
                    //     'branch_id' => Auth::user()->branch_id,
                    //     'reference_id' => $id,
                    //     'account_id' => $request->payment_method,
                    //     'purpose' => 'Purchase Edit',
                    //     'debit' => $request->total_payable ?? 0,
                    //     'balance' => $oldBalance->balance - ($request->total_payable ?? 0),
                    //     'created_at' => Carbon::now(),
                    // ]);

                    if ($request->carrying_cost > 0) {
                        if ($purchase->order_status === 'draft') {
                            $expense = new Expense;
                            $expense->branch_id = Auth::user()->branch_id;
                        } else {
                            $expense = Expense::where('purpose', 'Purchase'.$id)->first();
                        }
                        $expense->expense_date = now()->toDateString();
                        $expense->spender = Auth::user()->name;
                        $expense->bank_account_id = $request->payment_method;
                        $expense->amount = $request->carrying_cost;
                        $expense->save();
                    }

                    // get Transaction Model
                    if ($purchase->order_status === 'draft') {
                        $transaction = new Transaction;
                        $transaction->branch_id = Auth::user()->branch_id;
                        $transaction->payment_type = 'pay';
                        $transaction->particulars = 'Purchase#'.$id;
                        $transaction->supplier_id = $purchase->supplier_id;
                    } else {
                        $transaction = Transaction::where('particulars', 'Purchase#'.$id)->first();
                    }
                    $transaction->date = $purchaseDate;
                    $transaction->payment_method = $request->payment_method;
                    $transaction->debit = $request->total_payable;
                    $transaction->credit = $request->sub_total;
                    $transaction->balance = $request->total_payable - $request->sub_total;
                    $transaction->save();

                    // -- Save Supplier Info --//

                    $supplier = Customer::findOrFail($request->supplier_id);

                    if ($purchase->order_status === 'draft') {
                        $supplier->total_payable += ($request->sub_total - $total_cost_price);
                        if ($invoice_payment === 1) {
                            $supplier->total_receivable += ($request->total_payable - $total_cost_price) >= ($request->sub_total - $total_cost_price) ? ($request->sub_total - $total_cost_price) : ($request->total_payable - $total_cost_price);
                            $supplier->wallet_balance = $supplier->total_receivable - $supplier->total_payable;
                        } else {
                            $supplier->total_receivable += ($request->total_payable - $total_cost_price);
                            $supplier->wallet_balance = ($request->sub_total - $supplier->total_payable) - $total_cost_price;
                        }
                        $supplier->update();
                    } else {
                        // Calculate payable amounts
                        $payWithoutCarryingCost = $request->carrying_cost > 0 ? $request->total_payable - $request->carrying_cost : $request->total_payable;
                        $totalWithoutCarryingCost = $request->carrying_cost > 0 ? $request->sub_total - $request->carrying_cost : $request->sub_total;

                        // save Supplier Info
                        $payAmount = $purchase->paid - $payWithoutCarryingCost;
                        $subTotal = $purchase->sub_total - $totalWithoutCarryingCost;

                        // Update supplier's total receivable and payable
                        $supplier->total_receivable += $subTotal;
                        $supplier->total_payable += $payAmount;

                        // Adjust wallet balance based on the purchase's due amount
                        $walletAdjustment = $subTotal - $payAmount;
                        // if ($purchase->due > 0) {
                        //     $supplier->wallet_balance = ($supplier->wallet_balance - $purchase->due) + $walletAdjustment;
                        // } else {
                        $supplier->wallet_balance += $walletAdjustment;
                        // }

                        // Save the supplier's updated data
                        $supplier->save();
                    }

                    // purchase table Crud
                    // $purchase->branch_id = Auth::user()->branch_id;
                    // $purchase->supplier_id = $request->supplier_id;
                    $purchase->purchase_date = $purchaseDate;
                    $purchase->total_quantity = $totalQty;
                    $purchase->total_amount = $request->total;
                    if ($request->invoice) {
                        $purchase->invoice = $request->invoice;
                    } else {
                        // do {
                        //     $invoice = rand(123456, 999999); // Generate a random number
                        //     $existingInvoice = Purchase::where('invoice', $invoice)->first(); // Check if the random invoice exists
                        // } while ($existingInvoice); // Keep generating until a unique invoice number is found
                        // $purchase->invoice = $invoice;
                    }
                    // if ($request->carrying_cost > 0) {
                    //     $purchase->sub_total = $request->sub_total - $request->carrying_cost;
                    //     $purchase->grand_total = $request->sub_total - $request->carrying_cost;
                    // } else {
                    //     $purchase->sub_total = $request->sub_total;
                    //     $purchase->grand_total = $request->grand_total;
                    // }
                    $purchase->sub_total = $request->sub_total - $request->carrying_cost;
                    $purchase->grand_total = $request->grand_total;

                    $purchase->paid = $request->total_payable;
                    $due = $request->grand_total - $request->total_payable;
                    if ($due > 0) {
                        $purchase->due = $due;
                    } else {
                        $purchase->due = 0;
                    }

                    $purchase->due = max(0, ($request->grand_total - ($request->total_payable ?? 0)));
                    $purchase->total_purchase_cost = $total_cost_price;
                    $purchase->payment_method = $request->payment_method;
                    $purchase->note = $request->note;
                    if ($request->hasFile('document')) {
                        $extension = $request->document->getClientOriginalExtension();
                        $docName = rand().'.'.$extension;
                        $request->document->move(public_path('uploads/purchase/'), $docName);
                        $purchase->document = $docName;
                    }
                    $purchase->save();

                    return response()->json([
                        'status' => 200,
                        'purchaseId' => $purchase->id,
                        'message' => 'successfully save',
                    ]);
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Not Enough Balance in this Account. Please choose Another Account',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Please Add Balance to Account or Deposit Account Balance',
                ]);
            }
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    // destroy function
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->document) {
            $previousDocumentPath = public_path('uploads/purchase/').$purchase->document;
            if (file_exists($previousDocumentPath)) {
                unlink($previousDocumentPath);
            }
        }
        $purchase->delete();

        // return back()->with('message', "Purchase successfully Deleted");
        return response()->json([
            'status' => 200,
            'message' => 'Deleted Successfully',
        ]);
    }

    // filter function
    public function filter(Request $request)
    {
        // dd($request->all());
        // $purchaseQuery = Purchase::query();
        $purchaseQuery = Purchase::with(['purchaseItem.product.productUnit']);
        // Filter by product_id if provided
        if ($request->product_id != 'Select Product') {
            $purchaseQuery->whereHas('purchaseItem', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            });
        }
        // Filter by supplier_id if provided
        if ($request->supplier_id != 'Select Supplier') {
            $purchaseQuery->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $purchaseQuery->whereBetween('purchase_date', [$request->startDate, $request->endDate]);
        }

        // Execute the query
        $purchase = $purchaseQuery->get();
        $purchaseInvoice = $purchaseQuery->get();
        $purchaseTable = view('pos.purchase.table', compact('purchase'))->render();
        $purchaseInvoice = view('pos.purchase.all-purchase-invoice-print', compact('purchaseInvoice'))->render();

        return response()->json([
            'purchaseTable' => $purchaseTable,
            'purchaseInvoice' => $purchaseInvoice,
        ]);
        // return view('pos.purchase.view-all', compact('purchase', 'purchaseInvoice'));
    }

    // purchaseItem Function
    public function purchaseItem($id)
    {
        // Fetch PurchaseItem records with associated Product using eager loading
        $purchaseItems = PurchaseItem::where('purchase_id', $id)
            ->with(['product.unit']) // Eager load both product and its unit
            ->get();

        if ($purchaseItems->isNotEmpty()) {
            // If data exists, return the purchase items with product details
            return response()->json([
                'status' => 200,
                'purchaseItems' => $purchaseItems,
            ]);
        } else {
            // If no data found, return an error response
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    // get supplier details
    public function getSupplierDetails($id)
    {
        $supplier = Customer::findOrFail($id);

        return response()->json(['data' => $supplier], 200);
    }

    // image to PDF
    public function imageToPdf($id)
    {
        $purchase = Purchase::findOrFail($id);
        $documentPath = public_path('uploads/purchase/'.$purchase->document);
        // Define the data to pass to the PDF generation
        $data = [
            'imagePath' => $documentPath,  // Pass the moved document path
            'title' => "$purchase->document",
        ];

        $pdf = PDF::loadView('pdf.document', $data);
        // dd($pdf);

        // Return the generated PDF for download or streaming
        return response()->json([
            'status' => 200,
            'data' => $pdf,
        ]);
    }

    public function datewiseReport()
    {

        // $invoiceActivetion=PosSetting::where('invoice_payment',1)->first();

        // if($invoiceActivetion){
        $datewiseReport = Purchase::select(
            DB::raw('DATE(purchase_date) as date'),
            DB::raw('COUNT(DISTINCT invoice) as total_invoices'),
            DB::raw('SUM(grand_total) as total_amount'),
            DB::raw('SUM(paid) as total_paid'),
            DB::raw('SUM(due) as total_due'),

        )
            ->groupBy('date')
            ->having('total_invoices', '>', 0)
            ->orderByDesc('date')
            ->get();

        return view('pos.report.purchaseReport.datewiseReport', compact('datewiseReport'));
        // }

    }

    public function getSupplier()
    {
        try {
            // Retrieve customers associated with the authenticated user's branch
            $data = Customer::where('branch_id', Auth::user()->branch_id)->where('party_type', '<>', 'customer')->get();

            // Check if any customers were found
            if ($data->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Supplier found.',
                ]);
            }

            // Return the customer data with a success message
            return response()->json([
                'status' => 200,
                'message' => 'Suppliers retrieved successfully.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in getCustomer method: '.$e->getMessage());

            // Return a JSON response with a generic error message
            return response()->json([
                'status' => 500,
                'message' => 'An unexpected error occurred. Please try again later.',
            ]);
        }
    }
}
