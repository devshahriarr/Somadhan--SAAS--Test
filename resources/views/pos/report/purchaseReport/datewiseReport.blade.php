@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Purchase Datewise Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Total Invoice</th>
                                    <th>Total Amount</th>
                                    <th>Total Paid</th>
                                    <th>Total Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $invoiceActive = App\Models\PosSetting::where('invoice_payment',1)->first();
                                @endphp
                                {{-- @if($invoiceActive!= null) --}}
                                @foreach ($datewiseReport as $datewiseReport)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $datewiseReport->date }}</td>
                                        <td>{{ $datewiseReport->total_invoices}}</td>
                                        <td>
                                            @if($invoiceActive!= null)
                                            {{ number_format($datewiseReport->total_amount, 2) }}
                                            @else
                                            <h6>N/A</h6>
                                           @endif
                                        </td>
                                        <td>
                                            @if($invoiceActive!= null)
                                            {{ number_format($datewiseReport->total_paid, 2) }}
                                            @else
                                            <h6>N/A</h6>
                                           @endif
                                        </td>
                                        <td>
                                            @if($invoiceActive!= null)
                                            {{ number_format($datewiseReport->total_due, 2) }}
                                            @else
                                            <h6>N/A</h6>
                                           @endif
                                        </td>
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
