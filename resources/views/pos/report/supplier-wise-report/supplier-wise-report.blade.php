@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase  Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Supplier Purchase Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr >
                                    <th class="text-center">#</th>
                                    <th class="text-center">Supplier Name</th>
                                    <th class="text-center">Total Invoice</th>
                                    <th class="text-center">Total Purchase Amount</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($supplierWiseReport as $supplierWiseReport)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration}}</td>
                                        <td class="text-center">{{ $supplierWiseReport->supplier->name }}</td>
                                        <td class="text-center">{{ $supplierWiseReport->total_invoices}}</td>
                                        <td class="text-center">{{ $supplierWiseReport->total_amount}}</td>
                                    </tr>
                                @endforeach


                                {{-- @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
