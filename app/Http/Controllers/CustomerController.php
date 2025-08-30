<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\ServiceSale;
use App\Models\Transaction;
use App\Repositories\RepositoryInterfaces\CustomerInterfaces;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    private $customer_repo;

    public function __construct(CustomerInterfaces $customer_interface)
    {
        $this->customer_repo = $customer_interface;
    }

    public function AddCustomer()
    {
        return view('pos.customer.add_customer');
    }

    // End Method
    public function CustomerStore(Request $request)
    {
        $customer = new Customer;
        $customer->branch_id = Auth::user()->branch_id;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->opening_payable = $request->wallet_balance ?? 0;
        $customer->wallet_balance = $request->wallet_balance ?? 0;
        $customer->total_receivable = $request->wallet_balance ?? 0;
        $customer->party_type = 'customer';
        $customer->created_at = Carbon::now();
        $customer->save();

        $notification = [
            'message' => 'Customer Created Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('customer.view')->with($notification);
        // return redirect()->route('pos.customer.view')->with($notification);
    }

    // End Method
    public function CustomerView()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $customers = Customer::where('party_type', 'customer')->all();
        } else {
            $customers = Customer::where('branch_id', Auth::user()->branch_id)->where('party_type', 'customer')->latest()->get();
        }

        return view('pos.customer.view_customer', compact('customers'));
    }

    //
    public function CustomerEdit($id)
    {
        $customer = $this->customer_repo->EditCustomer($id);

        return view('pos.customer.edit_customer', compact('customer'));
    }

    //
    public function CustomerUpdate(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->branch_id = Auth::user()->branch_id;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        // $customer->opening_receivable = $request->opening_receivable ?? 0;
        // $customer->opening_payable = $request->opening_payable ?? 0;
        $customer->wallet_balance = $request->wallet_balance ?? 0;
        // $customer->total_receivable = $request->total_receivable ?? 0;
        // $customer->total_payable = $request->total_payable ?? 0;
        $customer->updated_at = Carbon::now();
        $customer->save();
        $notification = [
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->route('customer.view')->with($notification);
    }

    // End Method
    public function CustomerDelete($id)
    {
        Customer::findOrFail($id)->delete();
        $notification = [
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }
    // public function CustomerProfile($id)
    // {
    //     $data = Customer::findOrFail($id);
    //     $transactions = Transaction::where('customer_id', $data->id)->get();
    //     $branch = Branch::findOrFail($data->branch_id);
    //     $banks = Bank::latest()->get();
    //     $isCustomer = true;

    //     return view('pos.profiling.profiling', compact('data', 'transactions', 'branch', 'isCustomer', 'banks'));
    // }
    public function getDueInvoice($customerId)
    {

        $transactions = Transaction::with('customer', 'sale')

            ->where('customer_id', $customerId)
            ->where('balance', '>', 0)
            ->where('particulars', 'like', 'Sale%')
            ->get();
        $transactionsUnused = Transaction::where('customer_id', $customerId)
            ->where('status', 'unused')->get();

        $openingDueTransaction = Transaction::where('customer_id', $customerId)
            ->where('particulars', 'OpeningDue') // Condition for OpeningDue
            ->where('balance', '>', 0) // Only include if balance > 0
            // ->where('payment_method','=', Null)
            ->first(['id', 'balance', 'date']); // Ret

        // Extract the values or set defaults if no record matches
        $openingDue = $openingDueTransaction->balance ?? 0;
        $openingDueDate = $openingDueTransaction->date ?? null;
        $openingDueId = $openingDueTransaction->id ?? null;

        $serviceSaleTransaction = ServiceSale::where('customer_id', $customerId)
            ->where('due', '>', 0)
            ->get();

        // dd($transactions);
        return response()->json([
            'openingDue' => $openingDue,  // Add opening due to the response data
            'data' => $transactions,
            'openingDueDate' => $openingDueDate,
            'openingDueId' => $openingDueId,
            'serviceSaleTransaction' => $serviceSaleTransaction,
            'transactionsUnused' => $transactionsUnused,
        ]);
    }

    public function party()
    {
        $parties = Customer::get();

        return view('pos.party.index', compact('parties'));
    } // End Method

    public function partyStore(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|unique:users,phone',
                'opening_receivable' => 'nullable|numeric|max_digits:12',
                'opening_payable' => 'nullable|numeric|max_digits:12',
                'address' => 'nullable|string|max:250',
                'email' => 'nullable|email|unique:users,email',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422, // Unprocessable Entity
                    'errors' => $validator->errors(),
                ]);
            }

            $balance = $request->opening_receivable - $request->opening_payable;

            // Create a new Supplier instance
            $supplier = new Customer;
            $supplier->name = $request->name;
            $supplier->branch_id = Auth::user()->branch_id;
            $supplier->email = $request->email;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->opening_receivable = $request->opening_receivable ?? 0;
            $supplier->opening_payable = $request->opening_payable ?? 0;
            $supplier->total_receivable = $request->opening_receivable ?? 0;
            $supplier->total_payable = $request->opening_payable ?? 0;
            $supplier->wallet_balance = $balance;
            $supplier->party_type = $request->party_type;
            $supplier->save();

            if ($request->opening_payable > 0 || $request->opening_receivable > 0) {
                // transaction table
                $transaction = new Transaction;
                $transaction->date = Carbon::now();
                $transaction->processed_by = Auth::user()->id;
                $transaction->payment_type = 'pay';
                if ($balance > 0) {
                    $transaction->particulars = 'OpeningDue';
                } else {
                    $transaction->particulars = 'OpeningBalance';
                }
                $transaction->supplier_id = $supplier->id;
                $transaction->credit = $request->opening_receivable ?? 0;
                $transaction->debit = $request->opening_payable ?? 0;
                $transaction->balance = $balance;
                $transaction->branch_id = Auth::user()->branch_id;
                $transaction->save();
            }

            // Return a success response
            return response()->json([
                'status' => 200,
                'message' => 'Party saved successfully',
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while saving the supplier',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function partyView()
    {
        $suppliers = Customer::latest()->get();
        $firstSupplier = Customer::orderBy('created_at', 'asc')->first();

        return response()->json([
            'status' => 200,
            'firstSupplier' => $firstSupplier,
            'data' => $suppliers,
        ]);
    }

    public function partyProfile($id)
    {
        $data = Customer::findOrFail($id);
        $transactions = Transaction::where(function ($query) use ($data) {
            $query->where('customer_id', $data->id)
                ->orWhere('supplier_id', $data->id);
        })->get();

        $totalUnusedBalance = Transaction::where('customer_id', $id)
            ->where('status', 'unused')
            ->sum('balance');
        $branch = Branch::findOrFail($data->branch_id);
        $banks = Bank::latest()->get();

        return view('pos.profiling.profiling', compact('data', 'transactions', 'branch', 'banks', 'totalUnusedBalance'));
    }
}
