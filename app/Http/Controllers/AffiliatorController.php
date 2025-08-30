<?php

namespace App\Http\Controllers;

use App\Models\AccountTransaction;
use App\Models\Affiliator;
use App\Models\AffliateCommission;
use App\Models\PosSetting;
use App\Models\Transaction;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliatorController extends Controller
{
    public function index()
    {

        return view('pos.affiliator.index');
    }

    public function store(Request $request)
    {
        //  dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'commission_type' => 'required',
                'commission_rate' => 'required',
                'commission_state' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $affiliator = new Affiliator;
            $affiliator->name = $request->name;
            $affiliator->phone = $request->phone;
            $affiliator->branch_id = Auth::user()->branch_id;
            $affiliator->commission_type = $request->commission_type;
            $affiliator->commission_rate = $request->commission_rate;
            $affiliator->commission_state = $request->commission_state;
            $affiliator->save();

            return response()->json([
                'status' => 200,
                'message' => 'Affiliator Added Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(), // Show the exact error message
            ]);
        }

    }

    public function view()
    {
        try {
            $affiliatorsSettings = PosSetting::where('affliate_program', 1)->first();

            if ($affiliatorsSettings) {
                if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                    $affiliators = Affiliator::orderBy('id', 'asc')->get();
                } else {
                    $affiliators = Affiliator::orderBy('id', 'asc')->where('branch_id', Auth::user()->branch_id)->get();
                }

                return response()->json(['status' => 200, 'affiliator' => $affiliators]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Affiliator Program Not Active',
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $affiliator = Affiliator::find($id);
            if ($affiliator) {
                return response()->json([
                    'status' => 200,
                    'affiliator' => $affiliator,
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Affiliator Found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ]);
        }
    }

    public function update(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'commission_type' => 'required',
                'commission_rate' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $affiliator = Affiliator::find($request->id);
            if ($affiliator) {
                $affiliator->name = $request->name;
                $affiliator->phone = $request->phone;
                $affiliator->branch_id = Auth::user()->branch_id;
                $affiliator->commission_type = $request->commission_type;
                $affiliator->commission_rate = $request->commission_rate;
                $affiliator->commission_state = $request->commission_state;
                $affiliator->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Affiliator Updated Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ]);
        }
    }

    public function delete(Request $request)
    {

        try {

            $affiliator = Affiliator::find($request->id);
            if ($affiliator) {
                $affiliator->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Affiliator Deleted Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Affiliator Found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
            ]);
        }
    }

    public function commissionManage()
    {
        $affiliatorsSettings = PosSetting::where('affliate_program', 1)->first();

        if (! $affiliatorsSettings) {
            return redirect()->back()->with('error', 'Affiliate program is not active.');
        }

        // Get all affiliator IDs where user_id is NULL
        $affiliatorIds = Affiliator::whereNull('user_id')->pluck('id');

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $affliatorCommission = AffliateCommission::whereIn('affiliator_id', $affiliatorIds)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $affliatorCommission = AffliateCommission::whereIn('affiliator_id', $affiliatorIds)
                ->where('branch_id', Auth::user()->branch_id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('pos.affiliator.ManageCommission', compact('affliatorCommission'));
    }

    public function sellerCommission()
    {
        $saleCommissionSettings = PosSetting::where('sale_commission', 1)->first();

        if (! $saleCommissionSettings) {
            return redirect()->back()->with('error', 'Sale Commission is not active.');
        }

        // Get all affiliator IDs where user_id is NULL
        $affiliatorIds = Affiliator::whereNotNull('user_id')->pluck('id');

        if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $affliatorCommission = AffliateCommission::whereIn('affiliator_id', $affiliatorIds)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $affliatorCommission = AffliateCommission::whereIn('affiliator_id', $affiliatorIds)
                ->where('branch_id', Auth::user()->branch_id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('pos.affiliator.ManageCommission', compact('affliatorCommission'));
    }

    public function PaidCommission(Request $request)
    {

        try {
            $affliatorCommission = AffliateCommission::find($request->id);
            if ($affliatorCommission->commission_amount <= $request->amount) {
                $affliatorCommission->status = 'paid';
                $affliatorCommission->paid_amount += $request->amount;
                $affliatorCommission->save();
            } else {

                $affliatorCommission->status = 'partial paid';
                $affliatorCommission->commission_amount -= $request->amount;
                $affliatorCommission->paid_amount += $request->amount;
                $affliatorCommission->save();
            }

            // accountTransaction table
            $accountTransaction = new AccountTransaction;
            $accountTransaction->branch_id = Auth::user()->branch_id;
            $accountTransaction->purpose = 'Affliator Commission';
            $accountTransaction->reference_id = $request->id;
            $accountTransaction->account_id = $request->transaction_account;
            $accountTransaction->credit = $request->amount;
            $oldBalance = AccountTransaction::where('account_id', $request->transaction_account)->latest('created_at')->first();
            $accountTransaction->balance = $oldBalance->balance - $request->amount;
            $accountTransaction->created_at = Carbon::now();
            $accountTransaction->save();

            $transaction = new Transaction;
            $transaction->branch_id = Auth::user()->branch_id;
            $transaction->date = $request->payment_date;
            $transaction->payment_type = 'pay';
            $transaction->particulars = 'Affliator Commission#'.$request->id;
            $transaction->others_id = $affliatorCommission->id;
            $transaction->debit = $transaction->debit + $request->amount;
            $transaction->balance = $transaction->balance + $request->amount;
            $transaction->payment_method = $request->transaction_account;
            $transaction->save();

            return response()->json([
                'status' => 200,
                'message' => 'Affliator Commission Paid Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage(),
            ]);
        }

    }
}
