@extends('master')
@section('title','| Damage Edit')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('damage.view') }}" class="btn btn-info">All Damage List</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Damage</h6>
                    <form id="myValidForm" action="{{ route('damage.update',$damage_info->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="mb-3 col-md-6">
                                @php
                                   $products = App\Models\Product::where('branch_id', Auth::user()->branch_id)
                                    ->withSum('stockQuantity', 'stock_quantity')
                                    ->having('stock_quantity_sum_stock_quantity', '>', 0)
                                    ->orderBy('stock_quantity_sum_stock_quantity', 'asc')
                                    ->get();
                                @endphp
                                <label for="ageSelect" class="form-label">Product <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select" name="product_id"
                                    data-width="100%"  onchange="show_quantity(this)" readonly>
                                    @if ($products->count() > 0)
                                        <option selected disabled>Select Damaged Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{$damage_info->product_id == $product->id ? 'selected' : ''}}> {{ $product->name.' ' .$product->unit->name}}</option>
                                        @endforeach
                                    @else
                                        <option selected disabled>Please Add Product</option>
                                    @endif
                                </select>
                                <span class="text-danger product_select_error"></span>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Quantity
                                        <span class="text-danger">*</span>
                                        <span class="text-primary" id="show_stock"></span>
                                        <span class="text-primary" id="show_unit"></span>
                                    </label>

                                    <input type="text" value="{{$damage_info->qty}}" id="damageQty" name="pc" onkeyup="damage_qty(this);" class="form-control" placeholder="0"  autocomplete="off" >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Date<span class="text-danger">*</span></label>
                                    <div class="input-group flatpickr me-2 mb-2 mb-md-0" id="dashboardDate">
                                        <span class="input-group-text input-group-addon bg-transparent border-primary"
                                            data-toggle><i data-feather="calendar" class="text-primary"></i></span>
                                        <input type="text" name="date"
                                            class="form-control bg-transparent border-primary" placeholder="Select date"
                                            data-input value="{{$damage_info->date}}">
                                    </div>
                                    {{-- <input type="date"  class="form-control" placeholder="Enter Date"> --}}
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" class="form-control" placeholder="Write About Damages" rows="4" cols="50"></textarea>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Save" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">



        //show available Quantity information
        function show_quantity(event) {
            let newValue = event.value;

            $.ajax({
                url: '/damage/show_quantity/' + newValue,
                type: 'get',
                success: function(res) {
                    $('#show_stock').text(res.stock_quantity);
                    $('#show_unit').text(res.unit.name);
                    $('#damageQty').removeAttr('disabled');
                }
            });
        }
        //Damage Quantity validation
        function damage_qty(event) {

            let newValue = event.value;
            let available_stock = parseInt($('#show_stock').text());

            if(available_stock< newValue){
                event.value = '';
                $('#submit_btn').attr("disabled", "disabled");
                Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: 'Invalid Quantity',
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                $('#submit_btn').removeAttr('disabled')
            }

        }


        $(document).ready(function() {

            $('#myValidForm').validate({
                rules: {
                    product_id: {
                        required: true,
                    },
                    pc: {
                        required: true,
                    },
                    date: {
                        required: true,
                    },
                },
                messages: {
                    damaged_product_id: {
                        required: 'Please Enter the Name of Damaged Product',
                    },
                    pc: {
                        required: 'Please Enter the number of Damaged Products',
                    },
                    date: {
                        required: 'Please Enter date of Damaged Products',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-valid-groups').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });
        });



    </script>
@endsection
