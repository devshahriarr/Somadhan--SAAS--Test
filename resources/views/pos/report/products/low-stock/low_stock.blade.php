@extends('master')
@section('title', '| Low Stock Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Low Stock Report</li>
        </ol>
    </nav>
    <div class="row">
        @php
            $bracnhes = App\Models\Branch::all();
        @endphp

        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Low Stock Table</h6>
                        @foreach ($bracnhes as $branch)
                            <a href="{{ route('branch.low.stock', ['branch' => $branch->id]) }}" class="btn"
                                style="background-color: #0d6efd">{{ $branch->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            @include('pos.report.products.low-stock.low-common-stock')

        @endif
    </div>
@endsection
