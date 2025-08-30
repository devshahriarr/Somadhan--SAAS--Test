@extends('master')
@section('title','| courier order')
@section('admin')

<div class="row">

    <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    </div>

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
    </div>


    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-info">Courier Pending order</h6>

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
                                
                                   <td>{{ $courier->courier->name ?? 'No Assign'}}</td>





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
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu bg-info text-white">
                                            <li>
                                                <a href="#"
                                                   class="dropdown-item text-dark fs-6 assign_courier_modal"
                                                   data-bs-toggle="modal"
                                                   data-sale_id="{{ $courier->sale->id ?? '' }}"
                                                   data-invoice_number="{{ $courier->sale->invoice_number ?? '' }}"
                                                   data-customer_name="{{ $courier->sale->customer->name ?? '' }}"
                                                   data-customer_phone="{{ $courier->sale->customer->phone ?? '' }}"
                                                   data-customer_address="{{ $courier->sale->customer->address ?? '' }}"
                                                   data-due="{{ $courier->sale->due ?? ''}}"
                                                   data-bs-target="#courierModal">
                                                    Assign Courier
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-dark fs-6 cancel" data-id="{{ $courier->id ?? '' }}">
                                                    Cancel Order
                                                </button>
                                            </li>
                                        </ul>
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


@if($courier_manage->count() > 0)
        <!-- Modal -->
<div class="modal fade" id="courierModal" tabindex="-1" aria-labelledby="courierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="courierModalLabel">Assign Courier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="" id="courier_assign" class="needs-validation" novalidate>
                    @csrf
                    @php $couriers = App\Models\CourierManage::all(); @endphp

                    <div class="mb-3">
                        <label for="courier_id" class="form-label fw-semibold">Select Courier</label>
                        <select name="courier_id" id="courier_id" class="form-select courier_select" required>
                            <option value="" disabled selected>-- Choose Courier --</option>
                            @foreach ($couriers as $courier)
                                <option value="{{ $courier->id }}">{{ $courier->courier_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Please select a courier.
                        </div>
                    </div>
                    {{-- @dd($courier_manage) --}}

                    <input type="hidden" id="sale_id" name="sale_id" value="">


                    <div class="row">
                         <!-- Invoice -->
                        <div class="col-md-6">
                            <label for="invoice" class="form-label fw-semibold">Invoice <span class="text-danger">*</span></label>
                            <input type="text" name="invoice" id="invoice" class="form-control" required>
                            <div class="form-text">Must be unique. Alpha-numeric, hyphens or underscores are allowed.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="recipient_name" class="form-label fw-semibold">Recipient Name <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" id="recipient_name" class="form-control" placeholder="e.g. John Smith" maxlength="100"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="recipient_phone" class="form-label fw-semibold">Recipient Phone <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_phone" id="recipient_phone" class="form-control" placeholder="e.g. 01234567890" pattern="\d{11}" maxlength="11"  required>
                                <div class="form-text">Must be exactly 11 digits.</div>
                            </div>
                        </div>
                           <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district_name" class="form-label fw-semibold">District</label>
                                    <select name="district_name" id="district_name" class="form-select district_name" aria-label="Select District">
                                        <option value="">Select District</option>
                                        @foreach ($district as $district_item)
                                            <option value="{{ $district_item['name'] }}">{{ $district_item['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-muted">Please select a district from the list above.</div>
                                </div>
                            </div>





                    </div>
                                        <!-- Steadfast Details Start -->
                        <div id="steadfast_details_container" style="display: none;">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-receipt-cutoff"></i> Steadfast Delivery Details
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- COD Amount -->
                                        <div class="col-md-6">
                                            <label for="cod_amount" class="form-label fw-semibold">COD Amount (৳) <span class="text-danger">*</span></label>
                                            <input type="number" name="cod_amount" id="cod_amount" class="form-control" min="0" step="0.01" placeholder="e.g. 1060"  required>
                                            <div class="form-text">Amount in BDT. Can’t be less than 0.</div>
                                        </div>

                                        <!-- Note (Optional) -->
                                        <div class="col-md-12">
                                            <label for="note" class="form-label fw-semibold">Note <span class="text-muted">(Optional)</span></label>
                                            <textarea name="note" id="note" class="form-control" rows="2" placeholder="e.g. Deliver within 3 PM"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Steadfast Details End -->

                                        <!-- REDX Details Start -->
                                        <div id="redx_details_container" style="display: none;">
                                            <div class="card shadow-sm border-0 mb-4">
                                                <div class="card-header bg-danger text-white fw-bold">
                                                    <i class="bi bi-box-seam"></i> REDX Delivery Details
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-3">

                                                        <!-- Delivery Area -->
                                                        <div class="col-md-6">
                                                            <label for="delivery_area" class="form-label fw-semibold">Delivery Area <span class="text-danger">*</span></label>
                                                            <select name="delivery_area" id="delivery_area" class="form-select delivery_area" required aria-label="Select Delivery Area">
                                                                <!-- Options will be populated dynamically -->
                                                            </select>
                                                            <div class="form-text text-muted">Please choose a delivery area based on the selected district.</div>
                                                        </div>

                                                        <!-- Parcel Weight -->
                                                        <div class="col-md-6">
                                                            <label for="parcel_weight" class="form-label fw-semibold">Parcel Weight (kg) <span class="text-danger">*</span></label>
                                                            <input type="number" name="parcel_weight" id="parcel_weight" class="form-control" placeholder="Enter parcel weight" required min="0" step="0.01">
                                                        </div>

                                                        <!-- Cash Collection Amount -->
                                                        <div class="col-md-6">
                                                            <label for="cash_collection_amount" class="form-label fw-semibold">Cash Collection Amount/COD <span class="text-danger">*</span></label>
                                                            <input type="number" name="cash_collection_amount" id="cash_collection_amount" class="form-control" placeholder="Enter cash collection amount" required  >
                                                        </div>

                                                        <!-- Value -->
                                                        <div class="col-md-6">
                                                            <label for="value" class="form-label fw-semibold">Value <span class="text-danger">*</span></label>
                                                            <input type="number" name="value" id="value" class="form-control" placeholder="Enter value for compensation calculation" required   >
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- REDX Details End -->



                                <!-- Paperfly Start -->
                <div id="paperfly" style="display: none;">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white fw-bold">
                            <i class="bi bi-truck"></i> Paperfly Delivery Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Customer Thana -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Receipent Thana <span class="text-danger">*</span></label>
                                    <input type="text" name="customerThana" class="form-control" placeholder="Enter Customer Thana" required>
                                </div>

                                <!-- Product Size and Weight -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Product Size & Weight <span class="text-danger">*</span></label>
                                    <select name="productSizeWeight" class="form-select" required>
                                        <option value="" disabled selected>Select Size/Weight</option>
                                        <option value="standard">Standard</option>
                                        <option value="large">Large</option>
                                        <option value="special">Special</option>
                                    </select>
                                </div>

                                <!-- Package Price -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Package Price <span class="text-danger">*</span></label>
                                    <input type="number" name="packagePrice" id="packagePrice" class="form-control" placeholder="Enter Package Price" required>
                                </div>

                                <!-- Max Weight -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Max Weight <span class="text-danger">*</span></label>
                                    <input type="number" name="maxWeight" class="form-control" placeholder="Enter Max Weight (kg)" required>
                                </div>

                                <!-- Delivery Option -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Delivery Option <span class="text-danger">*</span></label>
                                    <select name="deliveryOption" class="form-select" required>
                                        <option value="" disabled selected>Select Delivery Option</option>
                                        <option value="regular">Regular</option>
                                        <option value="express">Express</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Paperfly End -->


                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="customer_address" class="form-label fw-semibold">Receipent Address <span class="text-danger">*</span></label>
                        <textarea name="customer_address" id="customer_address" class="form-control" placeholder="Enter customer address" required rows="3"> </textarea>
                    </div>
                </div>


                    <div class="d-grid mt-4">
                        <a  class="btn btn-primary send_courier">Confirm Assign</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endif


    </div>
</div>

{{-- ✅ Updated Script --}}
<script>

/////////////////////////sale information ////////////////





$(document).on('click','.assign_courier_modal',function(){
    var sale_id = $(this).data('sale_id');
   var customer_name = $(this).data('customer_name');
   var customer_phone = $(this).data('customer_phone');
   var customer_address = $(this).data('customer_address');
   var due = $(this).data('due');

   var invoice = $(this).data('invoice_number');


   $('#invoice').val(invoice);
   $('#recipient_name').val(customer_name);
   $('#recipient_phone').val(customer_phone);
   $('#customer_address').val(customer_address);
   $('#cod_amount').val(due);
   $('#cash_collection_amount').val(due);
   $('#packagePrice').val(due);
   $('#sale_id').val(sale_id);

});














/////////////////////courier information ////////////////

    $(document).on('change', '.courier_select', function () {
        var courierId = $(this).val();
        var courier_name = $(this).find("option:selected").text();


        var courier = courier_name
            .toLowerCase()
            .replace(/[-_]+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

        console.log("Courier ID:", courierId);
        console.log("Formatted Courier Name:", courier);


        if (courier === 'steadfast') {
            $('#steadfast_details_container').fadeIn();
            $('#redx_details_container').fadeOut();
            $('#paperfly').fadeOut();
        }
        else if (courier === 'redx') {
            $('#steadfast_details_container').fadeOut();
            $('#paperfly').fadeOut();
            $('#redx_details_container').fadeIn();
        }
        else if (courier === 'paperfly') {
            $('#steadfast_details_container').fadeOut();
            $('#redx_details_container').fadeOut();
            $('#paperfly').fadeIn();
        }
    });

    $(document).on('change', 'select[name="district_name"]', function () {
    var district_name = $(this).val();

    $.ajax({
        url: '{{route("get.area.district")}}',
        type:'POST',
        data:{
            _token: "{{ csrf_token() }}",
            district_name: district_name
        },
        success:function(data){
            if(data.status===200){
                var option = '<option value="">Select Area</option>';
                var  areas = data.area;
                $('.delivery_area').val('').trigger('change');
                $('.delivery_area').empty();

                areas.forEach(function(area) {
                    areas.forEach(function(area) {
                        option += '<option value="' + area.name + '" data-id="' + area.id + '">' + area.name + '</option>';
                    });


                });
                $('.delivery_area').append(option);

            }
            else{
                var  option = '<option value="">No Area Get</option>';
                $('.delivery_area').empty();
                $('.delivery_area').append(option);
            }
        },
    });
    });



    $(document).on('change', '.delivery_area', function () {
    var selectedId = $(this).find('option:selected').data('id');

    // Remove old input if any
    $('input[name="area_id"]').remove();

    // Append new hidden input
    if (selectedId) {
        $('<input>').attr({
            type: 'hidden',
            name: 'area_id',
            value: selectedId
        }).appendTo('form'); // Adjust the selector to your form or preferred container
    }
});

$(document).on('click', '.send_courier', function () {

    let recipientName = $('input[name="recipient_name"]').val().trim();
    let recipientPhone = $('input[name="recipient_phone"]').val().trim();
    let invoiceNumber = $('input[name="invoice"]').val().trim();
    let customerAddress = $('textarea[name="customer_address"]').val().trim();

    // Steadfast fields
    let codAmount = $('input[name="cod_amount"]').val().trim();
    let note = $('textarea[name="note"]').val().trim();

    // REDX fields
    let deliveryArea = $('select[name="delivery_area"]').val();
    let parcelWeight = $('input[name="parcel_weight"]').val().trim();
    let cashCollectionAmount = $('input[name="cash_collection_amount"]').val().trim();
    let value = $('input[name="value"]').val().trim();

    // Paperfly fields
    let customerThana = $('input[name="customerThana"]').val().trim();
    let productSizeWeight = $('select[name="productSizeWeight"]').val();
    let packagePrice = $('input[name="packagePrice"]').val().trim();
    let maxWeight = $('input[name="maxWeight"]').val().trim();
    let deliveryOption = $('select[name="deliveryOption"]').val();

        let courierName = $('#courier_id option:selected').text();

        let hasError = false;
        var courier = courierName
            .toLowerCase()
            .replace(/[-_]+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
        let courierId = $('select[name="courier_id"]').val();
        // Validate if courier is empty
        if (courierId === '' || courierId == null) {
            //  console.log("Courier is empty");
            hasError = true;
           alert('Please select a courier.');
        }


  // Flag to check if validation fails

    // Clear previous error messages
    $('.error-message').remove(); // Remove all previous error messages


    if (recipientName === '') {
        hasError = true;
        $('input[name="recipient_name"]').after('<div class="error-message text-danger">Recipient Name is required.</div>');
    }

    if (recipientPhone === '') {
        hasError = true;
        $('input[name="recipient_name"]').after('<div class="error-message text-danger">Recipient Phone is required.</div>');
    }

    if (invoiceNumber === '') {
        hasError = true;
        $('input[name="invoice"]').after('<div class="error-message text-danger">Invoice Number is required.</div>');
    }

    if (customerAddress === '') {
        hasError = true;
        $('textarea[name="customer_address"]').after('<div class="error-message text-danger">Customer Address is required.</div>');
    }

    if (courier === 'steadfast') {
        // Steadfast validation
        if (codAmount === '') {
            hasError = true;

            $('input[name="cod_amount"]').after('<div class="error-message text-danger">COD Amount is required.</div>');
        }

    }

    if (courier === 'redx') {
        // REDX validation
        if (deliveryArea === '' || deliveryArea == null) {
            hasError = true;
            $('select[name="delivery_area"]').after('<div class="error-message text-danger">Delivery Area is required.</div>');
        }

        if (parcelWeight === '') {
            hasError = true;
            $('input[name="parcel_weight"]').after('<div class="error-message text-danger">Parcel Weight is required.</div>');
        }

        if (cashCollectionAmount === '') {
            hasError = true;
            $('input[name="cash_collection_amount"]').after('<div class="error-message text-danger">Cash Collection Amount is required.</div>');
        }

        if (value === '') {
            hasError = true;
            $('input[name="value"]').after('<div class="error-message text-danger">Value is required.</div>');
        }
    }

    if (courier === 'paperfly') {
        // Paperfly validation
        if (customerThana === '') {
            hasError = true;
            $('input[name="customerThana"]').after('<div class="error-message text-danger">Customer Thana is required.</div>');
        }

        if (productSizeWeight === '' || productSizeWeight == null) {
            hasError = true;
            $('select[name="productSizeWeight"]').after('<div class="error-message text-danger">Product Size & Weight is required.</div>');
        }

        if (packagePrice === '' || parseFloat(packagePrice) <= 0) {
            hasError = true;
            $('input[name="packagePrice"]').after('<div class="error-message text-danger">Package Price must be greater than 0.</div>');
        }

        if (maxWeight === '' || parseFloat(maxWeight) <= 0) {
            hasError = true;
            $('input[name="maxWeight"]').after('<div class="error-message text-danger">Max Weight must be greater than 0.</div>');
        }

        if (deliveryOption === '' || deliveryOption == null) {
            hasError = true;
            $('select[name="deliveryOption"]').after('<div class="error-message text-danger">Delivery Option is required.</div>');
        }
    }

    if (hasError) {
        return false; // Prevent form submission if there are errors
    }

     var formdata = new FormData($('#courier_assign')[0]);
    $.ajax({
        url: "{{ route('courier.assign.order') }}",
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        success: function(data) {
            console.log(data);
            if(data.status===200){
                $('#courier_assign')[0].reset();
                $('#courierModal').modal('hide');
                toastr.success("Courier Assign Successfully");
                location.reload();
            }
            else{
                toastr.error("Something Went Wrong");
            }
        }

    });
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
