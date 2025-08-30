@extends('master')
@section('title', '| SMS Report')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sms Report</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12   grid-margin stretch-card filter_box">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control start-date flatpickr-input"
                                    placeholder="Start date" data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control  flatpickr-input end-date" placeholder="End date"
                                    data-input="" readonly="readonly">
                                <span class="input-group-text input-group-addon" data-toggle="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        @php
                            if (Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
                                $customers = App\Models\Customer::where('party_type', 'customer')->get();
                            } else {
                                $customers = App\Models\Customer::where('party_type', 'customer')
                                    ->where('branch_id', Auth::user()->branch_id)
                                    ->get();
                            }
                        @endphp
                        <div class="col-md-4">
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <select class="js-example-basic-single form-select select-customer" data-width="100%"
                                    name="">
                                    @if ($customers->count() > 0)
                                        <option selected disabled>Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Employee</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="justify-content-left">
                                <button class="btn btn-sm bg-info text-dark mr-2" id="smsFilter">Filter</button>
                                <button class="btn btn-sm bg-primary text-dark" onclick="window.location.reload();"
                                    id="reset">Reset</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Sms Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table" style="padding-top:10px">
                            <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Number</th>
                                    <th>Send Date</th>
                                    <th>Purpose</th>
                                    <th>message</th>
                                </tr>
                            </thead>
                            {{-- @dd($customer) --}}
                            <tbody id="sms_show_info">
                                @include('pos.report.sms.sms-filter-table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // transactioninfo
            $('#smsFilter').click(function(e) {
                e.preventDefault();
                // alert('ok');
                let startDate = document.querySelector('.start-date').value;
                let endDate = document.querySelector('.end-date').value;
                //alert(startDate);
                let customerId = document.querySelector('.select-customer').value;
                // alert(customerId);
                $.ajax({
                    url: "{{ route('sms.report.filter') }}",
                    method: 'GET',
                    data: {
                        startDate,
                        endDate,
                        customerId
                    },
                    success: function(res) {
                        $("#sms_show_info").html(res);
                    }
                });
            });
        });
    </script>
@endsection
