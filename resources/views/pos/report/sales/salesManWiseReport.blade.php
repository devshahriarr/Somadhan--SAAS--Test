@extends('master')
@section('admin')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Salesman Wise Report</li>
        </ol>
    </nav>
    {{-- <table class="table table-bordered table-striped summary-print text-center  mb-4">
        <thead class="text-dark">
            <tr>
                <th>Salesman Sale Count</th>
                <th>Total Sale Amount </th>
                <th>Total Discount Amount </th>
                <th>Total Paid</th>
                <th>Total Due</th>
                <th>Total Invoice</th>
            </tr>
        </thead>
        @php
             if(Auth::user()->role ==='superadmin' || Auth::user()->role ==='admin'){
                $salesData = App\Models\Sale::all();
             }else{
                 $salesData = App\Models\Sale::where('branch_id', Auth::user()->branch_id)->get();
             }

        @endphp
        <tbody>
            <tr class="fw-bold">
                <td>{{$salesData->count()}}</td>
                <td>{{$salesData->sum('change_amount')}}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table> --}}
    <div class="col-md-12 grid-margin stretch-card filter_box">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <h6 class="card-title">Salesman Wise Report</h6>
                </div>
                <form id="filter_form">
              <div class="row">

                <div class="col-md-3">
                    <div class="input-group flatpickr" id="flatpickr-date">
                        <input type="text" class="form-control from-date flatpickr-input start-date"
                            placeholder="Start date" data-input="" readonly="readonly" name="start_date">
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
                        <input type="text" class="form-control from-date flatpickr-input end-date"
                            placeholder="End date" data-input="" readonly="readonly" name="end_date">
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
                        <select class="form-select" id="salesman_id" name="salesman_id">
                            <option value="">Select Salesman</option>
                            @php
                           $salesmans = App\Models\User::where('role', '!=', 'superadmin')
                            ->get();
                           @endphp
                            @foreach ($salesmans as $salesman)
                            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                            @endforeach
                            </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="salesman_id" name="day_wise">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>

                            </select>
                    </div>
                    <div class="col-md-6 mb-3 mt-3">
                        <div class="justify-content-left">
                            <a href="#" class="btn btn-sm bg-info text-dark mr-2" id="filter">Filter</a>
                            <button class="btn btn-sm bg-primary text-dark" id="reset">Reset</button>
                        </div>
                    </div>

              </div>
            </form>
            </div>


        </div>
    </div>





    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Salesman Wise Sales Report</h6>
                    <div class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sale By</th>
                                    <th>Invoice Number</th>
                                    <th>Total Amount</th>
                                    <th>Actual Discount</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                </tr>
                            </thead>
                            <tbody id="table_data">

                                @foreach ($salesmanWiseReport as $salesmanWiseReport)
                                @php
                                $salesMan = App\Models\User::where('id',$salesmanWiseReport->sale_by)->first();
                              @endphp
                                    <tr class="first_data">
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $salesMan->name}}</td>
                                        <td>{{ $salesmanWiseReport->invoice_number}}</td>
                                        <td>
                                            {{ number_format($salesmanWiseReport->total, 2) }}
                                        </td>
                                        <td>
                                            {{ number_format($salesmanWiseReport->actual_discount, 2) }}
                                        </td>
                                        <td>

                                            {{ number_format($salesmanWiseReport->change_amount, 2) }}

                                        </td>
                                        <td>

                                            {{ number_format($salesmanWiseReport->paid, 2) }}

                                        </td>
                                        <td>

                                            {{ number_format($salesmanWiseReport->due, 2) }}

                                        </td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>


  $(document).on('click', '#filter', function() {
   var formdata= new  FormData($('#filter_form')[0]);

        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        })
        $.ajax({
            url: "{{ route('salesmanWiseReport') }}",
            type: "POST",
            data: formdata,
            contentType: false,
            processData:false,
            success: function(data) {
                console.log(data);
              if(data.status==200){
                 let salesmanWiseReport = data.salesmanWiseReport;
                  $('#table_data').empty();
                 salesmanWiseReport.forEach(function(item,index){
                    $('#table_data').append(`
                        <tr >
                            <td class="text-end">${index+1}</td>
                           <td class="text-end">${item.sale_by.name}</td>
                           <td class="text-end">${item.invoice_number}</td>
                           <td class="text-end">${item.total}</td>
                           <td class="text-end">${item.actual_discount}</td>
                           <td class="text-end">${item.change_amount}</td>
                           <td class="text-end">${item.paid}</td>
                           <td class="text-end">${item.due}</td>

                        </tr>

                    `);
                 });

              }

            }


        });

    });






    flatpickr("#start_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        placeholder: "Start Date"
    });

    flatpickr("#end_date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        placeholder: "Start Date"
    });
</script>


@endsection
