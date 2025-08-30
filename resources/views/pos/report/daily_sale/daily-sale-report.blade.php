@extends('master')
@section('title', '|Daily  Sale Report')
@section('admin')
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daily Sale Report</li>
    </ol>
</nav>
<div class="row">


<div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow-1">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Total Sale Amount</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="">
                                ৳ {{$dailySaleSum}}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline  mb-2">
                        <h6 class="card-title mb-0">Paid Sale Amount</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="">
                                ৳ {{$dailyPaidSaleSum}}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <h6 class="card-title mb-0">Due</h6>
                        <div class="dropdown mb-2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-2">
                                ৳ {{$dailyDueSaleSum}}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
    <div class="col-md-12">
        @include('pos.report.daily_sale.daily_sale_report_table')
    </div>
</div>

@endsection
