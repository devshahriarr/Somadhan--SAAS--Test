@extends('master')
@section('title','| Customer Due Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer Due Report</li>
        </ol>
    </nav>

    <div class="row">
        <table class="table table-bordered table-striped text-center  mb-4">
            <thead class="text-dark">
                <tr>
                    <th>Total Customer</th>
                    <th>Total Due</th>

                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td>{{$customer->count() ?? 0}}</td>
                    <td>৳ {{($customer->sum('wallet_balance'))}}</td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">

                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select select-customer" data-width="100%"
                                    name="">
                                    @if ($customer->count() > 0)
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customer as $customerData)
                                            <option value="{{ $customerData->id }}">{{ $customerData->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Customer</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</button>
                                <button class="btn btn-sm bg-primary text-dark" onclick="window.location.reload()">Reset</button>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="flex text-md-end ">
                                {{-- <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                    <i class="btn-icon-prepend" data-feather="printer"></i>
                                    Print
                                </button> --}}
                                {{-- <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                                    Download Report
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Customer Due Table</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Invoice Due</th>
                                    <th>Total Due</th>
                                </tr>
                            </thead>
                            {{-- @dd($customer) --}}
                            <tbody id="showData">
                                @include('pos.report.customer.table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .page-content {
                margin-top: 0 !important;
                padding-top: 0 !important;
                width: 100% !important;
            }

            button,
            a,
            .filter_box,
            nav,
            .footer,
            .id,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info {
                display: none !important;
            }

            table {
                padding-right: 50px !important;
            }
        }
    </style>


    <script>
        $(document).ready(function() {

            // filter
            $('#filter').click(function(e) {
                e.preventDefault();
                let customerId = document.querySelector('.select-customer').value;

                $.ajax({
                    url: "{{ route('customer.due.filter') }}",
                    method: 'GET',
                    data: {
                        customerId
                    },
                    success: function(res) {
                        // console.log(res);
                        jQuery('#showData').html(res);
                        // const customer = res.customer;
                        // const transactions = res.transactions;
                        // supplierInfo(customer, transactions);
                    }
                });
            });
            // print
            // document.querySelector('.print-btn').addEventListener('click', function(e) {
            //     e.preventDefault();
            //     $('#dataTableExample').removeAttr('id');
            //     $('.table-responsive').removeAttr('class');
            //     // Trigger the print function
            //     window.print();
            // });


        })
    </script>
@endsection
