@extends('master')
@section('title','| courier order')
@section('admin')

<div class="row">

    <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    </div>
{{--
    <div class="row">
        <!-- Total Orders -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-shopping-basket fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Total Orders</h6>
                        <h4 class="mb-0">{{$courier_total_order??0}}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Orders -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-cart-plus fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">New Orders</h6>
                        <h4 class="mb-0">{{$new_order??0}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}




    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">Courier Processing Order</h6>

                <div class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Courier Name</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Receivable Amount</th>
                                <th>Due</th>
                                <th>Paid</th>
                                <th>Courier Wise Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="showData">
                            @if ($courier_manage->count() > 0)
                            @foreach ($courier_manage as $key => $courier)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $courier->sale->sale_date ?? ''}}</td>
                               <td><a href="{{ route('sale.invoice',$courier->sale->id) }}">{{ $courier->sale->invoice_number ?? ''}}</td>
                                <td>{{ $courier->sale->customer->name ?? ''}}</td>

                                 @php
                                     $courier_manage = App\Models\CourierManage::where('id', $courier->courier_id)->first();
                                     
                                 @endphp



                                 <td>{{ $courier_manage->courier_name ?? 'No Assign'}}</td>
                                <td>{{ $courier->sale->quantity ?? ''}}</td>
                                <td>{{ $courier->sale->total ?? ''}}</td>
                                <td>{{ $courier->sale->receivable ?? ''}}</td>
                                <td>{{ $courier->sale->due ?? ''}}</td>
                                <td>{{ $courier->sale->paid ?? ''}}</td>



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






                                <td class="text-center">
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
                                </td>
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







</script>



@endsection
