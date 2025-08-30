@extends('master')
@section('title', '| Sales Invoice Discount Report')
@section('admin')
<div class="row">
    <table class="table table-bordered table-striped summary-print text-center  mb-4">
        <thead class="text-dark">
            <tr>
                <th>Total Discount </th>
            </tr>
        </thead>
        <tbody>
            <tr class="fw-bold">
                <td>{{$invoiceDiscounts->sum('actual_discount') ?? 0}}</td>
            </tr>
        </tbody>
    </table>
    <!----- Filter ------->
    <div class="col-md-12   grid-margin stretch-card filter_box">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                        $customers = App\Models\Customer::where('party_type', 'customer')->get();
                    @endphp

                    <div class="col-md-4">
                        <div class="input-group flatpickr" id="flatpickr-date">
                            <select class="js-example-basic-single form-select select-supplier customer_id"
                                data-width="100%" name="">
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
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="justify-content-left">
                            <button class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</button>
                            <button class="btn btn-sm bg-primary text-dark" id="reset">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!----- Filter End------->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title ">Sales Invoice Discount Report</h6>
                <div id="" class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Invoice</th>
                                <th>Customer Name</th>
                                <th>Discount</th>
                                <th>Date</th>
                                <th>Sales By</th>
                            </tr>
                        </thead>
                        <tbody class="showdiscountData">
                            @include('pos.report.discount_report.invoice_discount_table')
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
       // filter
       document.querySelector('#filter').addEventListener('click', function(e) {
                e.preventDefault();
                let startDate = document.querySelector('.start-date').value;

                let endDate = document.querySelector('.end-date').value;
                let customer_id = document.querySelector('.customer_id').value;
                // console.log(startDate,endDate,customer_id)
                $.ajax({
                    url: "{{ route('sale.invoice.discount.report.filter') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        customer_id,
                    },
                    success: function(res) {
                        jQuery('.showdiscountData').html(res.salesTable);
                    }
                });
            });

            // reset
            document.querySelector('#reset').addEventListener('click', function(e) {
                e.preventDefault();
                $('.start-date').val("");
                $('.end-date').val("");
                $('.customer_id').val('Select Customer').trigger('change');
            });
</script>

@endsection
