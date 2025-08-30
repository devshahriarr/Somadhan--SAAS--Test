<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\EmployeeSalary;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Returns;
use App\Models\Sale;
use App\Models\ServiceSale;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\ViaSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'salesman') {
            return redirect()->route('sale.new');
        } else {
            // today summary
            $branchData = [];
            $banks = Bank::all();
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $branches = Branch::all();
                foreach ($branches as $branch) {
                    $branchId = $branch->id;
                    $viaSale = ViaSale::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())->get();
                    $todaySalesData = Sale::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())->get();
                    $todaySales = $todaySalesData->sum('paid') - $viaSale->sum('sub_total');

                    $todayPurchase = Purchase::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())->get();
                    $todayExpanse = Expense::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())->get();
                    $dueCollection = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'SaleDue')
                        ->whereDate('created_at', Carbon::now())
                        ->get();
                    $otherCollection = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'OthersReceive')
                        ->whereDate('created_at', Carbon::now())
                        ->get();
                    $otherPaid = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'OthersPayment')
                        ->whereDate('created_at', Carbon::now())
                        ->get();
                    $purchaseDuePay = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'PurchaseDue')
                        ->whereDate('created_at', Carbon::now())
                        ->get();
                    $todayBalance = AccountTransaction::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())
                        ->latest()
                        ->first();
                    $previousDayBalance = 0;
                    $date = Carbon::now();
                    foreach ($banks as $bank) {
                        $transaction = AccountTransaction::where('branch_id', $branchId)
                            ->where('account_id', $bank->id)
                            ->whereDate('created_at', '<', $date)
                            ->latest('created_at')
                            ->first();

                        if ($transaction) {
                            $previousDayBalance += $transaction->balance;
                        }
                    }
                    $addBalance = AccountTransaction::where('branch_id', $branchId)
                        ->where(function ($query) {
                            $query->where('purpose', 'Add Bank Balance')
                                ->orWhere('purpose', 'Bank');
                        })
                        ->whereDate('created_at', Carbon::now())
                        ->get();
                    $todayEmployeeSalary = EmployeeSalary::where('branch_id', $branchId)
                        ->whereDate('created_at', Carbon::now())->get();
                    $adjustDueCollection = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'Adjust Due Collection')
                        ->where('payment_type', 'receive')
                        ->whereDate('created_at', Carbon::now())
                        ->sum('credit');
                    $todayReturnAmount = Transaction::where('branch_id', $branchId)
                        ->where('particulars', 'Return')
                        ->where('payment_type', 'pay')
                        ->whereDate('created_at', Carbon::now())
                        ->sum('debit');
                    $viaPayment = AccountTransaction::where('branch_id', $branchId)
                        ->where('purpose', 'Via Payment')
                        ->whereDate('created_at', Carbon::now())
                        ->get();

                    $totalIngoing =
                        $previousDayBalance +
                        $todaySales +
                        $dueCollection->sum('credit') +
                        $otherCollection->sum('credit') +
                        $addBalance->sum('credit') +
                        $adjustDueCollection +
                        $viaSale->sum('sub_total');
                    $totalOutgoing =
                        $todayPurchase->sum('paid') +
                        $todayExpanse->sum('amount') +
                        $todayEmployeeSalary->sum('debit') +
                        $todayReturnAmount +
                        $purchaseDuePay->sum('debit') +
                        $otherPaid->sum('debit') +
                        $viaPayment->sum('debit');
                    // Store the data for the current branch
                    $branchData[$branchId] = [
                        'previousDayBalance' => $previousDayBalance,
                        'todaySales' => $todaySales,
                        'todayPurchase' => $todayPurchase->sum('paid'),
                        'todayExpanse' => $todayExpanse->sum('amount'),
                        'dueCollection' => $dueCollection->sum('credit'),
                        'otherCollection' => $otherCollection->sum('credit'),
                        'otherPaid' => $otherPaid->sum('debit'),
                        'purchaseDuePay' => $purchaseDuePay->sum('debit'),
                        'addBalance' => $addBalance->sum('credit'),
                        'todayEmployeeSalary' => $todayEmployeeSalary->sum('debit'),
                        'todayReturnAmount' => $todayReturnAmount,
                        'adjustDueCollection' => $adjustDueCollection,
                        'viaSale' => $viaSale->sum('sub_total'),
                        'viaPayment' => $viaPayment->sum('debit'),
                        'branch' => $branch,
                        'totalIngoing' => $totalIngoing,
                        'totalOutgoing' => $totalOutgoing,

                        // Add other relevant data here
                    ];
                } // End ForEach
            } // end if
            else {
                $branchId = Auth::user()->branch_id;
                $viaSale = ViaSale::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())->get();
                $todaySalesData = Sale::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())->get();
                $todaySales = $todaySalesData->sum('paid') - $viaSale->sum('sub_total');

                $todayPurchase = Purchase::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())->get();
                $todayExpanse = Expense::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())->get();
                $dueCollection = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'SaleDue')
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $otherCollection = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'OthersReceive')
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $otherPaid = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'OthersPayment')
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $purchaseDuePay = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'PurchaseDue')
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $todayBalance = AccountTransaction::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())
                    ->latest()
                    ->first();
                $previousDayBalance = 0;
                $date = Carbon::now();
                foreach ($banks as $bank) {
                    $transaction = AccountTransaction::where('branch_id', $branchId)
                        ->where('account_id', $bank->id)
                        ->whereDate('created_at', '<', $date)
                        ->latest('created_at')
                        ->first();

                    if ($transaction) {
                        $previousDayBalance += $transaction->balance;
                    }
                }
                $addBalance = AccountTransaction::where('branch_id', $branchId)
                    ->where(function ($query) {
                        $query->where('purpose', 'Add Bank Balance')
                            ->orWhere('purpose', 'Bank');
                    })
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $todayEmployeeSalary = EmployeeSalary::where('branch_id', $branchId)
                    ->whereDate('created_at', Carbon::now())->get();
                $viaPayment = AccountTransaction::where('branch_id', $branchId)
                    ->where('purpose', 'Via Payment')
                    ->whereDate('created_at', Carbon::now())
                    ->get();
                $adjustDueCollection = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'Adjust Due Collection')
                    ->where('payment_type', 'receive')
                    ->whereDate('created_at', Carbon::now())
                    ->sum('credit');
                $todayReturnAmount = Transaction::where('branch_id', $branchId)
                    ->where('particulars', 'Return')
                    ->where('payment_type', 'pay')
                    ->whereDate('created_at', Carbon::now())
                    ->sum('debit');

                $totalIngoing =
                    $previousDayBalance +
                    $todaySales +
                    $dueCollection->sum('credit') +
                    $otherCollection->sum('credit') +
                    $addBalance->sum('credit') +
                    $adjustDueCollection +
                    $viaSale->sum('sub_total');
                $totalOutgoing =
                    $todayPurchase->sum('paid') +
                    $todayExpanse->sum('amount') +
                    $todayEmployeeSalary->sum('debit') +
                    $todayReturnAmount +
                    $purchaseDuePay->sum('debit') +
                    $otherPaid->sum('debit') +
                    $viaPayment->sum('debit');
            } // End else

            // today Total Summary
            $todayTotalViaSale = ViaSale::whereDate('created_at', Carbon::now())->sum('sub_total');
            $todaySaleTotal = Sale::whereDate('created_at', Carbon::now())->sum('paid');
            $todayTotalSales = $todaySaleTotal - $todayTotalViaSale;

            $todayTotalPurchase = Purchase::whereDate('created_at', Carbon::now())->sum('paid');
            $todayTotalExpanse = Expense::whereDate('created_at', Carbon::now())->sum('amount');
            $todayTotalDueCollection = Transaction::where('particulars', 'SaleDue')
                ->whereDate('created_at', Carbon::now())
                ->sum('credit');
            $todayTotalOtherCollection = Transaction::where('particulars', 'OthersReceive')
                ->whereDate('created_at', Carbon::now())
                ->sum('credit');
            $todayTotalOtherPaid = Transaction::where('particulars', 'OthersPayment')
                ->whereDate('created_at', Carbon::now())
                ->sum('debit');
            $todayTotalPurchaseDuePay = Transaction::where('particulars', 'PurchaseDue')
                ->whereDate('created_at', Carbon::now())
                ->sum('debit');
            $todayBalance = AccountTransaction::whereDate('created_at', Carbon::now())
                ->latest()
                ->first();
            $previousDayTotalBalance = 0;
            $date = Carbon::now();
            foreach ($banks as $bank) {
                $transaction = AccountTransaction::where('account_id', $bank->id)
                    ->whereDate('created_at', '<', $date)
                    ->latest('created_at')
                    ->first();

                if ($transaction) {
                    $previousDayTotalBalance += $transaction->balance;
                }
            }
            $todayTotalAddBalance = AccountTransaction::where(function ($query) {
                $query->where('purpose', 'Add Bank Balance')
                    ->orWhere('purpose', 'Bank');
            })
                ->whereDate('created_at', Carbon::now())
                ->sum('credit');
            $todayTotalEmployeeSalary = EmployeeSalary::whereDate('created_at', Carbon::now())->sum('debit');
            $todayTotalAdjustDueCollection = Transaction::where('particulars', 'Adjust Due Collection')
                ->where('payment_type', 'receive')
                ->whereDate('created_at', Carbon::now())
                ->sum('credit');
            $todayTotalReturnAmount = Transaction::where('particulars', 'Return')
                ->where('payment_type', 'pay')
                ->whereDate('created_at', Carbon::now())
                ->sum('debit');
            $todayTotalViaPayment = AccountTransaction::where('purpose', 'Via Payment')
                ->whereDate('created_at', Carbon::now())
                ->sum('debit');

            $todayTotalIngoing =
                $previousDayTotalBalance +
                $todayTotalSales +
                $todayTotalDueCollection +
                $todayTotalOtherCollection +
                $todayTotalAddBalance +
                $todayTotalAdjustDueCollection +
                $todayTotalViaSale;
            $todayTotalOutgoing =
                $todayTotalPurchase +
                $todayTotalExpanse +
                $todayTotalEmployeeSalary +
                $todayTotalReturnAmount +
                $todayTotalPurchaseDuePay +
                $todayTotalOtherPaid +
                $todayTotalViaPayment;

            // Total Summary
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                $sales = Sale::all();
                $purchase = Purchase::all();
                $expanse = Expense::all();
                $salary = EmployeeSalary::all();
                $bankLabels = [];
                $grandTotal = 0;
                foreach ($banks as $bank) {
                    $transaction = AccountTransaction::where('account_id', $bank->id)
                        ->latest('created_at')
                        ->first();
                    // dd($transaction);
                    if ($transaction) {
                        $bankData = [
                            'name' => $bank->name,
                            'amount' => number_format($transaction->balance, 2), // Accessing the balance attribute
                        ];
                        array_push($bankLabels, $bankData);
                        $grandTotal += $transaction->balance;
                    }
                }

                $totalCustomerDue = $sales->sum('change_amount') - $sales->sum('paid');
                $totalSupplierDue = $purchase->sum('sub_total') - $purchase->sum('paid');
                $totalBonus = $sales->sum('actual_discount');
            } else {
                $sales = Sale::where('branch_id', Auth::user()->branch_id)->get();
                $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->get();
                $expanse = Expense::where('branch_id', Auth::user()->branch_id)->get();
                $salary = EmployeeSalary::where('branch_id', Auth::user()->branch_id)->get();
                $bankLabels = [];
                $grandTotal = 0;
                foreach ($banks as $bank) {
                    $transaction = AccountTransaction::where('branch_id', Auth::user()->branch_id)
                        ->where('account_id', $bank->id)
                        ->latest('created_at')
                        ->first();
                    // dd($transaction);
                    if ($transaction) {
                        $bankData = [
                            'name' => $bank->name,
                            'amount' => number_format($transaction->balance, 2), // Accessing the balance attribute
                        ];
                        array_push($bankLabels, $bankData);
                        $grandTotal += $transaction->balance;
                    }
                }

                $totalCustomerDue = $sales->sum('change_amount') - $sales->sum('paid');
                $totalSupplierDue = $purchase->sum('sub_total') - $purchase->sum('paid');
                $totalBonus = $sales->sum('actual_discount');
            }

            // weekly update Chart
            $salesByDay = [];
            $salesProfitByDay = [];
            $purchaseByDay = [];
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {

                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->toDateString();
                    $dailySales = Sale::whereDate('sale_date', $date)->sum('receivable');
                    $dailyProfit = Sale::whereDate('sale_date', $date)->sum('profit');
                    $dailyPurchase = Purchase::whereDate('purchase_date', $date)->sum('grand_total');

                    $salesByDay[$date] = $dailySales;
                    $salesProfitByDay[$date] = $dailyProfit;
                    $purchaseByDay[$date] = $dailyPurchase;
                }
            } else {
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->toDateString();
                    $dailySales = Sale::where('branch_id', Auth::user()->branch_id)
                        ->whereDate('sale_date', $date)->sum('receivable');
                    $dailyProfit = Sale::where('branch_id', Auth::user()->branch_id)
                        ->whereDate('sale_date', $date)->sum('profit');
                    $dailyPurchase = Purchase::where('branch_id', Auth::user()->branch_id)
                        ->whereDate('purchase_date', $date)->sum('grand_total');

                    $salesByDay[$date] = $dailySales;
                    $salesProfitByDay[$date] = $dailyProfit;
                    $purchaseByDay[$date] = $dailyPurchase;
                }
            }
            // monthly update chart
            $salesByMonth = [];
            $profitsByMonth = [];
            $purchasesByMonth = [];
            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                for ($i = 0; $i < 12; $i++) {
                    $monthStart = now()->subMonths($i)->startOfMonth();
                    $monthEnd = now()->subMonths($i)->endOfMonth();

                    $monthlySales = Sale::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('receivable');
                    $monthlyProfit = Sale::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('profit');
                    $monthlyPurchase = Purchase::whereBetween('purchase_date', [$monthStart, $monthEnd])->sum(
                        'grand_total',
                    );

                    $salesByMonth[$monthStart->format('Y-m')] = $monthlySales;
                    $profitsByMonth[$monthStart->format('Y-m')] = $monthlyProfit;
                    $purchasesByMonth[$monthStart->format('Y-m')] = $monthlyPurchase;
                }
            } else {
                for ($i = 0; $i < 12; $i++) {
                    $monthStart = now()->subMonths($i)->startOfMonth();
                    $monthEnd = now()->subMonths($i)->endOfMonth();

                    $monthlySales = Sale::where('branch_id', Auth::user()->branch_id)
                        ->whereBetween('sale_date', [$monthStart, $monthEnd])->sum('receivable');
                    $monthlyProfit = Sale::where('branch_id', Auth::user()->branch_id)
                        ->whereBetween('sale_date', [$monthStart, $monthEnd])->sum('profit');
                    $monthlyPurchase = Purchase::where('branch_id', Auth::user()->branch_id)
                        ->whereBetween('purchase_date', [$monthStart, $monthEnd])->sum(
                            'grand_total',
                        );

                    $salesByMonth[$monthStart->format('Y-m')] = $monthlySales;
                    $profitsByMonth[$monthStart->format('Y-m')] = $monthlyProfit;
                    $purchasesByMonth[$monthStart->format('Y-m')] = $monthlyPurchase;
                }
            }
            // //////////////////////////////////--------Card Code Start------------/////////////////////////////////////
            // Branch
            $totalBranch = Branch::count();
            // Customer
            $totalCustomer = Customer::where('party_type', 'customer')->count();
            $todayCustomerCount = Customer::where('party_type', 'customer')->whereDate('created_at', Carbon::today())->count();
            // Supplier
            $totalSupplier = Customer::where('party_type', 'supplier')->count();
            $todaySupplierCount = Customer::where('party_type', 'supplier')->whereDate('created_at', Carbon::today())->count();
            // Stock Value
            $totalStockValuePrices = Product::get();  // Product সম্পর্ক ইজাজ করা

            // $totalStockSalePrice = Stock::with('product')->get();  // Product সম্পর্ক ইজাজ ক
            // Salary
            $TotalPaidSalary = EmployeeSalary::sum('debit');

            // //////////////////////////////////--------Card Code End------------/////////////////////////////////////
            return view('dashboard.dashboard', compact(
                // today summary
                'branchData',
                'totalIngoing',
                'previousDayBalance',
                'todaySales',
                'dueCollection',
                'otherCollection',
                'addBalance',
                'adjustDueCollection',
                'viaSale',
                'totalOutgoing',
                'todayPurchase',
                'todayExpanse',
                'todayEmployeeSalary',
                'todayReturnAmount',
                'purchaseDuePay',
                'otherPaid',
                'viaPayment',

                // today Total Summary
                'todayTotalIngoing',
                'previousDayTotalBalance',
                'todayTotalSales',
                'todayTotalDueCollection',
                'todayTotalOtherCollection',
                'todayTotalAddBalance',
                'todayTotalAdjustDueCollection',
                'todayTotalViaSale',
                'todayTotalOutgoing',
                'todayTotalPurchase',
                'todayTotalExpanse',
                'todayTotalEmployeeSalary',
                'todayTotalReturnAmount',
                'todayTotalPurchaseDuePay',
                'todayTotalOtherPaid',
                'todayTotalViaPayment',
                // total Summary
                'sales',
                'purchase',
                'expanse',
                'salary',
                'bankLabels',
                'grandTotal',
                'totalCustomerDue',
                'totalSupplierDue',
                'totalBonus',

                // weekly summary
                'salesByDay',
                'salesProfitByDay',
                'purchaseByDay',

                // monthly summary
                'salesByMonth',
                'profitsByMonth',
                'purchasesByMonth',
                // <!----------- Card Code---------> //
                // Branch
                'totalBranch',
                // Customer
                'totalCustomer',
                'todayCustomerCount',
                // Supplier
                'totalSupplier',
                'todaySupplierCount',
                // Stoct Value
                'totalStockValuePrices',
                // Salary
                'TotalPaidSalary'
            ));
        }
    }

    // end else
    // //////////////------------------------------Card All Method Fetch  -----------------------------///////
    // Total Sale Fetch Data
    public function filterTotalSales(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Sale::query();

        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('sale_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;
        }

        $totalsalesTotal = $salesQuery->sum('change_amount') ?? 0; // Assuming 'amount' is the sales field.

        return response()->json(['totalsalesTotal' => $totalsalesTotal]);
    }

    // Paid Sale Fetch Data
    public function filterPaidSales(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Sale::query();

        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('sale_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;
        }

        $paidSalesTotal = $salesQuery->sum('paid') ?? 0; // Assuming 'amount' is the sales field.

        return response()->json(['paidSalesTotal' => $paidSalesTotal]);
    }

    // Purchase Fetch Data
    public function filterDashboardPurchase(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Purchase::query();

        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('purchase_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('purchase_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('purchase_date', Carbon::now()->month)
                    ->whereYear('purchase_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('purchase_date', Carbon::now()->month)
                    ->whereYear('purchase_date', Carbon::now()->year);
                break;
        }

        $purchaseTotal = $salesQuery->sum('paid') ?? 0; // Assuming 'amount' is the sales field.

        return response()->json(['purchaseTotal' => $purchaseTotal]);
    }

    // Expense Fetch Data
    public function filterDashboardExpense(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Expense::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('expense_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('expense_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('expense_date', Carbon::now()->month)
                    ->whereYear('expense_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('expense_date', Carbon::now()->month)
                    ->whereYear('expense_date', Carbon::now()->year);
                break;
        }

        $expnseTotal = $salesQuery->sum('amount') ?? 0; // Assuming 'amount' is the sales field.

        return response()->json(['expenseTotal' => $expnseTotal]);
    }

    // Sale Due Collection Fetch Data
    public function filterDashboardDueCollection(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Sale::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('sale_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('sale_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('sale_date', Carbon::now()->month)
                    ->whereYear('sale_date', Carbon::now()->year);
                break;
        }

        $salesQuerys = $salesQuery->where('paid', '>', 'change_amount');

        $dueCollectTotal = $salesQuerys->get()->reduce(function ($carry, $sale) {
            // $adjustedChangeAmount = $sale->tax ? $sale->change_amount + $sale->tax : $sale->change_amount;

            if ($sale->paid > $sale->change_amount) {
                return $carry + ($sale->paid - $sale->change_amount);
            }

            return $carry;
        }, 0);

        return response()->json(['dueCollectTotal' => $dueCollectTotal]);
    }

    // Return
    public function filterDashboardReturn(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = Returns::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('return_date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('return_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('return_date', Carbon::now()->month)
                    ->whereYear('return_date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('return_date', Carbon::now()->month)
                    ->whereYear('return_date', Carbon::now()->year);
                break;
        }

        $returnTotal = $salesQuery->count() ?? 0; // Assuming 'amount' is the sales field.

        return response()->json(['returnTotal' => $returnTotal]);
    }

    // /Bank
    public function filterDashboardBank(Request $request)
    {
        // dd($request->all());
        if ($request->branchId) {
            $branchId = $request->input('branchId', $request->branchId);
        } else {
            $branchId = $request->input('branchId', Auth::user()->branch_id);
        }

        $totalBranchesBank = Bank::where('branch_id', $branchId)->count(); // Adjust field names as per your database structure
        // Calculate the total bank amount for the branch
        $banks = Bank::where('branch_id', $branchId)->get();
        $totalBalance = 0;
        $banks->load('accountTransaction');
        // Add latest transaction to each bank
        foreach ($banks as $bank) {
            $bank->latest_transaction = $bank->accountTransaction()->latest()->first();
            if ($bank->latest_transaction) {
                $totalBalance += $bank->latest_transaction->balance;
            }
        }

        return response()->json([
            'totalBranchesBank' => $totalBranchesBank,
            'totalBankAmount' => $totalBalance,
        ]);
    }

    public function filterServiceTotalSale(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = ServiceSale::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;
        }

        $totalServceSale = $salesQuery->sum('grand_total') ?? 0;

        return response()->json(['totalServceSale' => $totalServceSale]);
    }

    public function filterServicePaidSale(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = ServiceSale::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;
        }

        $paidServceSale = $salesQuery->sum('paid') ?? 0;

        return response()->json(['paidServceSale' => $paidServceSale]);
    }

    public function filterServiceDueSale(Request $request)
    {
        $filter = $request->input('filter', 'today'); // Default to 'today'
        $salesQuery = ServiceSale::query();
        switch ($filter) {
            case 'today':
                $salesQuery->whereDate('date', Carbon::today());
                break;

            case 'weekly':
                $salesQuery->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;

            case 'monthly':
                $salesQuery->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;
        }

        $dueServceSale = $salesQuery->sum('due') ?? 0;

        return response()->json(['dueServceSale' => $dueServceSale]);
    }
}
