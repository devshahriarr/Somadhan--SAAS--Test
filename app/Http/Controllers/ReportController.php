<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\AffliateCommission;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Damage;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\Expense;
use App\Models\PosSetting;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Returns;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Sms;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Variation;
use App\Models\ViaSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // today report function
    public function todayReport()
    {

        $todayDate = now()->toDateString();
        $branchData = [];
        // Today Invoice
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $branches = Branch::all();
            foreach ($branches as $branch) {
                $branchId = $branch->id;
                $todayInvoiceAmount = Sale::whereDate('sale_date', $todayDate)->where('branch_id', $branchId)->sum('change_amount');
                $today_grand_total = Purchase::whereDate('purchase_date', $todayDate)->where('branch_id', $branchId)->sum('grand_total');
                $todayExpenseAmount = Expense::whereDate('expense_date', $todayDate)->where('branch_id', $branchId)->sum('amount');
                $totalSalary = EmployeeSalary::whereDate('created_at', $todayDate)->where('branch_id', $branchId)->sum('debit');
                $branchData[$branchId] = [
                    'todayInvoiceAmount' => $todayInvoiceAmount,
                    'today_grand_total' => $today_grand_total,
                    'todayExpenseAmount' => $todayExpenseAmount,
                    'totalSalary' => $totalSalary,
                    'branch' => $branch,
                ];
            }
            $saleItemsForDate = SaleItem::whereDate('created_at', $todayDate);
            $todaySaleItemsToday = $saleItemsForDate->sum('qty');
            $totalInvoiceToday = Sale::whereDate('sale_date', $todayDate)->count();
            $totalSales = Sale::whereDate('sale_date', $todayDate)->get();
            $todayTotalSaleAmount = Sale::whereDate('sale_date', $todayDate)->sum('change_amount');
            $todayTotalSaleQty = Sale::whereDate('sale_date', $todayDate)->sum('quantity');
            $todayTotalSaleDue = Sale::whereDate('sale_date', $todayDate)->sum('due');

            // Today Purchase
            $todayPurchaseItems = PurchaseItem::whereDate('created_at', $todayDate);
            $purchases = Purchase::whereDate('created_at', $todayDate)->get();
            $todayPurchaseItemsToday = $todayPurchaseItems->sum('quantity');
            // $todayPurchaseToday = Purchase::whereDate('purchase_date', $todayDate)->get();
            // dd($todayPurchaseToday);
            // $today_grand_total = $todayPurchaseToday->sum('grand_total');
            $todayTotalPurchaseAmount = Purchase::whereDate('purchase_date', $todayDate)->sum('grand_total');
            $todayTotalPurchaseQty = Purchase::whereDate('purchase_date', $todayDate)->sum('total_quantity');
            $todayTotalPurchaseDue = Purchase::whereDate('purchase_date', $todayDate)->sum('due');

            // Today invoice product
            $todayInvoiceProductItems = Sale::whereDate('sale_date', $todayDate);
            $todayInvoiceProductTotal = $todayInvoiceProductItems->sum('quantity');
            $todayInvoiceProductAmount = $todayInvoiceProductItems->sum('final_receivable');
            // today invoice amount
            $totalInvoiceTodaySum = Sale::whereDate('sale_date', $todayDate);
            // $todayInvoiceAmount = $totalInvoiceTodaySum->sum('receivable');
            $todayProfit = $totalInvoiceTodaySum->sum('profit');
            // today expenses
            // $todayExpenseDate = Expense::whereDate('expense_date', $todayDate);
            // $todayExpenseAmount = $todayExpenseDate->sum('amount');
            // Today Customer
            $todayCustomer = Customer::where('party_type', 'customer')->whereDate('created_at', $todayDate);
            // Sale Profit
            $saleProfitAmount = $totalInvoiceTodaySum->sum('profit');

            $expense = Expense::whereDate('expense_date', $todayDate)->get();
            $expenseAmount = $expense->sum('amount');
            $salary = EmployeeSalary::whereDate('created_at', $todayDate)->get();
            // $totalSalary = $salary->sum('debit');
            $totalSalaryDue = $salary->sum('balance');
        } else {
            // for Branch
            $saleItemsForDate = SaleItem::whereHas('saleId', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })
                ->whereDate('created_at', $todayDate);
            $todaySaleItemsToday = $saleItemsForDate->sum('qty');
            $totalInvoiceToday = Sale::where('branch_id', Auth::user()->branch_id)->whereDate('sale_date', $todayDate)->count();
            $totalSales = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate)->get();
            $todayTotalSaleAmount = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate)->sum('change_amount');
            $todayTotalSaleQty = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate)->sum('quantity');
            $todayTotalSaleDue = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate)->sum('due');

            // Today Purchase
            $todayPurchaseItems = PurchaseItem::whereHas('Purchas', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })
                ->whereDate('created_at', $todayDate);
            $purchases = Purchase::whereDate('created_at', $todayDate)->get();
            $todayPurchaseItemsToday = $todayPurchaseItems->sum('quantity');
            $todayPurchaseToday = Purchase::where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', $todayDate)->get();
            // dd($todayPurchaseToday);
            $today_grand_total = $todayPurchaseToday->sum('grand_total');
            $todayTotalPurchaseAmount = Purchase::where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', $todayDate)->sum('grand_total');
            $todayTotalPurchaseQty = Purchase::where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', $todayDate)->sum('total_quantity');
            $todayTotalPurchaseDue = Purchase::whereDate('purchase_date', $todayDate)->sum('due');

            // Today invoice product
            $todayInvoiceProductItems = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate);
            $todayInvoiceProductTotal = $todayInvoiceProductItems->sum('quantity');
            $todayInvoiceProductAmount = $todayInvoiceProductItems->sum('final_receivable');
            // today invoice amount
            $totalInvoiceTodaySum = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $todayDate);
            $todayInvoiceAmount = $totalInvoiceTodaySum->sum('change_amount');
            $todayProfit = $totalInvoiceTodaySum->sum('profit');
            // today expenses
            $todayExpenseDate = Expense::where('branch_id', Auth::user()->branch_id)
                ->whereDate('expense_date', $todayDate);
            $todayExpenseAmount = $todayExpenseDate->sum('amount');
            // Today Customer//
            $todayCustomer = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', $todayDate);
            // Sale Profit
            $saleProfitAmount = $totalInvoiceTodaySum->sum('profit');

            $expense = Expense::where('branch_id', Auth::user()->branch_id)
                ->whereDate('expense_date', $todayDate)->get();
            $expenseAmount = $expense->sum('amount');
            $salary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', $todayDate)->get();
            $totalSalary = $salary->sum('debit');
            $totalSalaryDue = $salary->sum('balance');
        }

        return view('pos.report.today.today', compact('todayInvoiceAmount', 'totalSales', 'today_grand_total', 'todayExpenseAmount', 'totalSalary', 'expense', 'todayTotalSaleAmount', 'todayTotalSaleDue', 'todayTotalSaleQty', 'purchases', 'todayTotalPurchaseDue', 'todayTotalPurchaseQty', 'todayTotalPurchaseAmount', 'salary', 'branchData', 'totalSalaryDue'));
    }

    // summary report function
    public function summaryReport()
    {
        $branchData = [];
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $branches = Branch::all();
            foreach ($branches as $branch) {
                $branchId = $branch->id;
                $sale = Sale::where('branch_id', $branchId)->get();
                $saleAmount = $sale->sum('change_amount');
                $purchase = Purchase::where('branch_id', $branchId)->get();
                $purchaseAmount = $purchase->sum('sub_total');
                $expense = Expense::where('branch_id', $branchId)->get();
                $expenseAmount = $expense->sum('amount');
                $sellProfit = $sale->sum('profit');
                $salary = EmployeeSalary::where('branch_id', $branchId)->get();
                $totalSalary = $salary->sum('debit');
                $branchData[$branchId] = [
                    'saleAmount' => $saleAmount,
                    'purchaseAmount' => $purchaseAmount,
                    'sellProfit' => $sellProfit,
                    'expenseAmount' => $expenseAmount,
                    'totalSalary' => $totalSalary,
                    'branch' => $branch,
                ];
            }
            $products = Product::take(20)
                ->get();
            $expense = Expense::all();
            $supplier = Transaction::whereNotNull('supplier_id')->get();
            $customer = Transaction::whereNotNull('customer_id')->get();
        } else {
            $products = Product::where('branch_id', Auth::user()->branch_id)
                ->take(20)
                ->get();
            // $expense =  Expense::all();
            $supplier = Transaction::where('branch_id', Auth::user()->branch_id)->whereNotNull('supplier_id')->get();
            $customer = Transaction::where('branch_id', Auth::user()->branch_id)->whereNotNull('customer_id')->get();
            $sale = Sale::where('branch_id', Auth::user()->branch_id)->get();
            $saleAmount = $sale->sum('change_amount');
            $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->get();
            $purchaseAmount = $purchase->sum('grand_total');
            $expense = Expense::where('branch_id', Auth::user()->branch_id)->get();
            $expenseAmount = $expense->sum('amount');
            $sellProfit = $sale->sum('profit');
            $salary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)->get();
            $totalSalary = $salary->sum('debit');
        }

        return view('pos.report.summary.summary', compact('saleAmount', 'purchaseAmount', 'expenseAmount', 'sellProfit', 'totalSalary', 'products', 'expense', 'supplier', 'customer', 'branchData'));
    }

    // customer due report function
    public function customerDue()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $customer = Customer::where('party_type', 'customer')->where('wallet_balance', '>', 0)
                ->get();
        } else {
            $customer = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)
                ->where('wallet_balance', '>', 0)
                ->get();
        }

        return view('pos.report.customer.customer_due', compact('customer'));
    }

    public function damageReportPrint(Request $request)
    {
        // dd($request->all());

        $damageItem = Damage::when($request->startdatepurches && $request->enddatepurches, function ($query) use ($request) {
            return $query->whereBetween('date', [$request->startdatepurches, $request->enddatepurches]);
        })
            ->when($request->filterProduct, function ($query) use ($request) {
                return $query->where('product_id', $request->filterProduct);
            })
            ->when($request->branchId, function ($query) use ($request) {
                return $query->where('branch_id', $request->branchId);
            })
            ->get();

        if ($damageItem->isEmpty()) {
            $damageItem = Damage::all();
        }

        return view('pos.report.damages.print', compact('damageItem'));
    }

    // customer due filter function
    public function customerDueFilter(Request $request)
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $customer = Customer::where('party_type', 'customer')->where('id', $request->customerId)->get();
        } else {
            $customer = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->where('id', $request->customerId)->get();
        }

        return view('pos.report.customer.table', compact('customer'))->render();
    }

    // supplier due report function
    public function supplierDueReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $suppliers = Customer::where('party_type', 'supplier')->where('wallet_balance', '>', 0)
                ->get();
        } else {
            $suppliers = Customer::where('party_type', 'supplier')->where('branch_id', Auth::user()->branch_id)
                ->where('wallet_balance', '>', 0)
                ->get();
        }

        return view('pos.report.supplier.supplier_due', compact('suppliers'));
    }

    // supplier due filter function
    public function supplierDueFilter(Request $request)
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $suppliers = Customer::where('id', $request->customerId)->get();
        } else {
            $suppliers = Customer::where('branch_id', Auth::user()->branch_id)
                ->where('id', $request->customerId)->get();
        }

        return view('pos.report.supplier.table', compact('suppliers'))->render();
    }
    // low stock report function

    // Top Products function
    // public function topProducts()
    // {
    //     if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
    //         $products = Product::withSum(['stockQuantity as stock_quantity_sum' => function ($query) {}], 'stock_quantity')
    //             // ->orderBy('total_sold', 'asc') // or 'desc' for descending order
    //             ->orderBy('stock_quantity_sum', 'asc') // or 'desc' for descending order
    //             ->get();
    //     } else {
    //         $products = Product::withSum(['stockQuantity as stock_quantity_sum' => function ($query) {
    //             $query->where('branch_id', Auth::user()->branch_id);
    //         }], 'stock_quantity')
    //             ->orderBy('stock_quantity_sum', 'asc') // or 'desc' for descending order
    //             ->get();
    //     }
    //     return view('pos.report.products.top_products', compact('products'));
    // }
    // purchase Report function
    public function purchaseReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $purchase = Purchase::all();
            $purchaseItem = PurchaseItem::all();
        } else {
            $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->get();
            $purchaseItem = PurchaseItem::whereHas('purchas', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->get();
        }

        return view('pos.report.purchase.purchase', compact('purchaseItem', 'purchase'));
    }

    public function PurchaseProductFilter(Request $request)
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $purchase = Purchase::all();
            $purchaseItem = PurchaseItem::when($request->startDatePurches && $request->endDatePurches, function ($query) use ($request) {
                return $query->whereHas('Purchas', function ($subQuery) use ($request) {
                    $subQuery->whereBetween('purchase_date', [$request->startDatePurches, $request->endDatePurches]);
                });
            })
                ->when($request->filterProduct != 'Select Product', function ($query) use ($request) {
                    return $query->where('product_id', $request->filterProduct);
                })
                ->get();
        } else {
            $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->get();
            $purchaseItem = PurchaseItem::whereHas('Purchas', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })
                ->when($request->startDatePurches && $request->endDatePurches, function ($query) use ($request) {
                    return $query->whereHas('Purchas', function ($subQuery) use ($request) {
                        $subQuery->whereBetween('purchase_date', [$request->startDatePurches, $request->endDatePurches]);
                    });
                })
                ->when($request->filterProduct != 'Select Product', function ($query) use ($request) {
                    return $query->where('product_id', $request->filterProduct);
                })
                ->get();
        }

        return view('pos.report.purchase.purchase-filter-table', compact('purchaseItem', 'purchase'))->render();
    }

    //
    public function PurchaseDetailsInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);

        return view('pos.report.purchase.purchase_invoice', compact('purchase'));

        return view('pos.report.purchase.purchase');
    }

    // damage reports starting

    public function damageReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $damageItem = Damage::all();
            $damage_cost = Damage::sum('damage_cost');
            $monthlyDamage = Damage::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('damage_cost');
        } else {
            $damageItem = Damage::where('branch_id', Auth::user()->branch_id)->with('product', 'variation')->get();
            $damage_cost = Damage::where('branch_id', Auth::user()->branch_id)->sum('damage_cost');
            $monthlyDamage = Damage::where('branch_id', Auth::user()->branch_id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('damage_cost');
        }
        foreach ($damageItem as $damage) {
            $damage->total_cost = $damage->product->cost * $damage->qty;
        }
        $totalSum = $damageItem->sum('total_cost');

        return view('pos.report.damages.damage', compact('damageItem', 'totalSum', 'monthlyDamage', 'damage_cost'));
    }

    public function DamageProductFilter(Request $request)
    {
        // dd($request);
        $damageItem = Damage::when($request->startDatePurches && $request->endDatePurches, function ($query) use ($request) {
            return $query->whereBetween('date', [$request->startDatePurches, $request->endDatePurches]);
        })
            ->when($request->filterProduct != 'Select Product', function ($query) use ($request) {
                return $query->where('product_id', $request->filterProduct);
            })
            ->when($request->branchId != 'Select Branch', function ($query) use ($request) {
                return $query->where('branch_id', $request->branchId);
            })
            ->get();
        foreach ($damageItem as $damage) {
            $damage->total_cost = $damage->product->cost * $damage->qty;
        }
        $totalSum = $damageItem->sum('total_cost');

        return view('pos.report.damages.damage-filter-table', compact('damageItem', 'totalSum'))->render();
    } //

    // customer Ledger report function
    public function customerLedger()
    {
        return view('pos.report.customer.customer_ledger');
    }

    // customer Ledger Filter function
    public function customerLedgerFilter(Request $request)
    {
        $transactionQuery = Transaction::query();
        // Filter by supplier_id if provided
        if ($request->customerId != 'Select Customer') {
            $transactionQuery->where('customer_id', $request->customerId);
        }
        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $transactionQuery->whereBetween('date', [$request->startDate, $request->endDate]);
        }
        $transactions = $transactionQuery->get();
        $customer = Customer::findOrFail($request->customerId);

        return response()->json([
            'status' => 200,
            'transactions' => $transactions,
            'customer' => $customer,
        ]);

        // return view("pos.report.supplier.show_ledger", compact('supplier', 'transactions'))->render();
    }

    // supplier Ledger report function
    public function supplierLedger()
    {
        return view('pos.report.supplier.supplier_ledger');
    }

    // supplier Ledger Filter function
    public function supplierLedgerFilter(Request $request)
    {
        $transactionQuery = Transaction::query();
        // Filter by supplier_id if provided
        if ($request->supplierId != 'Select Supplier') {
            $transactionQuery->where('supplier_id', $request->supplierId);
        }
        // Filter by date range if both start_date and end_date are provided
        if ($request->startDate && $request->endDate) {
            $transactionQuery->whereBetween('date', [$request->startDate, $request->endDate]);
        }
        $transactions = $transactionQuery->get();
        $supplier = Customer::findOrFail($request->supplierId);

        return response()->json([
            'status' => 200,
            'transactions' => $transactions,
            'supplier' => $supplier,
        ]);

        // return view("pos.report.supplier.show_ledger", compact('supplier', 'transactions'))->render();
    }

    // bank Report function
    public function bankReport()
    {
        return view('pos.report.bank.bank');
    }

    public function lowStockReport()
    {
        // if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
        //     $branchsId = Branch::findOrFail($branchId);
        // }else{
        $branchsId = Auth::user()->branch_id;
        // }
        // Fetch stocks with relationships and filter by branch
        // $stocks = Stock::with(['product.category', 'variation'])
        //     ->where('branch_id', $branchsId)
        //     ->get();
        $stocks = Stock::with(['product.category', 'variation'])
            ->where('branch_id', $branchsId)
            ->where('stock_quantity', '<=', 10) // Filter for low stock
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Product-level stock summary
        $productSummary = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
            $totalStockQuantity = $stocksForProduct->sum('stock_quantity');

            $totalCostPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->cost_price ?? 0);
            });

            $totalB2bPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2b_price ?? 0);
            });

            $totalB2cPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2c_price ?? 0);
            });

            return [
                'product_name' => $stocksForProduct->first()->product->name ?? 'Unknown',
                'category' => $stocksForProduct->first()->product->category->name ?? 'Unknown',
                'total_stock_quantity' => $totalStockQuantity,
                'total_cost_price' => $totalCostPrice,
                'total_b2b_price' => $totalB2bPrice,
                'total_b2c_price' => $totalB2cPrice,
            ];
        });

        //  Variation-level stock and price details (per variation)
        // $groupedVariationDetails = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
        //     return $stocksForProduct->map(function ($stock) {
        //         return [
        //             'variation_name' => $stock->variation->status ?? 'N/A',
        //             'stock_quantity' => $stock->stock_quantity,
        //             'cost_price' => $stock->stock_quantity * ($stock->variation->cost_price ?? 0),
        //             'b2b_price' => $stock->stock_quantity * ($stock->variation->b2b_price ?? 0),
        //             'b2c_price' => $stock->stock_quantity * ($stock->variation->b2c_price ?? 0),
        //         ];
        //     });
        // });
        // $variations = Variation::where('stocks', 'stock_quantity')
        // ->orderBAsc('stock_quantity')
        // ->get();
        // $variations = Variation::all();
        $variations = Variation::withSum('stocks', 'stock_quantity')
            ->having('stocks_sum_stock_quantity', '<=', 10) // Filter variations with stock sum <= 10
            ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
            ->get();

        return view('pos.report.products.low-stock.low_stock', compact('productSummary', 'variations'));
    }

    // stock Report function
    public function stockReport()
    {

        $branchsId = Auth::user()->branch_id;

        $stocks = Stock::with(['product.category', 'variation'])
            ->where('branch_id', $branchsId)
            ->get();

        // Product-level stock summary
        $productSummary = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
            $totalStockQuantity = $stocksForProduct->sum('stock_quantity');

            $totalCostPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->cost_price ?? 0);
            });

            $totalB2bPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2b_price ?? 0);
            });

            $totalB2cPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2c_price ?? 0);
            });

            return [
                'product_name' => $stocksForProduct->first()->product->name ?? 'Unknown',
                'category' => $stocksForProduct->first()->product->category->name ?? 'Unknown',
                'total_stock_quantity' => $totalStockQuantity,
                'total_cost_price' => $totalCostPrice,
                'total_b2b_price' => $totalB2bPrice,
                'total_b2c_price' => $totalB2cPrice,
            ];
        });

        // Variation-level stock and price details (per variation)
        // $groupedVariationDetails = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
        //     return $stocksForProduct->map(function ($stock) {
        //         return [
        //             'variation_name' => $stock->variation->status ?? 'N/A',
        //             'stock_quantity' => $stock->stock_quantity,
        //             'cost_price' => $stock->stock_quantity * ($stock->variation->cost_price ?? 0),
        //             'b2b_price' => $stock->stock_quantity * ($stock->variation->b2b_price ?? 0),
        //             'b2c_price' => $stock->stock_quantity * ($stock->variation->b2c_price ?? 0),
        //         ];
        //     });
        // });
        $variations = Variation::withSum('stocks', 'stock_quantity')
            ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
            ->get();

        return view('pos.report.products.stock.stock', compact('productSummary', 'variations'));
    }

    public function stockShowByBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        $stocks = Stock::with(['product.category', 'variation'])
            ->where('branch_id', $branch->id)
            ->get();

        // Product-level stock summary
        $productSummary = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
            $totalStockQuantity = $stocksForProduct->sum('stock_quantity');

            $totalCostPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->cost_price ?? 0);
            });

            $totalB2bPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2b_price ?? 0);
            });

            $totalB2cPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2c_price ?? 0);
            });

            return [
                'product_name' => $stocksForProduct->first()->product->name ?? 'Unknown',
                'category' => $stocksForProduct->first()->product->category->name ?? 'Unknown',
                'total_stock_quantity' => $totalStockQuantity,
                'total_cost_price' => $totalCostPrice,
                'total_b2b_price' => $totalB2bPrice,
                'total_b2c_price' => $totalB2cPrice,
            ];
        });

        // Variation-level stock and price details (per variation)
        // $groupedVariationDetails = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
        //     return $stocksForProduct->map(function ($stock) {
        //         return [
        //             'variation_name' => $stock->variation->status ?? 'N/A',
        //             'stock_quantity' => $stock->stock_quantity,
        //             'cost_price' => $stock->stock_quantity * ($stock->variation->cost_price ?? 0),
        //             'b2b_price' => $stock->stock_quantity * ($stock->variation->b2b_price ?? 0),
        //             'b2c_price' => $stock->stock_quantity * ($stock->variation->b2c_price ?? 0),
        //         ];
        //     });
        // });
        // $variations = Variation::withSum('stocks', 'stock_quantity')
        // ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
        // ->get();
        $variations = Variation::withSum(['stocks as stocks_sum_stock_quantity' => function ($query) use ($branch) {
            $query->where('branch_id', $branch->id); // Match stocks with the specified branch ID
        }], 'stock_quantity')
            ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
            ->get();

        return view('pos.report.products.stock.superadmin-stock', compact('productSummary', 'variations'));
    }

    public function lowStockShowByBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        $stocks = Stock::with(['product.category', 'variation'])
            ->where('branch_id', $branch->id)
            ->where('stock_quantity', '<=', 10) // Filter for low stock
            ->orderBy('stock_quantity', 'asc')
            ->get();

        $productSummary = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
            $totalStockQuantity = $stocksForProduct->sum('stock_quantity');

            $totalCostPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->cost_price ?? 0);
            });

            $totalB2bPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2b_price ?? 0);
            });

            $totalB2cPrice = $stocksForProduct->sum(function ($stock) {
                return $stock->stock_quantity * ($stock->variation->b2c_price ?? 0);
            });

            return [
                'product_name' => $stocksForProduct->first()->product->name ?? 'Unknown',
                'category' => $stocksForProduct->first()->product->category->name ?? 'Unknown',
                'total_stock_quantity' => $totalStockQuantity,
                'total_cost_price' => $totalCostPrice,
                'total_b2b_price' => $totalB2bPrice,
                'total_b2c_price' => $totalB2cPrice,
            ];
        });

        // Variation-level stock and price details (per variation)
        // $groupedVariationDetails = $stocks->groupBy('product_id')->map(function ($stocksForProduct) {
        //     return $stocksForProduct->map(function ($stock) {
        //         return [
        //             'variation_name' => $stock->variation->status ?? 'N/A',
        //             'stock_quantity' => $stock->stock_quantity,
        //             'cost_price' => $stock->stock_quantity * ($stock->variation->cost_price ?? 0),
        //             'b2b_price' => $stock->stock_quantity * ($stock->variation->b2b_price ?? 0),
        //             'b2c_price' => $stock->stock_quantity * ($stock->variation->b2c_price ?? 0),
        //         ];
        //     });
        // });
        // $variations = Variation::withSum('stocks', 'stock_quantity')
        // ->having('stocks_sum_stock_quantity', '<=', 10) // Filter variations with stock sum <= 10
        // ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
        // ->get();

        $variations = Variation::withSum(['stocks as stocks_sum_stock_quantity' => function ($query) use ($branch) {
            $query->where('branch_id', $branch->id); // Match stocks with the specified branch ID
        }], 'stock_quantity')
            ->having('stocks_sum_stock_quantity', '<=', 10) // Filter variations with stock sum <= 10 for the branch
            ->orderBy('stocks_sum_stock_quantity', 'asc') // Order by the stock sum in ascending order
            ->get();

        return view('pos.report.products.low-stock.superadmin_low_stock', compact('productSummary', 'variations'));
    }

    // //////////////Account Transaction Method  //////////////
    public function AccountTransactionView()
    {
        $accountTransaction = AccountTransaction::latest()->get();

        return view('pos.report.account_transaction.account_transaction_ledger', compact('accountTransaction'));
    }

    public function AccountTransactionFilter(Request $request)
    {
        // dd($request->all());
        $accountTransaction = AccountTransaction::when($request->accountId, function ($query) use ($request) {
            return $query->where('account_id', $request->accountId);
        })
            ->when($request->startDate && $request->endDate, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
            })
            ->when($request->saleBy != 'Select Sales Man', function ($query) use ($request) {
            return $query->where('processed_by', $request->saleBy);
             })
        ->whereNotNull('processed_by')
            ->get();

        return view('pos.report.account_transaction.account_transaction_table', compact('accountTransaction'))->render();
    }

    // ////////////////Rexpense Report MEthod //////////////
    public function ExpenseReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $expense = Expense::latest()->get();
        } else {
            $expense = Expense::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.report.expense.expense', compact('expense'));
    }

    //
    public function ExpenseReportFilter(Request $request)
    {
        // dd($request->all());
        $expense = Expense::when($request->startDate && $request->endDate, function ($query) use ($request) {
            return $query->whereBetween('expense_date', [$request->startDate, $request->endDate]);
        })->get();

        return view('pos.report.expense.expense-table', compact('expense'))->render();
    }

    // ////////////////Employee Salary Report MEthod //////////////
    public function EmployeeSalaryReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $employeeSalary = EmployeeSalary::all();
        } else {
            $employeeSalary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.report.employee_salary.employee_salary', compact('employeeSalary'));
    }

    //
    public function EmployeeSalaryReportFilter(Request $request)
    {
        $employeeSalary = EmployeeSalary::when($request->salaryId, function ($query) use ($request) {
            return $query->where('employee_id', $request->salaryId);
        })
            ->when($request->startDate && $request->endDate, function ($query) use ($request) {
                return $query->whereBetween('date', [$request->startDate, $request->endDate]);
            })
            ->get();

        return view('pos.report.employee_salary.employee_salary-table', compact('employeeSalary'))->render();
    }

    // //////Product Info Report /////
    public function ProductInfoReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $variationInfo = Variation::all();
            $productInfo = Product::withSum('stockQuantity', 'stock_quantity')->latest()->get();
        } else {
            $variationInfo = Variation::all();
            $productInfo = Product::where('branch_id', Auth::user()->branch_id)
                ->withSum('stockQuantity', 'stock_quantity')
                ->latest()
                ->get();
            // $productInfo = Product::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.report.products.product_info_report', compact('productInfo', 'variationInfo'));
    } //

    public function ProductInfoFilter(Request $request)
    {
        $settings = PosSetting::first();
        $sale_price_type = $settings->sale_price_type;

        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $productInfo = Product::withSum('stockQuantity', 'stock_quantity')->when($request->filterStartPrice, function ($query) use ($request, $sale_price_type) {
                return $query->whereHas('variations', function ($subQuery) use ($request, $sale_price_type) {
                    $subQuery->where($sale_price_type, '<=', (float) $request->filterStartPrice);
                });
            })
                ->when($request->filterBrand != 'Select Brand', function ($query) use ($request) {
                    return $query->where('brand_id', $request->filterBrand);
                })
                ->when($request->FilterCat != 'Select Category', function ($query) use ($request) {
                    return $query->where('category_id', $request->FilterCat);
                })
                ->when($request->filterSubcat != 'Select Sub Category', function ($query) use ($request) {
                    return $query->where('subcategory_id', $request->filterSubcat);
                })
                ->get();
        } else {
            $productInfo = Product::where('branch_id', Auth::user()->branch_id)->withSum('stockQuantity', 'stock_quantity')
                ->when($request->filterStartPrice, function ($query) use ($request, $sale_price_type) {
                    return $query->whereHas('variations', function ($subQuery) use ($request, $sale_price_type) {
                        $subQuery->where($sale_price_type, '<=', (float) $request->filterStartPrice);
                    });
                })
                ->when($request->filterBrand != 'Select Brand', function ($query) use ($request) {
                    return $query->where('brand_id', $request->filterBrand);
                })
                ->when($request->FilterCat != 'Select Category', function ($query) use ($request) {
                    return $query->where('category_id', $request->FilterCat);
                })
                ->when($request->filterSubcat != 'Select Sub Category', function ($query) use ($request) {
                    return $query->where('subcategory_id', $request->filterSubcat);
                })
                ->get();
        }

        return view('pos.report.products.product-info-filter-rander-table', compact('productInfo'))->render();
    }

    // /SMS Report Method
    public function SmsView()
    {
        $smsAll = Sms::all();

        return view('pos.report.sms.sms_report', compact('smsAll'));
    }

    //
    public function SmsReportFilter(Request $request)
    {
        $smsAll = Sms::when($request->customerId != 'Select Customer', function ($query) use ($request) {
            return $query->where('customer_id', $request->customerId);
        })
            ->when($request->startDate && $request->endDate, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
            })
            ->get();

        return view('pos.report.sms.sms-filter-table', compact('smsAll'))->render();
    }

    public function monthlyReport()
    {
        $dailyReports = [];
        $banks = Bank::all();
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            for ($i = 0; $i < 30; $i++) { // Loop for the last 30 days
                // Calculate the start and end dates for the day
                $date = now()->subDays($i)->toDateString();

                // Calculate the totals for the day
                //   incoming value
                $viaSale = ViaSale::whereDate('created_at', $date)->sum('sub_total');
                $totalSaleAmount = Sale::whereDate('sale_date', $date)->sum('paid');
                $totalSale = $totalSaleAmount - $viaSale;
                $dueCollection = Transaction::where('particulars', 'SaleDue')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $otherCollection = Transaction::where('particulars', 'OthersReceive')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $adjustDueCollection = Transaction::where('particulars', 'Adjust Due Collection')
                    ->where('payment_type', 'pay')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $addBalance = AccountTransaction::where(function ($query) {
                    $query->where('purpose', 'Add Bank Balance')
                        ->orWhere('purpose', 'Bank');
                })
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $previousDayBalance = 0;
                $lastTransactionDate = AccountTransaction::whereDate('created_at', '<', $date)
                    ->latest('created_at')
                    ->first();
                if ($lastTransactionDate) {
                    $lastTransactionDate = $lastTransactionDate->created_at->toDateString();

                    foreach ($banks as $bank) {
                        $transaction = AccountTransaction::where('account_id', $bank->id)
                            ->whereDate('created_at', $lastTransactionDate)
                            ->latest('created_at')
                            ->first();

                        if ($transaction) {
                            $previousDayBalance += $transaction->balance;
                        }
                    }
                }

                $totalIngoing =
                    $previousDayBalance +
                    $totalSale +
                    $dueCollection +
                    $otherCollection +
                    $addBalance +
                    $adjustDueCollection +
                    $viaSale;

                // outgoing Value//
                $totalPurchaseCost = Purchase::whereDate('purchase_date', $date)->sum('paid');
                $totalExpense = Expense::whereDate('expense_date', $date)->sum('amount');
                $totalSalary = EmployeeSalary::whereDate('date', $date)->sum('debit');
                $purchaseDuePay = Transaction::where('particulars', 'PurchaseDue')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $otherPaid = Transaction::where('particulars', 'OthersPayment')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $viaPayment = AccountTransaction::where('purpose', 'Via Payment')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $return = Returns::whereDate('created_at', $date)->sum('refund_amount');
                $todayReturnAmount = $return - $adjustDueCollection;

                $totalOutgoing =
                    $totalPurchaseCost +
                    $totalExpense +
                    $totalSalary +
                    $todayReturnAmount +
                    $purchaseDuePay +
                    $otherPaid +
                    $viaPayment;

                // profit Calculation//
                $totalProfit = Sale::whereDate('sale_date', $date)->sum('profit');
                $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
                $totalBalance = $totalIngoing - $totalOutgoing;
                $dayName = now()->subDays($i)->format('d F Y');
                // Store the report data in the array
                $dailyReports[now()->subDays($i)->format('Y-m-d')] = [
                    'id' => now()->subDays($i)->format('Ymd'),
                    'date' => $dayName,
                    // incoming
                    'totalSale' => $totalSale,
                    'dueCollection' => $dueCollection,
                    'otherCollection' => $otherCollection,
                    'adjustDueCollection' => $adjustDueCollection,
                    'addBalance' => $addBalance,
                    'viaSale' => $viaSale,
                    'previousDayBalance' => $previousDayBalance,
                    'totalIngoing' => $totalIngoing,

                    // outgoing
                    'totalPurchaseCost' => $totalPurchaseCost,
                    'totalExpense' => $totalExpense,
                    'totalSalary' => $totalSalary,
                    'purchaseDuePay' => $purchaseDuePay,
                    'todayReturnAmount' => $todayReturnAmount,
                    'viaPayment' => $viaPayment,
                    'otherPaid' => $otherPaid,
                    'totalOutgoing' => $totalOutgoing,

                    // profit
                    'totalProfit' => $totalProfit,
                    'finalProfit' => $finalProfit,
                    'totalBalance' => $totalBalance,
                ];
            }
        } else {
            for ($i = 0; $i < 30; $i++) { // Loop for the last 30 days
                // Calculate the start and end dates for the day
                $date = now()->subDays($i)->toDateString();

                // Calculate the totals for the day
                //   incoming value
                $viaSale = ViaSale::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('created_at', $date)->sum('sub_total');
                $totalSaleAmount = Sale::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('sale_date', $date)->sum('paid');
                $totalSale = $totalSaleAmount - $viaSale;
                $dueCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                    ->where('particulars', 'SaleDue')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $otherCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                    ->where('particulars', 'OthersReceive')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $adjustDueCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                    ->where('particulars', 'Adjust Due Collection')
                    ->where('payment_type', 'pay')
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $addBalance = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                    ->where(function ($query) {
                        $query->where('purpose', 'Add Bank Balance')
                            ->orWhere('purpose', 'Bank');
                    })
                    ->whereDate('created_at', $date)
                    ->sum('credit');
                $previousDayBalance = 0;
                $lastTransactionDate = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('created_at', '<', $date)
                    ->latest('created_at')
                    ->first();
                if ($lastTransactionDate) {
                    $lastTransactionDate = $lastTransactionDate->created_at->toDateString();

                    foreach ($banks as $bank) {
                        $transaction = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                            ->where('account_id', $bank->id)
                            ->whereDate('created_at', $lastTransactionDate)
                            ->latest('created_at')
                            ->first();

                        if ($transaction) {
                            $previousDayBalance += $transaction->balance;
                        }
                    }
                }

                $totalIngoing =
                    $previousDayBalance +
                    $totalSale +
                    $dueCollection +
                    $otherCollection +
                    $addBalance +
                    $adjustDueCollection +
                    $viaSale;

                // outgoing Value
                $totalPurchaseCost = Purchase::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('purchase_date', $date)->sum('paid');
                $totalExpense = Expense::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('expense_date', $date)->sum('amount');
                $totalSalary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('date', $date)->sum('debit');
                $purchaseDuePay = Transaction::where('branch_id', Auth::user()->branch_id)
                    ->where('particulars', 'PurchaseDue')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $otherPaid = Transaction::where('branch_id', Auth::user()->branch_id)
                    ->where('particulars', 'OthersPayment')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $viaPayment = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                    ->where('purpose', 'Via Payment')
                    ->whereDate('created_at', $date)
                    ->sum('debit');
                $return = Returns::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('created_at', $date)->sum('refund_amount');
                $todayReturnAmount = $return - $adjustDueCollection;

                $totalOutgoing =
                    $totalPurchaseCost +
                    $totalExpense +
                    $totalSalary +
                    $todayReturnAmount +
                    $purchaseDuePay +
                    $otherPaid +
                    $viaPayment;

                // profit Calculation//
                $totalProfit = Sale::where('branch_id', Auth::user()->branch_id)
                    ->whereDate('sale_date', $date)->sum('profit');
                $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
                $totalBalance = $totalIngoing - $totalOutgoing;
                $dayName = now()->subDays($i)->format('d F Y');
                // Store the report data in the array
                $dailyReports[now()->subDays($i)->format('Y-m-d')] = [
                    'id' => now()->subDays($i)->format('Ymd'),
                    'date' => $dayName,
                    // incoming
                    'totalSale' => $totalSale,
                    'dueCollection' => $dueCollection,
                    'otherCollection' => $otherCollection,
                    'adjustDueCollection' => $adjustDueCollection,
                    'addBalance' => $addBalance,
                    'viaSale' => $viaSale,
                    'previousDayBalance' => $previousDayBalance,
                    'totalIngoing' => $totalIngoing,

                    // outgoing
                    'totalPurchaseCost' => $totalPurchaseCost,
                    'totalExpense' => $totalExpense,
                    'totalSalary' => $totalSalary,
                    'purchaseDuePay' => $purchaseDuePay,
                    'todayReturnAmount' => $todayReturnAmount,
                    'viaPayment' => $viaPayment,
                    'otherPaid' => $otherPaid,
                    'totalOutgoing' => $totalOutgoing,

                    // profit
                    'totalProfit' => $totalProfit,
                    'finalProfit' => $finalProfit,
                    'totalBalance' => $totalBalance,
                ];
            }
        }

        // Pass the daily reports array to the view
        return view('pos.report.monthly.monthly', compact('dailyReports'));
    }

    public function monthlyReportView($id)
    {
        // dd($id);
        $date = \DateTime::createFromFormat('Ymd', $id);

        $banks = Bank::all();

        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            //  Calculate the totals for the day //
            $viaSale = ViaSale::whereDate('created_at', $date)->sum('sub_total');
            $totalSaleAmount = Sale::whereDate('sale_date', $date)->sum('paid');
            $totalSale = $totalSaleAmount - $viaSale;
            $dueCollection = Transaction::where('particulars', 'SaleDue')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $otherCollection = Transaction::where('particulars', 'OthersReceive')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $adjustDueCollection = Transaction::where('particulars', 'Adjust Due Collection')
                ->where('payment_type', 'pay')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $addBalance = AccountTransaction::where(function ($query) {
                $query->where('purpose', 'Add Bank Balance')
                    ->orWhere('purpose', 'Bank');
            })
                ->whereDate('created_at', $date)
                ->sum('credit');
            $previousDayBalance = 0;
            $lastTransactionDate = AccountTransaction::whereDate('created_at', '<', $date)
                ->latest('created_at')
                ->first();
            if ($lastTransactionDate) {
                $lastTransactionDate = $lastTransactionDate->created_at->toDateString();

                foreach ($banks as $bank) {
                    $transaction = AccountTransaction::where('account_id', $bank->id)
                        ->whereDate('created_at', $lastTransactionDate)
                        ->latest('created_at')
                        ->first();

                    if ($transaction) {
                        $previousDayBalance += $transaction->balance;
                    }
                }
            }

            $totalIngoing =
                $previousDayBalance +
                $totalSale +
                $dueCollection +
                $otherCollection +
                $addBalance +
                $adjustDueCollection +
                $viaSale;

            // outgoing Value
            $totalPurchaseCost = Purchase::whereDate('purchase_date', $date)->sum('paid');
            $totalExpense = Expense::whereDate('expense_date', $date)->sum('amount');
            $totalSalary = EmployeeSalary::whereDate('date', $date)->sum('debit');
            $purchaseDuePay = Transaction::where('particulars', 'PurchaseDue')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $otherPaid = Transaction::where('particulars', 'OthersPayment')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $viaPayment = AccountTransaction::where('purpose', 'Via Payment')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $return = Returns::whereDate('created_at', $date)->sum('refund_amount');
            $todayReturnAmount = $return - $adjustDueCollection;

            $totalOutgoing =
                $totalPurchaseCost +
                $totalExpense +
                $totalSalary +
                $todayReturnAmount +
                $purchaseDuePay +
                $otherPaid +
                $viaPayment;

            // profit Calculation
            $totalProfit = Sale::whereDate('sale_date', $date)->sum('profit');
            $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
            $totalBalance = $totalIngoing - $totalOutgoing;
        } else {
            //  Calculate the totals for the day //
            $viaSale = ViaSale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', $date)->sum('sub_total');
            $totalSaleAmount = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $date)->sum('paid');
            $totalSale = $totalSaleAmount - $viaSale;
            $dueCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                ->where('particulars', 'SaleDue')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $otherCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                ->where('particulars', 'OthersReceive')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $adjustDueCollection = Transaction::where('branch_id', Auth::user()->branch_id)
                ->where('particulars', 'Adjust Due Collection')
                ->where('payment_type', 'pay')
                ->whereDate('created_at', $date)
                ->sum('credit');
            $addBalance = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                ->where(function ($query) {
                    $query->where('purpose', 'Add Bank Balance')
                        ->orWhere('purpose', 'Bank');
                })
                ->whereDate('created_at', $date)
                ->sum('credit');
            $previousDayBalance = 0;
            $lastTransactionDate = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', '<', $date)
                ->latest('created_at')
                ->first();
            if ($lastTransactionDate) {
                $lastTransactionDate = $lastTransactionDate->created_at->toDateString();

                foreach ($banks as $bank) {
                    $transaction = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                        ->where('account_id', $bank->id)
                        ->whereDate('created_at', $lastTransactionDate)
                        ->latest('created_at')
                        ->first();

                    if ($transaction) {
                        $previousDayBalance += $transaction->balance;
                    }
                }
            }

            $totalIngoing =
                $previousDayBalance +
                $totalSale +
                $dueCollection +
                $otherCollection +
                $addBalance +
                $adjustDueCollection +
                $viaSale;

            // outgoing Value
            $totalPurchaseCost = Purchase::where('branch_id', Auth::user()->branch_id)
                ->whereDate('purchase_date', $date)->sum('paid');
            $totalExpense = Expense::where('branch_id', Auth::user()->branch_id)
                ->whereDate('expense_date', $date)->sum('amount');
            $totalSalary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)
                ->whereDate('date', $date)->sum('debit');
            $purchaseDuePay = Transaction::where('branch_id', Auth::user()->branch_id)
                ->where('particulars', 'PurchaseDue')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $otherPaid = Transaction::where('branch_id', Auth::user()->branch_id)
                ->where('particulars', 'OthersPayment')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $viaPayment = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                ->where('purpose', 'Via Payment')
                ->whereDate('created_at', $date)
                ->sum('debit');
            $return = Returns::where('branch_id', Auth::user()->branch_id)
                ->whereDate('created_at', $date)->sum('refund_amount');
            $todayReturnAmount = $return - $adjustDueCollection;

            $totalOutgoing =
                $totalPurchaseCost +
                $totalExpense +
                $totalSalary +
                $todayReturnAmount +
                $purchaseDuePay +
                $otherPaid +
                $viaPayment;

            // profit Calculation //
            $totalProfit = Sale::where('branch_id', Auth::user()->branch_id)
                ->whereDate('sale_date', $date)->sum('profit');
            $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
            $totalBalance = $totalIngoing - $totalOutgoing;
        }

        $formattedDate = $date->format('d F Y');
        $report = [
            'date' => $formattedDate,
            'totalSale' => number_format($totalSale, 2),
            'dueCollection' => number_format($dueCollection, 2),
            'otherCollection' => number_format($otherCollection, 2),
            'adjustDueCollection' => number_format($adjustDueCollection, 2),
            'addBalance' => number_format($addBalance, 2),
            'viaSale' => number_format($viaSale, 2),
            'previousDayBalance' => number_format($previousDayBalance, 2),
            'totalIngoing' => number_format($totalIngoing, 2),

            // outgoing
            'totalPurchaseCost' => number_format($totalPurchaseCost, 2),
            'totalExpense' => number_format($totalExpense, 2),
            'totalSalary' => number_format($totalSalary, 2),
            'purchaseDuePay' => number_format($purchaseDuePay, 2),
            'todayReturnAmount' => number_format($todayReturnAmount, 2),
            'viaPayment' => number_format($viaPayment, 2),
            'otherPaid' => number_format($otherPaid, 2),
            'totalOutgoing' => number_format($totalOutgoing, 2),

            // profit
            'totalProfit' => number_format($totalProfit, 2),
            'finalProfit' => number_format($finalProfit, 2),
            'totalBalance' => number_format($totalBalance, 2),
        ];

        return response()->json([
            'status' => '200',
            'report' => $report,
        ]);
    }

    public function yearlyReport()
    {
        $monthlyReports = [];

        for ($i = 0; $i < 12; $i++) {
            // Calculate the start and end dates for the month
            $startOfMonth = now()->subMonths($i)->startOfMonth()->toDateString();
            $endOfMonth = now()->subMonths($i)->endOfMonth()->toDateString();

            // Calculate the totals for the month
            if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
                $totalPurchaseCost = Purchase::whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
                    ->sum('grand_total');
                $totalSale = Sale::whereBetween('sale_date', [$startOfMonth, $endOfMonth])
                    ->sum('change_amount');
                $totalProfit = Sale::whereBetween('sale_date', [$startOfMonth, $endOfMonth])
                    ->sum('profit');
                $totalExpense = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');
                $totalSalary = EmployeeSalary::whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->sum('debit');
                $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
            } else {
                $totalPurchaseCost = Purchase::whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
                    ->sum('grand_total');
                $totalSale = Sale::where('branch_id', Auth::user()->branch_id)
                    ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
                    ->sum('change_amount');
                $totalProfit = Sale::where('branch_id', Auth::user()->branch_id)
                    ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
                    ->sum('profit');
                $totalExpense = Expense::where('branch_id', Auth::user()->branch_id)
                    ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');
                $totalSalary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->sum('debit');
                $finalProfit = $totalProfit - ($totalExpense + $totalSalary);
            }

            $monthName = now()->subMonths($i)->format('F Y');
            // Store the report data in the array
            $monthlyReports[now()->subMonths($i)->format('Y-m')] = [
                'month' => $monthName,
                'totalPurchaseCost' => $totalPurchaseCost,
                'totalSale' => $totalSale,
                'totalProfit' => $totalProfit,
                'totalExpense' => $totalExpense,
                'totalSalary' => $totalSalary,
                'totalSalary' => $totalSalary,
                'finalProfit' => $finalProfit,
            ];
        }

        // Pass the monthly reports array to the view
        return view('pos.report.yearly.yearly', compact('monthlyReports'));
    }

    // --------------Top Sale---------------//
    public function variationTopSale()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $variations = Variation::withSum('saleItem', 'qty')
                ->orderByDesc('sale_item_sum_qty')
                ->get();

            $products = Product::withSum('saleItem', 'qty')
                ->orderByDesc('sale_item_sum_qty')
                ->get();
        } else {
            $variations = Variation::withSum('saleItem', 'qty')
                ->whereHas('saleItem.saleId', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                })
                ->orderByDesc('sale_item_sum_qty') // Order by qty descending
                ->get();

            $products = Product::withSum('saleItem', 'qty')
                ->whereHas('saleItem.saleId', function ($query) {
                    $query->where('branch_id', Auth::user()->branch_id);
                })
                ->orderByDesc('sale_item_sum_qty')
                ->get();
        }

        return view('pos.report.product-variation-top-sale.top-sale', compact('variations', 'products'));
    }

    // Variation Filter
    public function variationTopSaleFilter(Request $request)
    {

        $variations = Variation::withSum('saleItem', 'qty')
            ->orderByDesc('sale_item_sum_qty')->when($request->startDateSale && $request->endDateSale, function ($query) use ($request) {
                $query->whereHas('saleItem.saleId', function ($query) use ($request) {
                    return $query->whereBetween('sale_date', [$request->startDateSale, $request->endDateSale]);
                });
            })
            ->get();

        return view('pos.report.product-variation-top-sale.top-variation-sale-filter-table', compact('variations'))->render();
    }

    // Product Filter
    public function productTopSaleFilter(Request $request)
    {

        $products = Product::withSum('saleItem', 'qty')
            ->orderByDesc('sale_item_sum_qty')->when($request->startDateSaleProduct && $request->endDateSalProducte, function ($query) use ($request) {
                $query->whereHas('saleItem.saleId', function ($query) use ($request) {
                    return $query->whereBetween('sale_date', [$request->startDateSaleProduct, $request->endDateSalProducte]);
                });
            })
            ->get();

        return view('pos.report.product-variation-top-sale.top-product-sale-filter-table', compact('products'))->render();
    }

    // --------------Supplier Report---------------//
    public function supplierWiseReport()
    {

        $supplierWiseReport = Purchase::select(
            'supplier_id',
            DB::raw('COUNT(DISTINCT invoice) as total_invoices'),
            DB::raw('SUM(grand_total) as total_amount')
        )
            ->with('supplier')
            ->groupBy('supplier_id')
            ->having('total_invoices', '>', 0)
            ->orderByDesc('supplier_id')
            ->get();

        return view('pos.report.supplier-wise-report.supplier-wise-report', compact('supplierWiseReport'));
    }

    public function product_purchase_report()
    {
        $item = PurchaseItem::with('product', 'variant', 'Purchas')->orderBy('id', 'desc')->get();

        return view('pos.report.productPurchase.product-purchase-report', compact('item'));
    }

    public function saelsmanindex()
    {

        $salesmanWiseReport = Sale::whereNotNull('sale_by')
            ->whereDate('sale_date', Carbon::today())
            ->get();
        $salesman = Sale::whereNotNull('sale_by')->get();

        return view('pos.report.sales.salesManWiseReport', compact('salesmanWiseReport', 'salesman'));
    }

    public function saelsmanfilter(Request $request)
    {

        $query = Sale::whereNotNull('sale_by');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('salesman_id')) {
            $query->where('sale_by', $request->salesman_id);
        }

        if ($request->filled('day_wise')) {
            if ($request->day_wise == 'daily') {
                $query->whereDate('sale_date', Carbon::today());
            } elseif ($request->day_wise == 'weekly') {
                $query->whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->day_wise == 'monthly') {
                $query->whereMonth('sale_date', Carbon::now()->month);
            }
        }

        $salesmanWiseReport = $query->with('saleBy')->get();
        $salesman = Sale::whereNotNull('sale_by')->get();

        return response()->json([
            'status' => 200,
            'salesmanWiseReport' => $salesmanWiseReport,
            'salesman' => $salesman,
        ]);
    }

    // ***************************** dailySaleReport ***********************//
    public function dailySaleReport()
    {
        $todayDate = Carbon::today();
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $dailySaleSum = Sale::whereDate('sale_date', $todayDate)->sum('change_amount') ?? 0;
            $dailyPaidSaleSum = Sale::whereDate('sale_date', $todayDate)->sum('paid') ?? 0;
            $dailyDueSaleSum = Sale::whereDate('sale_date', $todayDate)->sum('due') ?? 0;
            $sales = Sale::select(
                DB::raw('DATE(sale_date) as sale_dates'),
                DB::raw('SUM(change_amount) as total_sale'),
                DB::raw('SUM(paid) as total_paid'),
                DB::raw('SUM(due) as total_due')
            )
                ->groupBy('sale_dates')
                ->get();
        } else {
            $dailySaleSum = Sale::whereDate('sale_date', $todayDate)->where('branch_id', Auth::user()->branch_id)->sum('change_amount');
            $dailyPaidSaleSum = Sale::whereDate('sale_date', $todayDate)->where('branch_id', Auth::user()->branch_id)->sum('paid') ?? 0;
            $dailyDueSaleSum = Sale::whereDate('sale_date', $todayDate)->where('branch_id', Auth::user()->branch_id)->sum('due') ?? 0;

            $sales = Sale::select(
                DB::raw('DATE(sale_date) as sale_dates'),
                DB::raw('SUM(change_amount) as total_sale'),
                DB::raw('SUM(paid) as total_paid'),
                DB::raw('SUM(due) as total_due')
            )->where('branch_id', Auth::user()->branch_id)
                ->groupBy('sale_dates')
                ->get();
        }

        return view('pos.report.daily_sale.daily-sale-report', compact('dailySaleSum', 'dailyPaidSaleSum', 'dailyDueSaleSum', 'sales'));
    }

    /*
        *****************************
        sallerWaysReport  দয়া করে এই কোড কেউ মুছবেন না।
        *****************************
        */
    public function sallerWaysReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $Sales = Sale::all();
            $salesByPerson = Sale::select(
                'sale_by',
                DB::raw('SUM(change_amount) as total_amount')
            )
                ->groupBy('sale_by')
                ->get();
        } else {
            $salesByPerson = Sale::select(
                'sale_by',
                DB::raw('SUM(change_amount) as total_amount')
            )
                ->where('branch_id', Auth::user()->branch_id)
                ->groupBy('sale_by')
                ->get();
            $Sales = Sale::where('branch_id', Auth::user()->branch_id)
                ->get();
        }

        return view('pos.report.saller_ways.saller_ways_report', compact('Sales', 'salesByPerson'));
    }

    //
    // ***************************** Sale Invoice Discount Report ***********************//
    public function salesInvoiceDiscountReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $invoiceDiscounts = Sale::get();
        } else {
            $invoiceDiscounts = Sale::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.report.discount_report.sales_invoice_discount_report', compact('invoiceDiscounts'));
    }

    public function InvoiceDiscountFilter(Request $request)
    {
        $saleQuery = Sale::query();

        // Branch-wise filter for non-admin roles
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') {
            $saleQuery->where('branch_id', Auth::user()->branch_id);
        }
        if ($request->customer_id != 'Select Customer') {
            $saleQuery->where('customer_id', $request->customer_id);
        }

        if ($request->startDate && $request->endDate) {
            $saleQuery->whereBetween('sale_date', [$request->startDate, $request->endDate]);
        }

        // Execute the query after all filters are applied
        $invoiceDiscounts = $saleQuery->get();
        $saleInvoiceTable = view('pos.report.discount_report.invoice_discount_table', compact('invoiceDiscounts'))->render();

        return response()->json([
            'salesTable' => $saleInvoiceTable,
        ]);
    }

    // ***************************** Sale Items Discount Report ***********************//
    public function salesItemsDiscountReport()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $itemsDiscounts = SaleItem::get();
        } else {
            $itemsDiscounts = SaleItem::whereHas('saleId', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->get();
        }

        return view('pos.report.discount_report.sales_items_discount_report', compact('itemsDiscounts'));
    }

    public function itemsDiscountFilter(Request $request)
    {
        $saleQuery = SaleItem::query();

        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') {
            $saleQuery->whereHas('saleId', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            });
        }

        if ($request->product_id != 'Select Product') {
            $saleQuery->where('product_id', $request->product_id);
        }
        if ($request->startDate && $request->endDate) {
            $saleQuery->whereHas('saleId', function ($query) use ($request) {
                $query->whereBetween('sale_date', [$request->startDate, $request->endDate]);
            });
        }
        $itemsDiscounts = $saleQuery->get();
        $saleInvoiceTable = view('pos.report.discount_report.items_discount_table', compact('itemsDiscounts'))->render();

        return response()->json([
            'salesTable' => $saleInvoiceTable,
        ]);
    }

    // ***************************** Party Ways Discount Report ***********************//
    public function partyDiscountReport()
    {
        // $partyDiscounts = Sale::join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
        // ->select(
        //     'sales.customer_id',
        //     DB::raw('SUM(sales.actual_discount) + SUM(sale_items.discount) as total_discount')
        // )
        // ->groupBy('sales.customer_id') // Group by customer_id only
        // ->get();
        // $sale = Sale::where('customer_id','a')
        // ->groupBy('customer_id')
        // ->get();
        $partyDiscounts = Customer::where('party_type', '!=', 'supplier')
            ->get();

        return view('pos.report.discount_report.party_ways_discount_report', compact('partyDiscounts'));
    }

    // //////////////////////////////commission report///////////////////////////////
    public function affiliateCommissionReport()
    {

        $affiliatorCommission = AffliateCommission::select(
            'affiliator_id',
            DB::raw('SUM(paid_amount) as total_paid'),
            DB::raw('COUNT(sale_id) as total_invoice'),
            DB::raw('SUM(
                            CASE
                                WHEN status IN ("Partial Paid", "Unpaid")
                                THEN commission_amount
                                ELSE 0
                            END
                        ) as total_due')
        )
            ->groupBy('affiliator_id') // Group by affiliator ID
            ->get();

        return view('pos.report.affiliateCommission.commissionReport', compact('affiliatorCommission'));

    }
}// Main
