@extends('master')
@section('title', '| Purchase Report')
@section('admin')
    <style>
        .nav.nav-tabs .nav-item .nav-link.active {
            border-color: #dee2e6 #dee2e6 #fff;
            color: #6571ff !important;
            background: #fff;
        }
        .nav.nav-tabs .nav-item .nav-link.active:hover {
            border-color: #dee2e6 #dee2e6 #fff;
            color: #6571ff !important;
            background: #fff;
        }
    </style>
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Top Sale Report</li>
        </ol>
    </nav>

    <div class="row filter-class">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="true">Top Product Sale </a>
            </li>
            <li class="nav-item">
                {{-- <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#" role="tab" aria-controls="contact" aria-selected="false"></a> --}}
                <a class="nav-link " id="variatioTopSale-tab" data-bs-toggle="tab" href="#variatioTopSale" role="tab"
                    aria-controls="profile" aria-selected="false">Top Variation Sale</a>
            </li>

        </ul>
        <div class="tab-content border border-top-0 p-3 active" id="myTabContent">

            <div class="tab-pane show active " id="home" role="tabpanel" aria-labelledby="home-tab">
                {{--  <-------------------Product  Sale-------------------------> --}}
                <div class="col-md-12   grid-margin stretch-card filter_box">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text"
                                            class="form-control from-date flatpickr-input start-date-product-sale"
                                            placeholder="Start date" data-input="" readonly="readonly">
                                        <span class="input-group-text input-group-addon" data-toggle=""><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2">
                                                </rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text"
                                            class="form-control from-date flatpickr-input end-date-product-sale"
                                            placeholder="End date" data-input="" readonly="readonly">
                                        <span class="input-group-text input-group-addon" data-toggle=""><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2">
                                                </rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg></span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="justify-content-left">
                                        <button class="btn btn-sm bg-info text-dark mr-2"
                                            id="saleProductfilter">Filter</button>
                                        <button class="btn btn-sm bg-primary text-dark"
                                            onclick="window.location.reload();" id="reset">Reset</button>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    {{-- <div class="flex text-md-end ">
                                <button type="button"
                                    class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 prints-btn">
                                    <i class="btn-icon-prepend"  data-feather="printer"></i>
                                    Print
                                </button>

                            </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-info">Top Product Sale Report</h6>
                                    <div id="" class="table-responsive">
                                        <table id='productDataTable' class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Total Sale Qty</th>
                                                    <th>Total Purchase Cost</th>
                                                    <th>Sale Price </th>
                                                    <th>Total Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="top-product-filter-table" class="showData">
                                                @include('pos.report.product-variation-top-sale.top-product-sale-filter-table')
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- // <-------------------Variation  Sale-------------------------> --}}
            </div>
            <div class="tab-pane show" id="variatioTopSale" role="tabpanel" aria-labelledby="variatioTopSale-tab">
                <div class="col-md-12   grid-margin stretch-card filter_box">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text"
                                            class="form-control from-date flatpickr-input start-date-sale-variation"
                                            placeholder="Start date" data-input="" readonly="readonly">
                                        <span class="input-group-text input-group-addon" data-toggle=""><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2">
                                                </rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <input type="text"
                                            class="form-control from-date flatpickr-input end-date-sale-variation"
                                            placeholder="End date" data-input="" readonly="readonly">
                                        <span class="input-group-text input-group-addon" data-toggle=""><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2">
                                                </rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg></span>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="justify-content-left">
                                        <button class="btn btn-sm bg-info text-dark mr-2"
                                            id="saleVariationfilter">Filter</button>
                                        <button class="btn btn-sm bg-primary text-dark"
                                            onclick="window.location.reload();" id="reset">Reset</button>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 ">
                                <div class="flex text-md-end ">
                                    <button type="button"
                                        class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0 print-btn">
                                        <i class="btn-icon-prepend"  data-feather="printer"></i>
                                        Print
                                    </button>

                                </div>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-info">Top Variant Sale Report</h6>

                                    <div id="" class="table-responsive">
                                        <table id="variationDataTable" class="table">
                                            <thead>
                                                <tr>

                                                    <th>Product Name</th>
                                                    <th>Product Qty</th>
                                                    <th>Variant Type</th>
                                                    <th>Size</th>
                                                    <th>Total Cost price </th>
                                                    <th>Total Sale price </th>
                                                    <th>Total Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="top-variation-filter-table" class="showData">
                                                @include('pos.report.product-variation-top-sale.top-variation-sale-filter-table')
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <script>
            $(document).ready(function() {
                // dyanamicTable('#productDataTable');
                // <-------------------Variation Sale------------------------->
                document.querySelector('#saleVariationfilter').addEventListener('click', function(e) {
                    e.preventDefault();
                    let startDateSale = document.querySelector('.start-date-sale-variation').value;
                    let endDateSale = document.querySelector('.end-date-sale-variation').value;
                    console.log(startDateSale, endDateSale)
                    $.ajax({
                        url: "{{ route('top.variation.sale.filter.view') }}",
                        method: 'GET',
                        data: {
                            startDateSale,
                            endDateSale,
                        },
                        success: function(res) {
                            jQuery('#top-variation-filter-table').html(res);
                        }
                    });
                });
                document.querySelector('#saleProductfilter').addEventListener('click', function(e) {
                    e.preventDefault();
                    let startDateSaleProduct = document.querySelector('.start-date-product-sale').value;
                    let endDateSalProducte = document.querySelector('.end-date-product-sale').value;
                    // console.log(startDateSaleProduct,endDateSalProducte)
                    $.ajax({
                        url: "{{ route('top.product.sale.filter') }}",
                        method: 'GET',
                        data: {
                            startDateSaleProduct,
                            endDateSalProducte,
                        },
                        success: function(res) {

                            jQuery('#top-product-filter-table').html(res);
                        }
                    });
                });

            });
            $('.prints-btn').click(function() {
                // Remove the id attribute from the table
                $('#example').removeAttr('id');
                $('.table-responsive').removeAttr('class');
                // Trigger the print function
                window.print();

            });

            // <-------------------Product Sale End------------------------->
        </script>
        <style>
            @media print {

                nav,
                .nav,
                .footer {
                    display: none !important;
                }

                .page-content {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }

                .btn_group,
                .filter_table,
                .dataTables_length,
                .pagination,
                .dataTables_info {
                    display: none !important;
                }

                #dataTableExample_filter {
                    display: none !important;
                }

                .border {
                    border: none !important;
                }

                table,
                th,
                td {
                    border: 1px solid black;
                    background: #fff
                }

                .actions,
                .filter-class {
                    display: none !important;
                }

                .card {
                    background: #fff !important;
                    box-shadow: none !important;
                    border: none !important;
                }

                .note_short {}
            }
        </style>
    @endsection
