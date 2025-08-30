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
        @include('pos.report.products.stock.common-stock')
    </div>
@endsection
