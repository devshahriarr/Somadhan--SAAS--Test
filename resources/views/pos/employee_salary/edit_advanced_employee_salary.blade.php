@extends('master')
@section('title','| Edit Advanced Employee Salary')
@section('admin')
<div class="row">
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">

        <h4 class="text-right"><a href="{{route('employee.salary.advanced.view')}}" class="btn btn-info">View Advanced Salary History</a></h4>
    </div>
</div>
<div class="col-md-12 stretch-card">
<div class="card">
	<div class="card-body">
		<h6 class="card-title text-info">Edit Advanced Employee Salary</h6>
			<form id="myValidForm" action="{{route('employee.salary.advanced.update',$employeeSalary->id)}}" method="post"  >
				@csrf
				<div class="row">
					<!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label"> Branch Name<span class="text-danger">*</span></label>
                                <input type="text"name="branch_id" value="{{$employeeSalary['branch']['name']}}"   class="form-control" data-input>

						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Employee Name<span class="text-danger">*</span></label>

                                <input type="text"value="{{$employeeSalary['emplyee']['full_name']}}"  name="employee_id" class="form-control" data-input>

						</div>
					</div>
					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3" id="flatpickr-date">
							<label class="form-label">Salary Date<span class="text-danger">*</span></label>
                            <input type="text" name="date" class="form-control" value="{{$employeeSalary->date}}" placeholder="Date" data-input>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
							<label class="form-label">Advanced Payemnt Salary Amount({{$employeeSalary->debit}} Tk) (Due Amount {{$employeeSalary->balance}} Tk)<span class="text-danger">*</span></label>
							<input type="number" class="form-control"value="{{$employeeSalary->balance}}"  name="debit"  placeholder="0.00">
						</div>
					</div><!-- Col -->
                    <div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
							<label class="form-label">Select Bank Acoount <span class="text-danger">*</span></label>
                            <select class="form-select js-example-basic-single" name="payment_method">
                                 <option selected="" disabled>Select Bank Name</option>
                                 @foreach ($bank as $item)
                                <option value="{{$item->id}}" {{$item->id == $employeeSalary->payment_method ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
							<label class="form-label">Advanced Salaray Reason Note</label>
							<textarea name="note" class="form-control" id="" cols="20" rows="5">{{$employeeSalary->note}}</textarea>
						</div>
					</div><!-- Col -->
				</div><!-- Row -->
				<div >
				<input type="submit" class="btn btn-primary submit" value="Payment">
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
                branch_id: {
                    required : true,
                },
                employee_id: {
                    required : true,
                },
                date: {
                    required : true,
                },
                debit: {
                    required : true,
                },
            },
            messages :{
                branch_id: {
                    required : 'Please Select Branch Name',
                },
                employee_id: {
                    required : 'Please Select Employee',
                },
                date: {
                    required : 'Please Select Salaray Date',
                },
                debit: {
                    required : 'Please Enter Salary Amount',
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
