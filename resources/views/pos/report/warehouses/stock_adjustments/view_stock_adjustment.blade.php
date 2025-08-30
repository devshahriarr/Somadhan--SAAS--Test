@extends('master')
@section('title','| Supplier Due Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Stock Adjustments Report</li>
        </ol>
    </nav>
    <div class="row">
        <div class="d-flex justify-content-end mb-2">
            <a href="{{route('stock.adjustment')}}" class="btn btn-primary">Add Adjustments</a>
        </div>
        {{-- <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select select-customer" data-width="100%"
                                    name="">
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

                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Stock Adjustments Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>Adjustment No</th>
                                    <th>Branch Id </th>
                                    <th>Warehouse Name</th>
                                    <th>Rack Name </th>
                                    <th>Adjustment Type</th>
                                    <th>Reason</th>
                                    <th>Adjusted By</th>
                                    <th>Product Name</th>
                                    <th>Variation Size</th>
                                    <th>Prev QTY</th>
                                    <th>Adjust QTY</th>
                                    <th>Final QTY</th>
                                </tr>
                            </thead>
                            {{-- @dd($customer) --}}
                            <tbody id="showData">
                                @include('pos.report.warehouses.stock_adjustments.stock_adjustment_table')
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
        // $(document).ready(function() {

        //     // filter
        //     $('#filter').click(function(e) {
        //         e.preventDefault();
        //         let customerId = document.querySelector('.select-customer').value;

        //         $.ajax({
        //             url: "{{ route('supplier.due.filter') }}",
        //             method: 'GET',
        //             data: {
        //                 customerId
        //             },
        //             success: function(res) {
        //                 // console.log(res);
        //                 jQuery('#showData').html(res);
        //                 // const customer = res.customer;
        //                 // const transactions = res.transactions;
        //                 // supplierInfo(customer, transactions);
        //             }
        //         });
        //     });
        //     // print
        //     document.querySelector('.print-btn').addEventListener('click', function(e) {
        //         e.preventDefault();
        //         $('#dataTableExample').removeAttr('id');
        //         $('.table-responsive').removeAttr('class');
        //         // Trigger the print function
        //         window.print();
        //     });
        // });



    </script>
@endsection
