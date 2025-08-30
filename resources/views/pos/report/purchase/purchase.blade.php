@extends('master')
@section('title','| Purchase Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
        </ol>
    </nav>

    <div class="row filter-class" >
        <table class="table table-bordered table-striped summary-print text-center  mb-4">
            <thead class="text-dark">
                <tr>
                    <th>Total Invoice </th>
                    <th>Total Purchase Amount </th>
                    <th>Total Purchase QTY </th>
                    <th>Total Purchase Item </th>

                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold">
                    <td>{{$purchase->count() ?? 0}}</td>
                    <td>{{$purchase->sum(('total_amount')) ?? 0}}</td>
                    <td>{{$purchase->sum(('total_quantity')) ?? 0}}</td>
                    <td>{{$purchaseItem->count() ?? 0}}</td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input start-date-purches"
                                    placeholder="Start date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input end-date-purches"
                                    placeholder="End date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle=""><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg></span>
                            </div>
                        </div>
                        @php
                         if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin'){
                                $products = App\Models\Product::get();
                            }else{
                                $products = App\Models\Product::get();
                            }
                        @endphp
                        <div class="col-md-3">
                            <div class=" input-group flatpickr" id="flatpickr-date">
                                <select class="filter_product_name js-example-basic-single form-select product_select" data-width="100%">
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Product</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="purchesfilter">Filter</button>
                                <button class="btn btn-sm bg-primary text-dark"  onclick="window.location.reload();" id="reset">Reset</button>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="flex text-md-end ">
                                <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                    <i class="btn-icon-prepend" id="purchesfilter" data-feather="printer"></i>
                                    Print
                                </button>
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
    </div>

    <div id="purchase-filter-table">
        @include('pos.report.purchase.purchase-filter-table')
    </div>
        <script>
            $(document).ready(function (){

document.querySelector('#purchesfilter').addEventListener('click', function(e) {
    e.preventDefault();
         let startDatePurches = document.querySelector('.start-date-purches').value;
        let endDatePurches = document.querySelector('.end-date-purches').value;
        //  alert(endDatePurches);
        let filterProduct = document.querySelector('.filter_product_name').value;
        // alert(filterProduct);
        $.ajax({
            url: "{{ route('purches.product.filter.view') }}",
            method: 'GET',
            data: {
                startDatePurches,
                endDatePurches,
                filterProduct,
            },
            success: function(res) {
                jQuery('#purchase-filter-table').html(res);
            }
        });
    });
    });
    $('.print-btn').click(function() {
            // Remove the id attribute from the table
            $('#dataTableExample').removeAttr('id');
            $('.table-responsive').removeAttr('class');
            // Trigger the print function
            window.print();

        });
        </script>
<style>
    @media print {

        nav,.nav,
        .footer {
            display: none !important;
        }

        .page-content {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .btn_group ,.filter_table,.dataTables_length,.pagination,.dataTables_info{
            display: none !important;
        }
        #dataTableExample_filter{
            display: none !important;
        }
        .border{
            border: none !important;
        }
        table,th,td{
            border: 1px solid black;
            background: #fff
        }
        .actions ,.filter-class{
            display: none !important;
        }
        .card{
            background: #fff!important;
            box-shadow: none!important;
            border: none !important;
        }
        .note_short{

        }
    }

</style>
@endsection
