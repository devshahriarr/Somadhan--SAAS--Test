<?php

namespace App\Http\Controllers;

use App\Models\CompanyBalance;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dailyBalance()
    {
        $company_balance = CompanyBalance::all();

        return view('pos.balance.view', compact('company_balance'));
    }

    public function purchase()
    {
        $purchase = Purchase::where('branch_id', Auth::user()->branch_id)->latest()->get();

        // return view('pos.purchase.view');
        return view('pos.balance.view', compact('purchase'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
