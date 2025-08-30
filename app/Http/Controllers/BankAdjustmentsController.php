<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Bank;
use App\Models\BankAdjustments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BankAdjustmentsController extends Controller
{
    public function index()
    {
        $banks = Bank::all();

        return view('pos.bank.bank_adjustments.bank_adjustment', compact('banks'));
    }

    public function storeBankAdjustments(Request $request)
    {
        // dd($request->all());
        // try {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'bank_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'adjustment_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->messages(),
            ], 422);
        }

        // /////from///////

        $oldBalanceFrom = AccountTransaction::where('account_id', $request->bank_id)->latest('created_at')->first();
        // dd($oldBalanceFrom);

        $bankAdjustments = new BankAdjustments;
        $bankAdjustments->branch_id = Auth::user()->branch_id;
        $bankAdjustments->bank_id = $request->bank_id;
        $bankAdjustments->amount = $request->amount;
        $bankAdjustments->adjustments_date = $request->date;
        $bankAdjustments->adjustment_type = $request->adjustment_type;
        if ($request->image) {
            $imageName = rand().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/bank_adjustments/'), $imageName);
            $bankAdjustments->image = $imageName;
        }
        $bankAdjustments->note = $request->note;
        $bankAdjustments->save();

        if ($request->adjustment_type === 'increase') {
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'bank adjustments increase';
            $accountTransaction->account_id = $request->bank_id;
            $accountTransaction->credit = $request->amount;
            $accountTransaction->processed_by = Auth::user()->id;
            $accountTransaction->balance = $oldBalanceFrom->balance + $request->amount;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            return response()->json([
                'status' => 200,
                'message' => 'Bank Adjustments Increase Successfully Completed.',
            ]);
        } elseif ($request->adjustment_type === 'decrease') {

            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'bank adjustments decrease';
            $accountTransaction->account_id = $request->bank_id;
            $accountTransaction->debit = $request->amount;
            $accountTransaction->balance = $oldBalanceFrom->balance - $request->amount;
            $accountTransaction->processed_by = Auth::user()->id;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            return response()->json([
                'status' => 200,
                'message' => 'Bank Adjustments Decrease Successfully Completed.',
            ]);
        } else {
            return response()->json([
                'status' => 405,
                'errormessage' => 'Please Add Balance to Account or Deposit Account Balance',
            ]);
        }
        // } catch (\Exception $e) {
        //     // Log the error message
        //     Log::error('Error saving bank transfer: ' . $e->getMessage());

        //     return response()->json([
        //         'status' => 500,
        //         'error' => 'An internal server error occurred. Please try again later.'
        //     ], 500);
        // }
    }

    public function view()
    {
        if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin') {
            $banks = BankAdjustments::with('bank')->get();
        } else {
            $banks = BankAdjustments::where('branch_id', Auth::user()->branch_id)
                ->with('bank') // Use the correct relationship names
                ->latest()
                ->get();
        }

        return response()->json([
            'status' => 200,
            'data' => $banks,
        ]);
    }
}
