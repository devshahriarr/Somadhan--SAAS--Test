
@extends('master')
@section('title','| Add Employee Salary')
@section('admin')
<div class="row">
    @if(Auth::user()->can('employee-salary.list'))
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('employee.salary.view')}}" class="btn btn-info">View Salary History</a></h4>
    </div>
</div>
@endif
<div class="col-md-12 stretch-card">
<div class="card">
	<div class="card-body">
		<h6 class="card-title text-info">Employee Salary</h6>
			<form id="myValidForm" action="{{route('employee.salary.store')}}" method="post"  >
				@csrf
				<div class="row">
					<!-- Col -->
                    <div class="col-sm-6 form-valid-groups">
						<div class="mb-3" id="flatpickr-date">
							<label class="form-label">Salary Date<span class="text-danger">*</span></label>
                            <input type="text" name="date" class="form-control start-date" placeholder="Date" data-input>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Select Branch <span class="text-danger">*</span></label>
                            <select class="form-select mb-3 js-example-basic-single"  name="branch_id">
                                <option selected="" value="">Select Branch</option>
                                @foreach ($branch as $branchs)
                                <option value="{{$branchs ->id}}">{{$branchs->name}}</option>
                                @endforeach
                            </select>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6">
						<div class="mb-3 form-valid-groups">
							<label class="form-label">Select Employee Name<span class="text-danger">*</span></label>
							<select class="form-select mb-3 js-example-basic-single" name="employee_id">
                                {{-- <option selected="" disabled>Select Employee Name</option> --}}
                                <option ></option>

                            </select>
						</div>
					</div>

					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
							<label class="form-label">Salary Amount <span id="employeeSalary"></span></label>
							<input type="number" class="form-control" name="debit"  placeholder="0.00">
                            <span id="advancedSalary">Note: Avanced Amount:0</span>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
							<label class="form-label">Select Bank Acoount <span class="text-danger">*</span></label>
                            <select class="form-select js-example-basic-single" name="payment_method">
                                 <option selected="" disabled>Select Bank Name</option>
                                 @foreach ($bank as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
						</div>
					</div><!-- Col -->
					<div class="col-sm-6 form-valid-groups">
						<div class="mb-3">
                            <label class="form-label">Note</label>
							<textarea name="note" class="form-control" id=""  cols="20" rows="5"></textarea>

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
                payment_method: {
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
                payment_method: {
                    required : 'Please Select Payment Method',
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
//Dropdown js
	$(document).ready(function(){
		$('select[name="branch_id"]').on('change',function(){
			var branch_id = $(this).val();
			if(branch_id){
				$.ajax({
					url:"{{('/employee/branch')}}/"+branch_id,
					type:"GET",
					dataType:'json',
					success:function(data){
						$('select[name = "employee_id"]').html('');
						var d = $('select[name= "employee_id"]').empty();
						$.each(data,function(key,value){
							$('select[name= "employee_id"]').append(
							'<option value="'+value.id+'">'+value.full_name+ " (" + value.salary+")"+  '</option>')
						});
					},
				});
			}
			else{
				alert('Danger');
			}
		});
        //
        $('select[name="employee_id"]').on('change', function(){
        var employee_id = $(this).val();
        // alert(employee_id);
        let date = document.querySelector('.start-date').value;
        if(employee_id){
            // AJAX request to fetch additional information about the selected employee
            $.ajax({
                url: '/employee/info/' +employee_id,
                type: "GET",
                dataType: 'json',
                data: {
                    date
                },
                success: function(employee){
                // alert(employee.data)
                    // console.log(employee.data);
                    if(employee.data !== null){
                        $('#employeeSalary').text( "Due: ৳ "+(employee.data.creadit - employee.data.debit));
                        if(employee.data.creadit != employee.data.debit) {
                            $('#advancedSalary').text( "Note: Avanced Amount: ৳ " + employee.data.debit);
                        }
                    }
                    else{
                        $('#employeeSalary').text( "*");
                        $('#advancedSalary').text( "Note: Avanced Amount: ৳ " + 0 );
                    }
                },
            });
        }
    });
	});

</script>
@endsection
