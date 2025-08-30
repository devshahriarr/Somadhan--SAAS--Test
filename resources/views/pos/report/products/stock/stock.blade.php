@extends('master')
@section('title', '| Stock Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Stock Report</li>
        </ol>
    </nav>
    <style>
        .nav.nav-tabs .nav-item .nav-link.active {
            border-color: #dee2e6 #dee2e6 #fff;
            color: #6571ff !important;
            background: #fff;
        }
    </style>
    <div class="row">
        @php
            $bracnhes = App\Models\Branch::all();
        @endphp

        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Stock Table</h6>
                        @foreach ($bracnhes as $branch)
                            <a href="{{ route('branch.stock', ['branch' => $branch->id]) }}" class="btn"
                                style="background-color: #0d6efd">{{ $branch->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @include('pos.report.products.stock.common-stock')
        @endif

    </div>
@endsection
