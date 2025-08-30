@extends('master')
@section('title','| Promottion Edit')
@section('admin')
<div class="row">
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">

        <h4 class="text-right"><a href="{{route('promotion.view')}}" class="btn btn-primary">View All Promotion</a></h4>
    </div>
</div>
<div class="col-md-12 stretch-card">
<div class="card">
	<div class="card-body">
		<h6 class="card-title text-info">Edit Promotion</h6>
			<form id="myValidForm" action="{{route('promotion.update',$promotion->id)}}" method="post">
				@csrf
				<div class="row">
					<!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Promotion Name<span class="text-danger">*</span></label>
							<input type="text" name="promotion_name" value="{{$promotion->promotion_name}}" class="form-control field_required" placeholder="Enter promotion name">
						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
                            <label class="form-label">Start Date<span class="text-danger">*</span></label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control" value="{{$promotion->start_date}}" name="start_date" placeholder="Select Start date" data-input>
                                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                              </div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
                            <label class="form-label">End Date<span class="text-danger">*</span></label>
                            <div class="input-group flatpickr" id="flatpickr-date">
                                <input type="text" class="form-control" value="{{$promotion->end_date}}" name="end_date" placeholder="Select End date" data-input>
                                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                              </div>
						</div>
					</div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-valid-groups">
                            <label class="form-label">Discount Type<span class="text-danger">*</span></label>
                            <select class="form-select" name="discount_type" aria-invalid="false">
                                <option selected="" disabled="">Select Discount Type </option>
                                 <option value="percentage" {{ $promotion->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                 <option value="fixed_amount" {{ $promotion->discount_type == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                        </div>
					</div>
                    <div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Discount Value<span class="text-danger">*</span></label>
							<input type="number" name="discount_value" value="{{$promotion->discount_value}}" class="form-control field_required" placeholder="Enter discount value">
						</div>
					</div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-valid-groups">
                            <label class="form-label">Descriptions <small>(Optional)</small></label>
                            <textarea name="description" class="form-control" id="" cols="10" rows="5">{{$promotion->description}}</textarea>
                        </div>
					</div>
				</div><!-- Row -->
				<div>
				<input type="submit" class="btn btn-primary submit" value="Save">
				</div>
			</form>
	</div>
</div>
</div>
</div>
<script>
      $(document).ready(function (){
        $('#myValidForm').validate({
            rules: {
                promotion_name: {
                    required : true,
                },
                start_date: {
                    required : true,
                },
                end_date: {
                    required : true,
                },
                discount_type:{
                    required : true,
                },
                discount_value:{
                    required : true,
                },

            },
            messages :{
                promotion_name: {
                    required : 'Please Enter Promotion Name',
                },
                start_date: {
                    required : 'Select Start Date',
                },
                end_date: {
                    required : 'Select End Date',
                },
                discount_type: {
                    required : 'Select Discount Type',
                },
                discount_value: {
                    required : 'Please Enter Discount Value',
                },
            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-valid-groups').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            },
        });
    });
</script>
@endsection
