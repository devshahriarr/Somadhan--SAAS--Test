@extends('master')
@section('title','| Add Customer')

@section('admin')
<div class="row">
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">

        <h4 class="text-right"><a href="{{route('customer.view')}}" class="btn btn-info">View All Customer</a></h4>
    </div>
</div>
<div class="col-md-12 stretch-card">
<div class="card">
	<div class="card-body">
		<h6 class="card-title text-info">Add Customer</h6>
			<form id="myValidForm" action="{{route('customer.store')}}" method="post"  >
				@csrf
				<div class="row">
					<!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label"> Name <span class="text-danger">*</span></label>
							<input type="text" name="name" class="form-control field_required" placeholder="Enter Customer name">
						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Phone<span class="text-danger">*</span></label>
							<input type="text" name="phone" class="form-control" placeholder="Enter Customer Phone">
						</div>
					</div>
					<div class="col-sm-6">
						<div class="mb-3">
							<label class="form-label">Email address</label>
							<input type="email" name="email" class="form-control" placeholder="Enter Customer email">
						</div>
					</div><!-- Col -->
                    <div class="col-sm-6">
						<div class="mb-3">
							<label class="form-label">Previous Due</label>
							<input type="number" class="form-control" name="wallet_balance" placeholder="0.00">
						</div>
					</div><!-- Col -->
					<div class="col-sm-12">
						<div class="mb-3">
							<label class="form-label">Customer Address</label>
							<textarea name="address" class="form-control"  placeholder="Write Customer Address" rows="4" cols="50"></textarea>
						</div>
					</div><!-- Col -->

				</div><!-- Row -->
				<div class="row">
					{{-- <div class="col-sm-4">
						<div class="mb-3">
							<label class="form-label">Opening Receivable</label>
							<input type="number" class="form-control" name="opening_receivable"  placeholder="0.00">
						</div>
					</div><!-- Col -->
					<div class="col-sm-4">
						<div class="mb-3">
							<label class="form-label">Opening Payable</label>
							<input type="number" name="opening_payable" class="form-control" placeholder="0.00">
						</div>
					</div><!-- Col --> --}}

				</div><!-- Row -->
				<div class="row">
				{{-- <div class="col-sm-6">
						<div class="mb-3">
							<label class="form-label">Total Receivable</label>
							<input type="number" class="form-control" name="total_receivable"  placeholder="0.00">
						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 ">
							<label class="form-label">Total Payable</label>
							<input type="number" class="form-control" name="total_payable" placeholder="0.00">
						</div>
					</div><!-- Col -->
					<!-- Col -->
				</div><!-- Row --> --}}
				<div >
				<input type="submit" class="btn btn-primary submit" value="Save">
				</div>
			</form>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
        $('#myValidForm').validate({
            rules: {
                name: {
                    required : true,
                },
                phone: {
                    required : true,
                },
            },
            messages :{
                name: {
                    required : 'Please Enter Customer Name',
                },
                phone: {
                    required : 'Please Enter Customer Phone Number',
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
