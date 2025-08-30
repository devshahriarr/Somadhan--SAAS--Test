<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
// use Validator;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    public function PaymentMethodAdd()
    {
        return view('pos.payment_method.payment_method_add');
    }

    public function PaymentMethodStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
        ]);

        if ($validator->passes()) {
            $payment = new PaymentMethod;
            $payment->name = $request->name;
            $payment->save();

            return response()->json([
                'status' => 200,
                'message' => 'Payment Method Save Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    //
    public function PaymentMethodView()
    {
        $paymentMethod = PaymentMethod::get();

        return response()->json([
            'status' => 200,
            'data' => $paymentMethod,
        ]);
    }

    //
    public function PaymentMethodEdit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        if ($paymentMethod) {
            return response()->json([
                'status' => 200,
                'paymentMethod' => $paymentMethod,
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Data Not Found',
            ]);
        }
    }

    //
    public function PaymentMethodUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:39',
        ]);
        if ($validator->passes()) {
            $paymentMethod = PaymentMethod::findOrFail($id);
            $paymentMethod->name = $request->name;
            $paymentMethod->save();

            return response()->json([
                'status' => 200,
                'message' => 'Payment Method Update Successfully',
            ]);
        } else {
            return response()->json([
                'status' => '500',
                'error' => $validator->messages(),
            ]);
        }
    }

    //
    public function PaymentMethodDelete($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Payment Method Deleted Successfully',
        ]);
    }
}
