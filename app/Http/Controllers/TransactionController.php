<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Investor;
use App\Models\PosSetting;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\ServiceSale;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function TransactionAdd()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $supplier = Customer::where('party_type', 'supplier')->latest()->get();
            $customer = Customer::where('party_type', 'customer')->latest()->get();
            $paymentMethod = Bank::all();
            $investors = Investor::latest()->get();
            $transaction = Transaction::latest()->get();
        } else {
            $supplier = Customer::where('party_type', 'supplier')->where('branch_id', Auth::user()->branch_id)->latest()->get();
            $customer = Customer::where('party_type', 'customer')->where('branch_id', Auth::user()->branch_id)->latest()->get();
            $paymentMethod = Bank::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $investors = Investor::where('branch_id', Auth::user()->branch_id)->latest()->get();
            $transaction = Transaction::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.transaction.transaction_add', compact('paymentMethod', 'supplier', 'customer', 'transaction', 'investors'));
    }

    //
    // public function TransactionView(){
    //     return view('pos.transaction.transaction_view');
    // }
    public function getDataForAccountId(Request $request)
    {
        $accountId = $request->input('id');
        $account_type = $request->input('account_type');
        // dd($account_type);
        // dd($accountId);
        // if ($account_type == "supplier") {
        //     $info = Customer::findOrFail($accountId);
        //     $count = Purchase::where('supplier_id', $accountId)->where('due', '>', 0)->count();
        // } elseif ($account_type == "customer") {
        //     $info = Customer::findOrFail($accountId);
        //     $count = '-';
        // }
        if ($account_type == 'other') {
            $info = Investor::findOrFail($accountId);
            $count = '-';
        }

        return response()->json([
            'info' => $info,
            'count' => $count,
        ]);
    }

    // End function
    public function TransactionStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'payment_method' => 'required',
            'amounts' => 'required',
            'debit' => ['numeric', 'max:12'],
            'credit' => ['numeric', 'max:12'],
        ]);
        $paymentMethods = $request->input('payment_method', []);
        $amounts = $request->input('amounts', []);
        $notification = ['message' => '', 'alert-type' => ''];

        if ($request->account_type == 'other') {
            foreach ($paymentMethods as $paymentMethod) {
                $amount = isset($amounts[$paymentMethod]) ? (float) $amounts[$paymentMethod] : 0;

                // Skip if amount is invalid
                if ($amount <= 0) {
                    $notification = [
                        'warning' => "Invalid or zero amount for payment method ID: $paymentMethod",
                        'alert-type' => 'warning',
                    ];

                    return redirect()->back()->with($notification);
                }

                // Fetch the latest balance for the payment method
                $oldBalance = AccountTransaction::where('account_id', $paymentMethod)
                    ->latest('created_at')
                    ->first();

                if ($request->transaction_type == 'pay') {
                    // Check if balance is sufficient
                    if (! $oldBalance || $oldBalance->balance < $amount) {
                        $notification = [
                            'warning' => "Insufficient balance for payment method ID: $paymentMethod",
                            'alert-type' => 'warning',
                        ];

                        return redirect()->back()->with($notification);
                    }

                    // Create Transaction record
                    Transaction::create([
                        'branch_id' => Auth::user()->branch_id,
                        'date' => $request->date,
                        'processed_by' => Auth::user()->id,
                        'payment_type' => $request->transaction_type,
                        'particulars' => 'OthersPayment',
                        'debit' => $amount,
                        'payment_method' => $paymentMethod,
                        'note' => $request->note,
                        'balance' => $amount,
                        'others_id' => $request->account_id,
                    ]);

                    // Update Investor
                    $investor = Investor::findOrFail($request->account_id);
                    $currentBalance = $investor->wallet_balance;
                    $newBalance = $currentBalance - $amount;
                    $oldDebit = $investor->debit + $amount;
                    $investor->update([
                        'type' => $request->type,
                        'debit' => $oldDebit,
                        'wallet_balance' => $newBalance,
                    ]);

                    // Create AccountTransaction
                    AccountTransaction::create([
                        'branch_id' => Auth::user()->branch_id,
                        'reference_id' => $investor->id,
                        'processed_by' => Auth::user()->id,
                        'purpose' => 'OthersPayment',
                        'account_id' => $paymentMethod,
                        'debit' => $amount,
                        'balance' => $oldBalance->balance - $amount,
                        'created_at' => Carbon::now(),
                    ]);
                } elseif ($request->transaction_type == 'receive') {
                    // Create Transaction record
                    Transaction::create([
                        'branch_id' => Auth::user()->branch_id,
                        'date' => $request->date,
                        'processed_by' => Auth::user()->id,
                        'payment_type' => $request->transaction_type,
                        'particulars' => 'OthersReceive',
                        'credit' => $amount,
                        'payment_method' => $paymentMethod,
                        'note' => $request->note,
                        'balance' => -$amount,
                        'others_id' => $request->account_id,
                    ]);

                    // Update Investor
                    $investor = Investor::findOrFail($request->account_id);
                    $currentBalance = $investor->wallet_balance;
                    $newBalance = $currentBalance + $amount;
                    $oldCredit = $investor->credit + $amount;
                    $investor->update([
                        'type' => $request->type,
                        'credit' => $oldCredit,
                        'wallet_balance' => $newBalance,
                    ]);

                    // Create AccountTransaction

                    $accountTransaction = new AccountTransaction;
                    $accountTransaction->branch_id = Auth::user()->branch_id;
                    $accountTransaction->reference_id = $investor->id;
                    $accountTransaction->processed_by =  Auth::user()->id;
                    $accountTransaction->purpose = 'OthersReceive';
                    $accountTransaction->account_id = $paymentMethod;
                    $accountTransaction->credit = $amount;
                    $accountTransaction->balance = $oldBalance ? $oldBalance->balance + $amount : $amount;
                    $accountTransaction->created_at = Carbon::now();
                    $accountTransaction->save();
                }
            }

            // Success notification
            $notification = [
                'message' => 'Transactions Processed Successfully',
                'alert-type' => 'info',
            ];

            return redirect()->back()->with($notification);
        } elseif ($request->account_type == 'party') {

            // foreach ($paymentMethods as $paymentMethod) {
            //     $amount = isset($amounts[$paymentMethod]) ? (float) $amounts[$paymentMethod] : 0;
            //     //Here change
            //     $oldBalance = AccountTransaction::where('account_id', $paymentMethod)->latest('created_at')->first();
            //     if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >= $amount) {
            //         //--Here change End--//
            //         $party = Customer::findOrFail($request->account_id);
            //         // dd($party->party_type);
            //         if ($request->transaction_type == 'receive') {
            //             $currentBalance = $party->wallet_balance;
            //             $currentBalance = $currentBalance ?? 0;
            //             $newBalance = floatval($currentBalance) + floatval($amount);
            //             $party->wallet_balance = $newBalance;
            //             $newPayble = $party->total_payable ?? 0;
            //             $updatePaybele = floatval($newPayble) - floatval($amount);
            //             // dd($tracBalance->balance);
            //             $party->total_payable = $updatePaybele;
            //             $tracBalance = Transaction::where('customer_id', $party->id)->latest()->first();
            //             if ($tracBalance !== null) {
            //                 $debitBalance = floatval($tracBalance->balance);
            //                 $updateTraBalance = ($debitBalance ?? 0) + floatval($amount);
            //             } else {
            //                 $updateTraBalance = floatval($amount); // Set to default value or handle
            //             }
            //             // dd($updateTraBalance);
            //             $transaction = Transaction::create([
            //                 'branch_id' => Auth::user()->branch_id,
            //                 'date' => $request->date,
            //                 'payment_type' => $request->transaction_type,
            //                 'particulars' => 'party receive',
            //                 'debit' => $amount,
            //                 'payment_method' => $paymentMethod,
            //                 'balance' => $updateTraBalance,
            //                 'note' => $request->note,
            //                 'customer_id' => $request->account_id
            //             ]);
            //             $party->update([
            //                 'wallet_balance' => $newBalance,
            //                 'total_payable' => $updatePaybele
            //             ]);
            //             //account Transaction Crud//
            //             $accountTransaction = new AccountTransaction;
            //             $accountTransaction->branch_id =  Auth::user()->branch_id;
            //             $accountTransaction->reference_id = $transaction->id;
            //             $accountTransaction->purpose =  'party receive';
            //             $accountTransaction->account_id =  $paymentMethod;
            //             $accountTransaction->debit = $amount;
            //             $oldBalance = AccountTransaction::where('account_id', $paymentMethod)->latest('created_at')->first();
            //             $accountTransaction->balance = $oldBalance->balance + $amount;
            //             $accountTransaction->created_at = Carbon::now();
            //             $accountTransaction->save();
            //             $notification = [
            //                 'message' => 'Transaction Receive Payment Successful',
            //                 'alert-type' => 'info'
            //             ];
            //             return redirect()->back()->with($notification);
            //         } elseif ($request->transaction_type == 'pay') {
            //             $currentBalance = $party->wallet_balance;
            //             $currentBalance = $currentBalance ?? 0;
            //             $newBalance = floatval($currentBalance) - floatval($amount);
            //             $party->wallet_balance = $newBalance;
            //             $newPayble = $party->total_payable ?? 0;
            //             $updatePaybele = floatval($newPayble) + floatval($amount);
            //             $party->total_payable = $updatePaybele;
            //             $tracBalance = Transaction::where('customer_id', $party->id)->latest()->first();
            //             if ($tracBalance !== null) {
            //                 $debitBalance = floatval($tracBalance->balance);
            //                 $updateTraBalance = ($debitBalance ?? 0) - floatval($amount);
            //             } else {
            //                 $updateTraBalance = floatval($amount);
            //             }
            //             $transaction = Transaction::create([
            //                 'branch_id' => Auth::user()->branch_id,
            //                 'date' => $request->date,
            //                 'payment_type' => $request->transaction_type,
            //                 'particulars' => 'party pay',
            //                 'credit' => $amount,
            //                 'payment_method' => $paymentMethod,
            //                 'balance' => $updateTraBalance,
            //                 'note' => $request->note,
            //                 'customer_id' => $request->account_id
            //             ]);
            //             $party->update([
            //                 'wallet_balance' => $newBalance,
            //                 'total_payable' => $updatePaybele
            //             ]);
            //             //account Transaction Crud//
            //             $accountTransaction = new AccountTransaction;
            //             $accountTransaction->branch_id =  Auth::user()->branch_id;
            //             $accountTransaction->reference_id = $transaction->id;
            //             $accountTransaction->purpose =  'party pay';
            //             $accountTransaction->account_id =  $paymentMethod;
            //             $accountTransaction->credit = $amount;
            //             $oldBalance = AccountTransaction::where('account_id', $paymentMethod)->latest('created_at')->first();
            //             $accountTransaction->balance = $oldBalance->balance - $amount;
            //             $accountTransaction->created_at = Carbon::now();
            //             $accountTransaction->save();
            //             $notification = [
            //                 'message' => 'Transaction Payment Successful',
            //                 'alert-type' => 'info'
            //             ];
            //             return redirect()->back()->with($notification);
            //         } else {
            //             $notification = [
            //                 'warning' => 'Your account Balance is low Please Select Another account',
            //                 'alert-type' => 'warning'
            //             ];
            //             return redirect()->back()->with($notification);
            //         }
            //     }
            //     //End//
            // }
            $party = Customer::findOrFail($request->account_id);
            $currentBalance = $party->wallet_balance ?? 0;
            $newPayble = $party->total_payable ?? 0;
            $newReceivable = $party->total_receivable ?? 0;
            $notifications = [];
            $totalAmount = 0;
            // Start a database transaction
            DB::beginTransaction();
            try {
                foreach ($paymentMethods as $paymentMethod) {
                    $amount = isset($amounts[$paymentMethod]) ? (float) $amounts[$paymentMethod] : 0;

                    // Skip if amount is 0 or invalid
                    if ($amount <= 0) {
                        $notifications[] = [
                            'warning' => "Invalid amount for payment method {$paymentMethod}. Skipping.",
                            'alert-type' => 'warning',
                        ];

                        continue;
                    }

                    // Check account balance
                    $oldBalance = AccountTransaction::where('account_id', $paymentMethod)
                        ->latest('created_at')
                        ->first();

                    if (! $oldBalance || $oldBalance->balance < $amount) {
                        $notifications[] = [
                            'warning' => "Insufficient balance for payment method {$paymentMethod}. Skipping.",
                            'alert-type' => 'warning',
                        ];

                        continue;
                    }

                    // Calculate new balances based on transaction type
                    if ($request->transaction_type == 'receive') {
                        $particulars = 'party receive';
                        $debit = $amount;
                        $credit = 0;
                        $accountBalanceChange = $oldBalance->balance + $amount;
                        $totalAmount += $amount; //
                        $updatePaybele = floatval($newPayble) + floatval($totalAmount);
                        $newBalance = floatval($newReceivable) - floatval($updatePaybele);
                        $runningTraBalance = -$amount;
                        $party->update([
                            'wallet_balance' => $newBalance,
                            'total_payable' => $updatePaybele,

                        ]);
                    } elseif ($request->transaction_type == 'pay') {
                        $particulars = 'party pay';
                        $debit = 0;
                        $credit = $amount;
                        $accountBalanceChange = $oldBalance->balance - $amount;
                        $totalAmount += $amount;
                        // dd( $totalAmount );
                        //  $newBalance = floatval($currentBalance) - floatval($totalAmount);
                        $updateReceivable = floatval($newReceivable) + floatval($totalAmount);
                        $newBalance = floatval($updateReceivable) - floatval($newPayble);
                        $runningTraBalance = $amount;
                        $party->update([
                            'wallet_balance' => $newBalance,
                            'total_receivable' => $updateReceivable,
                        ]);
                    } else {
                        throw new Exception("Invalid transaction type for payment method {$paymentMethod}.");
                    }

                    // Update transaction balance
                    // $tracBalance = Transaction::where('customer_id', $party->id)->latest()->first();
                    // $updateTraBalance = $tracBalance ? floatval($tracBalance->balance) + ($request->transaction_type == 'receive' ? $amount : -$amount) : floatval($amount);

                    // Create transaction
                    $transaction = Transaction::create([
                        'branch_id' => Auth::user()->branch_id,
                        'date' => $request->date,
                        'payment_type' => $request->transaction_type,
                        'particulars' => $particulars,
                        'debit' => $debit,
                        'credit' => $credit,
                        'payment_method' => $paymentMethod,
                        'balance' => $runningTraBalance,
                        'note' => $request->note,
                        'customer_id' => $request->account_id,
                        'status' => 'unused',
                    ]);

                    // Update party details

                    // Create account transaction
                    $accountTransaction = new AccountTransaction;
                    $accountTransaction->branch_id = Auth::user()->branch_id;
                    $accountTransaction->reference_id = $transaction->id;
                     $accountTransaction->processed_by =  Auth::user()->id;
                    $accountTransaction->purpose = $particulars;
                    $accountTransaction->account_id = $paymentMethod;
                    $accountTransaction->debit = $debit;
                    $accountTransaction->credit = $credit;
                    $accountTransaction->balance = $accountBalanceChange;
                    $accountTransaction->created_at = Carbon::now();
                    $accountTransaction->save();

                    $notifications[] = [
                        'message' => "Transaction for payment method {$paymentMethod} successful.",
                        'alert-type' => 'info',
                    ];
                }

                // Commit the transaction
                DB::commit();

                // Return all notifications
                return redirect()->back()->with($notifications[0]); // Return the first notification for simplicity
            } catch (Exception $e) {
                // Rollback transaction on error
                DB::rollBack();

                return redirect()->back()->with([
                    'warning' => 'Transaction failed: '.$e->getMessage(),
                    'alert-type' => 'error',
                ]);
            }
        }
    }

    // else if ($request->account_type == 'customer') {
    //     //---Customer Table Update---//
    //     $customer = Customer::findOrFail($request->account_id);
    //     $newBalance = $customer->wallet_balance - $request->amount;
    //     $newPayable = $customer->total_payable + $request->amount;
    //     $customer->update([
    //         'wallet_balance' => $newBalance,
    //         'total_payable' => $newPayable
    //     ]);

    //     // transaction crud Update
    //     $tracsBalance = Transaction::where('customer_id', $customer->id)->latest()->first();
    //     $transBalance = $tracsBalance->balance ?? 0;
    //     $newTrasBalance = $transBalance + $request->amount;
    //     $transaction = Transaction::create([
    //         'branch_id' => Auth::user()->branch_id,
    //         'date' => $request->date,
    //         'payment_type' => 'receive',
    //         'particulars' => 'SaleDue',
    //         'credit' => $request->amount,
    //         'payment_method' => $request->payment_method,
    //         'note' => $request->note,
    //         'balance' => $newTrasBalance,
    //         'customer_id' => $request->account_id
    //     ]);

    //     //account Transaction Crud
    //     $accountTransaction = new AccountTransaction;
    //     $accountTransaction->branch_id =  Auth::user()->branch_id;
    //     $accountTransaction->reference_id = $transaction->id;
    //     $accountTransaction->purpose =  'SaleDue';
    //     $accountTransaction->account_id =  $request->payment_method;
    //     $accountTransaction->credit = $request->amount;
    //     $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
    //     if ($oldBalance) {
    //         $accountTransaction->balance = $oldBalance->balance + $request->amount;
    //     } else {
    //         $accountTransaction->balance = $request->amount;
    //     }
    //     $accountTransaction->created_at = Carbon::now();
    //     $accountTransaction->save();

    //     //-------------------SMS--------------------//
    //     $settings = PosSetting::first();
    //     $transaction_sms = $settings->transaction_sms;
    //     if($transaction_sms == 1){
    //     $number = $customer->phone;
    //     $api_key = "0yRu5BkB8tK927YQBA8u";
    //     $senderid = "8809617615171";
    //     $message = "Dear {$customer->name}, your transaction has been successfully completed. Received Amount: {$request->amount}. Thank you.";
    //     $url = "http://bulksmsbd.net/api/smsapi";
    //     $data = [
    //         "api_key" => $api_key,
    //         "number" => $number,
    //         "senderid" => $senderid,
    //         "message" => $message,
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
    //     $response = json_decode($response, true);
    //  }
    //     //-------------------SMS--------------------//

    //     $notification = [
    //         'message' => 'Transaction Payment Successful',
    //         'alert-type' => 'info'
    //     ];

    //     return redirect()->back()->with($notification);
    // } else if ($request->account_type == 'other') {
    //     // dd($request->transaction_type);
    //     $tracsBalances = Transaction::where('others_id', $request->account_id)->latest()->first();
    //     $currentBalance = $tracsBalances->balance ?? 0;
    //     $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
    //     if ($request->transaction_type == 'pay') {
    //         if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >= $request->amount) {
    //             $payBalance = $currentBalance - $request->amount;
    //             // dd($currentBalance - $request->amount);
    //             $transaction = Transaction::create([
    //                 'branch_id' => Auth::user()->branch_id,
    //                 'date' => $request->date,
    //                 'payment_type' => $request->transaction_type,
    //                 'particulars' => 'OthersPayment',
    //                 'debit' => $request->amount,
    //                 'payment_method' => $request->payment_method,
    //                 'note' => $request->note,
    //                 'balance' => $payBalance,
    //                 'others_id' => $request->account_id,
    //             ]);
    //             $investor = Investor::findOrFail($request->account_id);
    //             $currentBalance = $investor->wallet_balance;
    //             $newBalance = $currentBalance  - $request->amount;
    //             $oldDebit = $investor->debit  + $request->amount;
    //             $investor->update([
    //                 'type' => $request->type,
    //                 'debit' =>  $oldDebit,
    //                 'wallet_balance' => $newBalance,
    //             ]);
    //             // account transaction
    //             $accountTransaction = new AccountTransaction;
    //             $accountTransaction->branch_id =  Auth::user()->branch_id;
    //             $accountTransaction->reference_id = $investor->id;
    //             $accountTransaction->purpose =  'OthersPayment';
    //             $accountTransaction->account_id =  $request->payment_method;
    //             $accountTransaction->debit = $request->amount;
    //             $accountTransaction->balance = $oldBalance->balance - $request->amount;
    //             $accountTransaction->created_at = Carbon::now();
    //             $accountTransaction->save();

    //             $notification = [
    //                 'message' => 'Transaction Others Successful',
    //                 'alert-type' => 'info'
    //             ];
    //             return redirect()->back()->with($notification);
    //         } else {
    //             $notification = [
    //                 'warning' => 'Your account Balance is low Please Select Another account',
    //                 'alert-type' => 'warning'
    //             ];
    //             return redirect()->back()->with($notification);
    //         }
    //     } else if ($request->transaction_type == 'receive') {
    //         $receiveBalance = $currentBalance + $request->amount;
    //         $transaction = Transaction::create([
    //             'branch_id' => Auth::user()->branch_id,
    //             'date' => $request->date,
    //             'payment_type' => $request->transaction_type,
    //             'particulars' => 'OthersReceive',
    //             'credit' => $request->amount,
    //             'payment_method' => $request->payment_method,
    //             'note' => $request->note,
    //             'balance' => $receiveBalance,
    //             'others_id' => $request->account_id,
    //         ]);
    //         $investor = Investor::findOrFail($request->account_id);
    //         $currentBalance = $investor->wallet_balance;
    //         $newBalance = $currentBalance  + $request->amount;
    //         $oldCredit = $investor->credit + $request->amount;
    //         $investor->update([
    //             'type' => $request->type,
    //             'credit' =>  $oldCredit,
    //             'wallet_balance' => $newBalance,
    //         ]);

    //         // Account Transaction
    //         $accountTransaction = new AccountTransaction;
    //         $accountTransaction->branch_id =  Auth::user()->branch_id;
    //         $accountTransaction->reference_id = $investor->id;
    //         if($request->type == 'add-balance'){
    //          $accountTransaction->purpose = 'Add Bank Balance';
    //         }else{
    //             $accountTransaction->purpose =  'OthersReceive';
    //         }
    //         $accountTransaction->account_id =  $request->payment_method;
    //         $accountTransaction->credit = $request->amount;
    //         $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
    //         if ($oldBalance) {
    //             $accountTransaction->balance = $oldBalance->balance + $request->amount;
    //         } else {
    //             $accountTransaction->balance = $request->amount;
    //         }
    //         $accountTransaction->created_at = Carbon::now();
    //         $accountTransaction->save();

    //         $notification = [
    //             'message' => 'Transaction Others Successful',
    //             'alert-type' => 'info'
    //         ];
    //         return redirect()->back()->with($notification);
    //     }
    // }
    // } //
    public function TransactionDelete($id)
    {
        Transaction::find($id)->delete();
        $notification = [
            'message' => 'Transaction Deleted Successfully',
            'alert-type' => 'info',
        ];

        return redirect()->back()->with($notification);
    }

    //
    public function TransactionFilterView(Request $request)
    {
        // $customerName="";
        // $suplyerName="";
        // if($request->filterCustomer == 'Select Customer'){
        //     $customerName = null;
        // }
        // if($request->filterSupplier == 'Select Supplier'){
        //     $suplyerName = null;
        // }
        $transaction = Transaction::when($request->filterCustomer != 'Select Customer', function ($query) use ($request) {
            return $query->where('customer_id', $request->filterCustomer);
        })
            ->when($request->filterSupplier != 'Select Supplier', function ($query) use ($request) {
                return $query->where('supplier_id', $request->filterSupplier);
            })
            ->when($request->startDate && $request->endDate, function ($query) use ($request) {
                return $query->whereBetween('date', [$request->startDate, $request->endDate]);
            })
            ->get();

        return view('pos.transaction.transaction-filter-rander-table', compact('transaction'))->render();
    }

    public function TransactionInvoiceReceipt($id)
    {
        $transaction = Transaction::findOrFail($id);

        return view('pos.transaction.invoice', compact('transaction'));
    }

    public function InvestmentStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->passes()) {
            $investor = new Investor;
            $investor->branch_id = Auth::user()->branch_id;
            $investor->name = $request->name;
            $investor->phone = $request->phone;
            $investor->created_at = Carbon::now();
            $investor->save();

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Save',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function GetInvestor()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $data = Investor::latest()->get();
        } else {
            $data = Investor::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully save',
            'allData' => $data,
        ]);
    }

    public function getParty()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $data = Customer::latest()->get();
        } else {
            $data = Customer::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully save',
            'allData' => $data,
        ]);
    }

    public function InvestorInvoice($id)
    {
        $investors = Investor::findOrFail($id);

        return view('pos.investor.investor-invoice', compact('investors'));
    }

    public function invoicePaymentStore(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'payment_balance' => 'required',
            'account' => 'required',
        ]);

        if ($validator->passes()) {

            // transaction
            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = Carbon::now();
            // $transaction->processed_by =  Auth::user()->id;
            $transaction->payment_type = 'receive';
            $transaction->payment_method = $request->account;
            $transaction->credit = $request->payment_balance;
            $transaction->debit = 0;
            $transaction->balance = $transaction->debit - $transaction->credit ?? 0;
            $transaction->note = $request->note;

            // Account Transaction Table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->account_id = $request->account;
            $accountTransaction->processed_by =  Auth::user()->id;
            $oldBalance = AccountTransaction::where('account_id', $request->account)->latest('created_at')->first();

            if ($request->isCustomer === 'customer') {
                // transaction
                $transaction->particulars = 'SaleDue';
                $transaction->customer_id = $request->data_id;

                // Customer Table
                $customer = Customer::findOrFail($request->data_id);
                $newBalance = $customer->wallet_balance - $request->payment_balance;
                $newPayable = $customer->total_payable + $request->payment_balance;
                $customer->update([
                    'wallet_balance' => $newBalance,
                    'total_payable' => $newPayable,
                ]);

                $accountTransaction->purpose = 'SaleDue';
                $accountTransaction->credit = $request->payment_balance;
                if ($oldBalance) {
                    $accountTransaction->balance = $oldBalance->balance + $request->payment_balance;
                } else {
                    $accountTransaction->balance = $request->payment_balance;
                }
                // -------------------SMS--------------------//
                $settings = PosSetting::first();
                $invoicePayment_sms = $settings->profile_payment_sms;
                if ($invoicePayment_sms == 1) {
                    $number = $customer->phone;
                    $api_key = '0yRu5BkB8tK927YQBA8u';
                    $senderid = '8809617615171';
                    $message = "Dear {$customer->name}, your invoice payment has been successfully completed. Paid Amount: {$request->payment_balance}. Thank you for your payment.";
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
                // -------------------SMS--------------------//
            } else {
                if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >= $request->payment_balance) {
                    // transaction update
                    $transaction->particulars = 'PurchaseDue';
                    $transaction->supplier_id = $request->data_id;

                    // supplier Crud
                    $supplier = Customer::findOrFail($request->data_id);
                    $newBalance = $supplier->wallet_balance - $request->payment_balance;
                    $newPayable = $supplier->total_payable + $request->payment_balance;
                    $supplier->update([
                        'wallet_balance' => $newBalance,
                        'total_payable' => $newPayable,
                    ]);

                    $accountTransaction->purpose = 'PurchaseDue';
                    $accountTransaction->debit = $request->payment_balance;
                    $accountTransaction->balance = $oldBalance->balance - $request->payment_balance;
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Your account Balance is low Please Select Another account or Add Balance on your Account',
                    ]);
                }
            }
            $accountTransaction->save();
            $transaction->save();

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Payment',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function linkInvoicePaymentStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_balance' => 'required',
            'account' => 'required',
        ]);

        if ($validator->passes()) {

            $saleIds = json_decode($request->input('sale_ids'), true);
            $transactionIds = json_decode($request->input('transaction_ids'), true);
            $serviceIds = json_decode($request->input('Service_ids'), true);
            $unused_ids = json_decode($request->input('unused_ids'), true);
            $transaction = Transaction::whereIn('id', $transactionIds)->first(); // Get the first transaction
            //  dd($unused_ids);
            $latestFinalBalance = (float) $request->payment_balance;
            $customer = Customer::findOrFail($request->data_id); // Customer খুঁজে রাখুন
                //Unused Id  //
            $totalUnusedAmount = Transaction::whereIn('id', $unused_ids)->sum('balance');
            // dd($totalUnusedAmount);
                foreach ($unused_ids as $unused_id) {
                if ($latestFinalBalance <= 0) {
                    break;
                }
                 $transaction_id = Transaction::findOrFail($unused_id);
                 $transaction_id->status = 'used';
                 $transaction_id->save();
                }

                //Unused Id end //
            if ($transaction) {
                $prevDueBal = min($latestFinalBalance, $transaction->balance);
                $transaction->credit = (float) ($transaction->credit ?? 0) + $prevDueBal;
                $transaction->balance = (float) $transaction->balance - $prevDueBal;
                // $transaction->payment_method = -1; // Payment done identifier
                $transaction->save();

                $customer->wallet_balance - $prevDueBal;
                $customer->total_payable + $prevDueBal;
                $customer->save();
                // dd($prevDueBal);
                $latestFinalBalance -= $prevDueBal;
            } else {
                $prevDueBal = 0;
            }
            foreach ($serviceIds as $serviceId) {
                if ($latestFinalBalance <= 0) {
                    break;
                }
                $serviceSale = ServiceSale::findOrFail($serviceId);
                $amountDiff = min($serviceSale->due, $latestFinalBalance);
                if ($serviceSale) {
                    $serviceSale->paid += $amountDiff;
                    $serviceSale->due -= $amountDiff;
                    $serviceSale->save();
                    $latestFinalBalance -= $amountDiff;
                }
                $customer->wallet_balance - $amountDiff;
                $customer->total_payable + $amountDiff;
                $customer->save();
            }
            foreach ($saleIds as $saleId) {
                if ($latestFinalBalance <= 0) {
                    break;
                }

                $sale = Sale::findOrFail($saleId);
                $amountDiff = min($sale->due, $latestFinalBalance);

                if ($sale) {
                    $sale->paid += $amountDiff;
                    $sale->due -= $amountDiff;
                    $sale->status = ($sale->due == 0) ? 'paid' : 'partial';
                    $sale->save();
                    $latestFinalBalance -= $amountDiff;
                }
            }

            if ($latestFinalBalance > 0) {
                $customer->wallet_balance - $latestFinalBalance;
                $customer->save();
            }
            // transaction
            $latestUpdateBal =  $request->payment_balance - $totalUnusedAmount ;
            // dd($latestUpdateBal);
            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = Carbon::now();
            //- $transaction->processed_by =  Auth::user()->id; -//
            $transaction->payment_type = 'receive';
            $transaction->payment_method = $request->account;
            // $transaction->credit = $request->payment_balance - $prevDueBal;
            $transaction->credit =  $latestUpdateBal ;
            $transaction->debit = 0;
            $transaction->balance = $transaction->debit - $transaction->credit ?? 0;
            $transaction->note = $request->note;
            $transaction->status = 'used';

            // Account Transaction Table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->account_id = $request->account;
             $accountTransaction->processed_by  =  Auth::user()->id;
            $oldBalance = AccountTransaction::where('account_id', $request->account)->latest('created_at')->first();

            if ($request->isCustomer === 'customer') {
                // transaction //
                $transaction->particulars = 'SaleDue';
                $transaction->customer_id = $request->data_id;

                // Customer Table
                $customer = Customer::findOrFail($request->data_id);
                $newBalance = $customer->wallet_balance -  $latestUpdateBal ;
                $newPayable = $customer->total_payable + $latestUpdateBal;
                $customer->update([
                    'wallet_balance' => $newBalance,
                    'total_payable' => $newPayable,
                ]);

                $accountTransaction->purpose = 'SaleDue';
                $accountTransaction->credit =  $latestUpdateBal;
                if ($oldBalance) {
                    $accountTransaction->balance = $oldBalance->balance +  $latestUpdateBal;
                } else {
                    $accountTransaction->balance =  $latestUpdateBal;
                }
                $accountTransaction->save();
                $transaction->save();
                // -------------------SMS--------------------//
                $settings = PosSetting::first();
                $linkInvoicePayment_sms = $settings->link_invoice_payment_sms;
                if ($linkInvoicePayment_sms == 1) {
                    $number = $customer->phone;
                    $api_key = '0yRu5BkB8tK927YQBA8u';
                    $senderid = '8809617615171';
                    $message = "Dear {$customer->name}, your Link invoice payment has been successfully completed. Paid Amount: {$request->payment_balance}. Thank you for your payment.";
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
                // -------------------SMS--------------------//
            } else {
                if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >=  $latestUpdateBal) {
                    // transaction update
                    $transaction->particulars = 'PurchaseDue';
                    $transaction->supplier_id = $request->data_id;

                    // supplier Crud
                    $supplier = Customer::findOrFail($request->data_id);
                    $newBalance = $supplier->wallet_balance -  $latestUpdateBal;
                    $newPayable = $supplier->total_payable +  $latestUpdateBal;
                    $supplier->update([
                        'wallet_balance' => $newBalance,
                        'total_payable' => $newPayable,
                    ]);

                    $accountTransaction->purpose = 'PurchaseDue';
                    $accountTransaction->debit =  $latestUpdateBal;
                    $accountTransaction->balance = $oldBalance->balance -  $latestUpdateBal;
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Your account Balance is low Please Select Another account or Add Balance on your Account',
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Payment',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    public function investorDetails($id)
    {
        $investor = Investor::findOrFail($id);
        $branch = Branch::findOrFail($investor->branch_id);
        $transactions = Transaction::where(function ($query) {
            $query->where('particulars', 'OthersPayment')
                ->orWhere('particulars', 'OthersReceive');
        })->where('others_id', $id)->get();
        $banks = Bank::get();

        return view('pos.investor.investorDetails', compact('investor', 'branch', 'transactions', 'banks'));
    }

    // public function investorDelete($id)
    // {
    //     $investor = Investor::findOrFail($id);

    //     $transaction = Transaction::where('particulars', 'OthersReceive')
    //         ->orWhere('particulars', 'OthersPayment')
    //         ->where('others_id', $investor->id)
    //         ->get();

    // if ($transaction) {
    //     $totalDebit = $transaction->debit - $transaction->credit;

    //     if (!$totalDebit === 0) {
    //         $accountTransaction = new AccountTransaction;
    //         $accountTransaction->branch_id =  Auth::user()->branch_id;
    //         $accountTransaction->reference_id = $investor->id;
    //         $accountTransaction->purpose =  'Delete Investor';
    //         $accountTransaction->account_id =  $request->payment_method;
    //         $accountTransaction->debit = $request->amount;
    //         $accountTransaction->credit = $request->amount;
    //         $oldBalance = AccountTransaction::where('account_id', $request->payment_method)->latest('created_at')->first();
    //         $accountTransaction->balance = $oldBalance->balance - $request->amount;
    //         $accountTransaction->created_at = Carbon::now();
    //         $accountTransaction->save();
    //     }
    // }

    //     $investor->delete();

    //     return redirect()->back();
    // }
    public function getPartyData(Request $request)
    {
        $accountId = $request->input('id');
        $account_type = $request->input('account_type');
        if ($account_type === 'party') {
            $info = Customer::findOrFail($accountId);
        }

        return response()->json([
            'info' => $info,
        ]);
    }
}
