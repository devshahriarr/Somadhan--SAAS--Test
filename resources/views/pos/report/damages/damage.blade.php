@extends('master')
@section('title', '| Damage Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Damage Report</li>
        </ol>
    </nav>

    <div class="row filter-class">
        <!-- Total Damage Cost Card -->
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card p-4 shadow-sm d-flex flex-row align-items-center">
                <div class="icon-container bg-primary text-white d-flex align-items-center justify-content-center">
                    <i class="fas fa-money-bill-wave fs-3"></i>
                </div>
                <div class="ms-3">
                    <label class="fw-bold text-primary">Total Damage Cost</label>
                    <h4 class="text-dark mb-0">৳{{ number_format($damage_cost, 2) }}</h4>
                </div>
            </div>
        </div>

        <!-- Monthly Damage Cost Card -->
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="card p-4 shadow-sm d-flex flex-row align-items-center">
                <div class="icon-container bg-success text-white d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-alt fs-3"></i>
                </div>
                <div class="ms-3">
                    <label class="fw-bold text-success">Monthly Damage Cost</label>
                    <h4 class="text-dark mb-0">৳{{ number_format($monthlyDamage, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>


    <div class="row filter-class">
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('damage.report.print') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" class="form-control from-date flatpickr-input start-date-purches"
                                        placeholder="Start date" data-input="" readonly="readonly" name="startdatepurches">
                                    <span class="input-group-text input-group-addon" data-toggle=""><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
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
                                        placeholder="End date" data-input="" readonly="readonly" name="enddatepurches">
                                    <span class="input-group-text input-group-addon" data-toggle=""><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                            </rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg></span>
                                </div>
                            </div>
                            @php
                            $products = App\Models\Product::get();
                            @endphp
                            <div class="col-md-3">
                                <div class=" input-group flatpickr" id="flatpickr-date">
                                    <select name="filterProduct"
                                        class="filter_product_name js-example-basic-single form-select product_select"
                                        data-width="100%">
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
                            @php
                            if(Auth::user()->role == 'superadmin' || Auth::user()->role = 'admin'){
                            $all_branch = App\Models\Branch::all();
                            }else{
                                $all_branch = App\Models\Branch::where('id', Auth::user()->branch_id)->get();
                            }
                            @endphp
                            <div class="col-md-3">
                                <div class=" input-group flatpickr" id="flatpickr-date">
                                    <select class="filter_branch js-example-basic-single form-select product_select"
                                        data-width="100%" name="branchId">
                                        @if ($all_branch->count() > 0)
                                            <option selected disabled>Select Branch</option>
                                            @foreach ($all_branch as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        @else
                                            <option selected disabled>Please Add branch</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="justify-content-left">
                                    <button type="button" class="btn btn-sm bg-info text-dark mr-2"
                                        id="purchesfilter">Filter</button>
                                    <button type="button" class="btn btn-sm bg-primary text-dark"
                                        onclick="window.location.reload();" id="reset">Reset</button>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="flex text-md-end ">
                                    <button type="submit"
                                        class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                                        <i class="btn-icon-prepend" data-feather="printer"></i>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="purchase-filter-table">
        @include('pos.report.damages.damage-filter-table')
    </div>

    <script>
        $(document).ready(function() {
            document.querySelector('#purchesfilter').addEventListener('click', function(e) {
                e.preventDefault();
                let startDatePurches = document.querySelector('.start-date-purches').value;
                let endDatePurches = document.querySelector('.end-date-purches').value;
                //  alert(endDatePurches);
                let filterProduct = document.querySelector('.filter_product_name').value;
                let branchId = document.querySelector('.filter_branch').value;
                // alert(filterProduct);
                $.ajax({
                    url: "{{ route('damage.product.filter.view') }}",
                    method: 'GET',
                    data: {
                        startDatePurches,
                        endDatePurches,
                        filterProduct,
                        branchId
                    },
                    success: function(res) {
                        jQuery('#purchase-filter-table').html(res);
                    }
                });
            });
        });
    </script>
@endsection
