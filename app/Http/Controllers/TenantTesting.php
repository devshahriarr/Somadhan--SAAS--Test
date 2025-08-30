<?php

namespace App\Http\Controllers;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantTesting extends Controller
{
    //
    Route::get('/test-tenant', function () {
        return Tenant::all();
    });
}

