@extends('master')
@section('title', '| Sale Filter')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sale Filter Manage</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input start-date"
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
                        <div class="col-md-2">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control from-date flatpickr-input end-date"
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
                            $products = App\Models\Product::all();
                            $customers = App\Models\Customer::whereIn('party_type', ['customer', 'both'])->get();
                            $excludedNames = ['Super Admin', 'TecAdmin'];
                            $salebys = App\Models\User::whereNotIn('name', $excludedNames)->get();


                        @endphp
                        @if (Auth::user()->role == 'admin' || 'superadmin')
                            <div class="col-md-2">
                            @else
                                <div class="col-md-3">
                        @endif
                        <div style="width: 100%;">
                            <select class="js-example-basic-single form-select product_select"  data-placeholder="Select Product">
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
                    @if (Auth::user()->role == 'admin' || 'superadmin')
                        <div class="col-md-2">
                        @else
                            <div class="col-md-2">
                    @endif
                    <div  style="width: 100%;">
                        <select class="js-example-basic-single form-select select-supplier customer_id"
                            name="" data-placeholder="Select Customer">
                            @if ($customers->count() > 0)
                                <option selected disabled>Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            @else
                                <option selected disabled>Please Add Customer</option>
                            @endif
                        </select>
                    </div>
                </div>
                @if (Auth::user()->role == 'admin' || 'superadmin')
                    <div class="col-md-2" >
                        <div  style="width: 100%!important;">
                            <select class="js-example-basic-single  sale_by_id"
                                name="" data-placeholder="Select Sales Man">
                                    <option selected disabled>Select Sales Man</option>
                                    @foreach ($salebys as $saleby)
                                        <option value="{{ $saleby->id }}">{{ $saleby->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-md-2">
                    <div   style="width: 100%!important;">
                        <select class="js-example-basic-single  account_id" data-width="100%" style="width: 100%;"
                            name="" data-placeholder="Select Account Name">

                                <option selected disabled>Select Account Name</option>
                                @foreach ( $banks as  $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="justify-content-left">
                        <button class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</button>
                        <button class="btn btn-sm bg-primary text-dark" id="reset">Reset</button>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="flex text-md-end ">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#invoiceModal"
                        class="btn btn-primary btn-icon-text mb-md-0 gap-1">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        All Invoice
                    </button>
                    </div>
                </div>
            </div>
            {{-- //Sale Data// --}}

              {{-- //////////////// Modal all Invoice ////////// --}}
              <div class="modal fade" id="invoiceModal" tabindex="-1"
              aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-xl">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalScrollableTitle">Preview</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"
                              aria-label="Close"></button>
                      </div>
                      <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                          <div class="table-responsive">
                              <table class="table ">
                                  <thead>
                                      <tr>
                                          <th>Date</th>
                                          <th>Invoice</th>
                                          <th>Details</th>
                                          <th>Total</th>
                                      </tr>
                                  </thead>
                                  <tbody id="showDataModal">
                                      @include('pos.sale.all-invoice-print')
                                  </tbody>
                              </table>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" onclick="printModalContent()"
                              class="btn btn-success">Print/Save PDF</button>
                          {{-- <button type="button" class="btn btn-info">Save PDF</button> --}}
                          <button type="button" class="btn btn-secondary"
                              data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
            <div>
                <div class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th class="id">#SL</th>
                                <th>Invoice <br>Number</th>
                                <th>Customer</th>
                                {{-- <th>Items</th> --}}
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Discount</th>
                                <th>Receivable</th>
                                <th>Paid</th>
                                <th>Product <br> Returned</th>
                                <th>Due</th>
                                @if (Auth::user()->role !== 'salesman')
                                    <th>Purchase <br> Cost</th>
                                    <th>Profit</th>
                                @endif
                                <th>Receive Account</th>
                                <th>Sale By</th>
                                <th>Sale Status</th>

                                <th>Status</th>
                                {{-- <th>Sale Status</th> --}}

                            </tr>
                        </thead>
                        <tbody id="showData">
                            @include('pos.sale.sale_filter.sale_main_filter_table')
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



// Filter button handler
document.querySelector('#filter').addEventListener('click', function(e) {
    e.preventDefault();
    let startDate = document.querySelector('.start-date').value;
    let endDate = document.querySelector('.end-date').value;
    let product_id = document.querySelector('.product_select').value;
    let customer_id = document.querySelector('.customer_id').value;
    let sale_by_id = document.querySelector('.sale_by_id').value;
    let account_id = document.querySelector('.account_id').value;

    $.ajax({
        url: "{{ route('sale.filter') }}",
        method: 'GET',
        data: {
            startDate,
            endDate,
            product_id,
            customer_id,
            sale_by_id,
            account_id,
        },
        success: function(res) {
            // Destroy the existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            // Update the table content
            jQuery('#showData').html(res.salesTable);
            jQuery('#showDataModal').html(res.saleInvoiceTable);

            // Reinitialize the DataTable with all your custom settings
            initializeDataTable();
        }
    });
});

// Reset button handler
document.querySelector('#reset').addEventListener('click', function(e) {
    e.preventDefault();
    $('.start-date').val("");
    $('.end-date').val("");
    $('.product_select').val('Select Product').trigger('change');
    $('.customer_id').val('Select Customer').trigger('change');
    $('.sale_by_id').val('Select Sales Man').trigger('change');
    $('.account_id').val('Select Account Name').trigger('change');

    // Reload the original data
    $.ajax({
        url: "{{ route('sale.filter') }}",
        method: 'GET',
        data: { reset: true },
        success: function(res) {
            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
            }

            jQuery('#showData').html(res.salesTable);
            jQuery('#showDataModal').html(res.saleInvoiceTable);

            initializeDataTable();
        }
    });
});


    </script>
@endsection
