@extends('master')
@section('title','| courier order')
@section('admin')

<div class="row">
                  @php
                        $courier = strtolower(str_replace(' ', '', $courier_name));
                    @endphp
    <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    </div>
    <div class="row mb-3 align-items-center">
    <!-- Left: Section title (optional) -->
    <div class="col-md-6 col-sm-12">
        <h5 class="fw-semibold mb-0">Order Summary</h5>
    </div>
<!-- Right: Filter Dropdown -->
<div class="col-md-6 col-sm-12 text-md-end mt-2 mt-md-0">
    <form id="filterForm" class="mb-3">
        <input type="hidden" name="courier_id" id="courier_id" value="{{ $order->first()->courier_id ??''}}">
        <div class="d-inline-flex flex-wrap align-items-center bg-light px-3 py-2 rounded shadow-sm">
            <label for="filter_type" class="me-2 fw-semibold text-primary mb-0" style="white-space: nowrap;">
                Filter By:
            </label>
            <select class="form-select form-select-sm border-primary text-primary fw-semibold filter_type"
                    name="filter_type" id="filter_type"
                    style="min-width: 140px; box-shadow: none;">
                <option value="today" selected>Today</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
        </div>
    </form>
</div>



<!-- Summary Cards -->
<div class="row">
    <!-- Total Orders -->
    <div class="col-md-4 col-sm-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 50px; height: 50px;">
                    <i class="fas fa-shopping-basket fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Total Orders</h6>
                    <h4 class="mb-0" id="totalToday">{{ $totalToday ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- New Orders -->
    <div class="col-md-4 col-sm-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 50px; height: 50px;">
                    <i class="fas fa-cart-plus fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">New Orders</h6>
                    <h4 class="mb-0" id="pendingToday">{{ $pendingToday ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Courier Balance -->
    <div class="col-md-4 col-sm-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 50px; height: 50px;">
                    <i class="fas fa-wallet fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Courier Balance</h6>
                    @if($courier == 'steadfast')
                        <h4 class="mb-0" id="courierBalance">৳{{ $balance ?? 0 }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">


                    @if($courier == 'steadfast')
                        Steadfast Courier
                    @elseif($courier == 'redx')
                        RedX Courier
                    @elseif($courier == 'paperfly')
                        Paperfly Courier
                    @endif
                </h6>

                <div class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Receivable Amount</th>
                                <th>Due</th>
                                <th>Paid</th>
                                 <th>Courier Status</th>
                                 <th>Courier Wise Order</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody class="showData">
                            @if ($order->count() > 0)
                            @foreach ($order as $key => $courier)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $courier->sale->sale_date ?? ''}}</td>
                                <td>{{ $courier->sale->invoice_number ?? ''}}</td>
                                <td>{{ $courier->sale->customer->name ?? ''}}</td>
                                <td>{{ $courier->sale->quantity ?? ''}}</td>
                                <td>{{ $courier->sale->total ?? ''}}</td>
                                <td>{{ $courier->sale->receivable ?? ''}}</td>
                                <td>{{ $courier->sale->due ?? ''}}</td>
                                <td>{{ $courier->sale->paid ?? ''}}</td>
                                <td>{{$courier->courier_status??''}}</td>


                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Courier List
                                        </button>
                                        <ul class="dropdown-menu bg-info text-white">
                                           @foreach ($couriers as $courierdata)
                                           <li>
                                            <a class="dropdown-item" href="{{ route('courier.wise.order',$courierdata->id) }}" >{{$courierdata->courier_name}}</a>
                                           </li>
                                           @endforeach


                                        </ul>
                                    </div>
                                </td>






                                {{-- <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Change Status Button -->
                                        <button class="btn btn-sm btn-success complete" title="Change Status" data-id="{{  $courier->id ?? ''  }}">
                                            <i class="fas fa-sync-alt"></i> Complete Order
                                        </button>

                                        <!-- Cancel Order Button -->
                                        <button class="btn btn-sm btn-danger cancel" title="Cancel Order" data-id="{{  $courier->id ?? ''  }}">
                                            <i class="fas fa-times-circle"></i> Cancel
                                        </button>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>




    </div>
</div>

{{-- ✅ Updated Script --}}
<script>

/////////////////////////sale information ////////////////


 $(document).on('click','.complete',function(){
    var order_id = $(this).data('id');
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            url: "{{ route('courier.proceccing.order.status.change') }}",
            type: "POST",
            data: { order_id: order_id },
            success:function(data){
                if(data.status===200){
                    toastr.success("Order Complete Successfully");
                    location.reload();
                }
            }

        })
 });
















////////////////////////////courier Cancel Order//////////////////////////

$(document).on('click','.cancel',function(){
    var order_id = $(this).data('id');
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ route('courier.cancel.order') }}",
        type: "POST",
        data: { order_id: order_id },
        success: function(data) {
            console.log(data);
            if(data.status===200){
          toastr.success("Order Cancel Successfully");
          location.reload();
            }
            else{
                toastr.error("Something Went Wrong");
            }
        }
    });
});


$(document).on('change','.filter_type',function(){
    var filter_type = $(this).val();
    var courier_id = $('#courier_id').val();
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $.ajax({
        url: "{{ route('courier.wise.filter.order') }}",
        type:"POST",
        data: {filter_type:filter_type,courier_id:courier_id},
        success:function(data){
            if(data.status===200){
                $('#totalToday').text(data.total_order);
                $('#pendingToday').text(data.pending_order);
                
            }
        }
    });
})




</script>



@endsection
