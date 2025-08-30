<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\ViaSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ViaSaleController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $viaSale = ViaSale::latest()->get();
        } else {
            $viaSale = ViaSale::where('branch_id', Auth::user()->branch_id)->latest()->get();
        }

        return view('pos.via_sale.via_sale', compact('viaSale'));
    }

    public function viaSaleGet($id)
    {
        $viaSale = ViaSale::findOrFail($id);

        return response()->json([
            'status' => '200',
            'data' => $viaSale,
        ]);
    }

    public function viaSalePayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_account' => 'required',
            'amount' => 'required',
        ]);
        $validator->after(function ($validator) use ($id, $request) {
            $viaSale = ViaSale::findOrFail($id);
            if ($request->amount > $viaSale->due) {
                $validator->errors()->add('amount', 'The amount cannot be greater than the due amount.');
            }
        });
        if ($validator->passes()) {
            $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
            // dd($oldBalance->balance >= $request->amount);
            if ($oldBalance && $oldBalance->balance > 0 && $oldBalance->balance >= $request->amount) {
                $viaSale = ViaSale::findOrFail($id);
                $viaSale->paid = $viaSale->paid + $request->amount;
                $viaSale->due = $viaSale->due - $request->amount;
                if ($viaSale->paid + $request->amount >= $viaSale->sub_total) {
                    $viaSale->status = 1;
                }
                $viaSale->save();

                // account Transaction crud
                $accountTransaction = new AccountTransaction;
                $accountTransaction->branch_id = Auth::user()->branch_id;
                $accountTransaction->purpose = 'Via Payment';
                $accountTransaction->reference_id = $id;
                $accountTransaction->account_id = $request->transaction_account;
                $accountTransaction->debit = $request->amount;
                $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
                $accountTransaction->balance = $oldBalance->balance - $request->amount;
                $accountTransaction->created_at = Carbon::now();
                $accountTransaction->save();

                return response()->json([
                    'status' => '200',
                    'message' => 'Payment Transaction Successful',
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Not Enough Balance in Account. Please choose Another Account or Deposit Account Balance',
                ]);
            }
        } else {
            return response()->json([
                'status' => 500,
                'error' => $validator->errors(),
            ]);
        }
    }

    public function viaSaleInvoice($id)
    {
        $viaSale = ViaSale::findOrFail($id);

        return view('pos.via_sale.invoice', compact('viaSale'));
    }

    public function ViaSaleProductDelete($id)
    {
        $viaSale = ViaSale::findOrFail($id)->delete();

        return response()->json(['message' => 'Via Sale deleted successfully.']);
    }
}
