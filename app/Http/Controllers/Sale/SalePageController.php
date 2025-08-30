<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Affiliator;
use App\Models\AffliateCommission;
use App\Models\Customer;
use App\Models\PosSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SalePageController extends Controller
{
    public function index()
    {
        try {
            $setting = PosSetting::latest()->first();
            $affiliators = Affiliator::where('branch_id', Auth::user()->branch_id)
                ->whereNull('user_id')
                ->get();
            $customers = Customer::where('branch_id', Auth::user()->branch_id)->where('party_type', '<>', 'supplier')->get();

            return Inertia::render('Sale/Sale', [
                'setting' => $setting,
                'affiliators' => $affiliators,
                'customers' => $customers,
            ]);
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            Log::error('Error in index method: ' . $e->getMessage());

            // Return a custom error view with a user-friendly message and a 500 status code
            return response()->view('errors.500', ['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }
}
