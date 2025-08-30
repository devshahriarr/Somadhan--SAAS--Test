<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\PosSetting;
use App\Models\ServiceSale;
use App\Models\ServiceSaleItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceSaleController extends Controller
{
    public function index()
    {
        return view('pos.service_sale.service_sale');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $serviceNames = $request->input('serviceName', []);
        $volumes = $request->input('volume', []);
        $prices = $request->input('price', []);
        $totals = $request->input('total', []);
        $formattedDate = Carbon::parse($request->date)->format('Y-m-d') ?? Carbon::parse(Carbon::now())->format('Y-m-d');
        do {
            $invoiceNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (ServiceSale::where('invoice_number', $invoiceNumber)->exists());

        // Loop through the arrays and insert each service
        $due = $request->subTotal - $request->total_payable;

        $serviceSale = ServiceSale::create([
            'branch_id' => Auth::user()->branch_id,
            'customer_id' => $request->customer_id,
            'date' => $formattedDate,
            'invoice_number' => $invoiceNumber,
            'grand_total' => $request->subTotal,
            'paid' => $request->total_payable,
            'due' => $due,
        ]);
        $serviceId = $serviceSale->id;
        foreach ($serviceNames as $key => $serviceName) {
            ServiceSaleItem::create([
                'service_sale_id' => $serviceId,
                'name' => $serviceName,
                'volume' => $volumes[$key],
                'price' => $prices[$key],
                'total' => $totals[$key],
            ]);
        }
        $settings = PosSetting::first();
        // check invoice payment on or off
        $invoice_payment = $settings?->invoice_payment ?? 0;

        // dd($request->all());
        $customer = Customer::findOrFail($request->customer_id);

        $customer->total_receivable += $request->subTotal;
        $customer->total_payable += ($request->total_payable >= $request->subTotal) ? $request->subTotal : $request->total_payable;
        $customer->wallet_balance = $customer->total_receivable - $customer->total_payable;
        $customer->save();

        $accountTransaction = new AccountTransaction;
        $accountTransaction->branch_id = Auth::user()->branch_id;
        $accountTransaction->purpose = 'serviceSale';
        // $accountTransaction->reference_id = $serviceId;
        $accountTransaction->account_id = $request->payment_method;
        $accountTransaction->credit = $request->total_payable;
        $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
        if ($oldBalance) {
            $accountTransaction->balance = $oldBalance->balance + $request->total_payable;
        } else {
            $accountTransaction->balance = $request->total_payable;
        }
        $accountTransaction->created_at = Carbon::now();
        $accountTransaction->save();

        $transaction = new Transaction;
        $transaction->date = $formattedDate;
        $transaction->processed_by = Auth::user()->id;
        $transaction->payment_type = 'receive';
        $transaction->particulars = 'serviceSale#'.$serviceId;
        $transaction->customer_id = $request->customer_id;
        $transaction->payment_method = $request->payment_method;
        $transaction->credit = $request->total_payable;
        $transaction->debit = $request->subTotal;
        $transaction->balance = $request->subTotal - $request->total_payable;
        $transaction->branch_id = Auth::user()->branch_id;
        $transaction->save();

        return response()->json([
            'status' => 200,
            'message' => 'Services added successfully!',
        ]);
    }

    // End Method
    public function view()
    {
        $serviceSales = ServiceSale::all();

        return view('pos.service_sale.service_sale_view', compact('serviceSales'));
    }

    // End Method
    public function invoice($id)
    {
        $sale = ServiceSale::findOrFail($id);
        $customer = Customer::findOrFail($sale->customer_id);

        return view('pos.service_sale.service-sale-invoice', compact('sale', 'customer'));
    }

    public function viewParty()
    {
        $customers = Customer::where('party_type', '!=', 'supplier')->get(); // Adjust fields as needed

        return response()->json([
            'status' => 200,
            'customers' => $customers,
        ]);
    }

    public function viewServiceLedger($id)
    {
        $servicesSales = ServiceSale::findOrFail($id);
        $servicesSaleItems = ServiceSaleItem::where('service_sale_id', $id)->get();
        $transactions = Transaction::where('particulars', 'serviceSale#'.$id)->get();
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $banks = Bank::all();
        } else {
            $banks = Bank::where('branch_id', Auth::user()->branch_id)->get();
        }

        return view('pos.service_sale.service-sale-ledger', compact('servicesSales', 'servicesSaleItems', 'transactions', 'banks'));
    }

    public function ServiceSalePayment(Request $request)
    {
        // dd($request->all());
        $servicesale = ServiceSale::findOrFail($request->data_id);
        $servicesale->paid = $servicesale->paid + $request->payment_balance;
        $servicesale->due = $servicesale->due - $request->payment_balance;
        $servicesale->save();
        // dd($request->all());
        $customer = Customer::findOrFail($request->customer_id);
        $customer->total_payable += $request->payment_balance;
        $customer->wallet_balance -= $request->payment_balance;
        $customer->save();
        //
        $accountTransaction = new AccountTransaction;
        $accountTransaction->branch_id = Auth::user()->branch_id;
        $accountTransaction->purpose = 'serviceSale repayment';
        // $accountTransaction->reference_id = $serviceId;
        $accountTransaction->account_id = $request->account;
        $accountTransaction->credit = $request->payment_balance;
        $oldBalance = AccountTransaction::where('account_id', $request->account)->latest('created_at')->first();
        if ($oldBalance) {
            $accountTransaction->balance = $oldBalance->balance + $request->payment_balance;
        } else {
            $accountTransaction->balance = $request->payment_balance;
        }
        $accountTransaction->created_at = Carbon::now();
        $accountTransaction->save();

        $transaction = new Transaction;
        $transaction->date = Carbon::now();
        $transaction->processed_by = Auth::user()->id;
        $transaction->payment_type = 'receive';
        $transaction->particulars = 'serviceSale#'.$request->data_id;
        $transaction->customer_id = $request->customer_id;
        $transaction->payment_method = $request->account;
        $transaction->credit = $request->payment_balance;
        $transaction->debit = 0;
        $transaction->balance = $transaction->debit - $transaction->credit ?? 0;
        $transaction->branch_id = Auth::user()->branch_id;
        $transaction->save();

        return response()->json([
            'status' => 200,
            'message' => 'Services Payments  successfully!',
        ]);
    }
}// main end
