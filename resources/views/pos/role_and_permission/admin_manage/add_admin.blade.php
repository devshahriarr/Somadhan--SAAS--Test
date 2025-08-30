@extends('master')
@section('title', '| Add Admin')
@section('admin')

   @php
       $settings = App\models\PosSetting::all()->first();
       $userIdentity = strtolower(str_replace(' ', '', Auth::user()->role));
   @endphp


    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('admin.all') }}" class="btn btn-info">All Admin</a></h4>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Add Admin Form</h6>
                        @php
                        $employees = App\Models\Employee::all();
                        @endphp
                    <form class="forms-sample" id="myValidForms" method="post" action="{{ route('admin.store') }}">
                        @csrf
                        <div class="row mb-3 ">
                            <label for="empoloyee_id_product" class="col-sm-3 col-form-label">Employee Name</label>
                            <div class="col-sm-9 form-valid-groupss">
                                <select class="js-example-basic-single form-select" id="employeeSelect"
                                    name="employee_id" data-width="100%">
                                    <option selected disabled>Select Employee </option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="unselectButton" class="btn" style="background-color:#5660D9">Unselect</button>
                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <label for="exampleInput1Username2" class="col-sm-3 col-form-label">Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="text" name="name" class="form-control" id="exampleInput1Username2"
                                    placeholder="Name">
                            </div>
                        </div>
                        <div class="row mb-3 form-valid-groups">
                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="email" name="email" class="form-control" id="exampleInputEmail2"
                                    autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile (Optional)</label>
                            <div class="col-sm-9">
                                <input type="tel" name="phone" class="form-control" id="exampleInputMobile"
                                    placeholder="Mobile number">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile11" class="col-sm-3 col-form-label">Address (Optional)</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="address" id="exampleInputMobile11"
                                    placeholder="Enter Address">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Password<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="password" class="form-control" name="password" id="exampleInputPassword2"
                                    autocomplete="off" placeholder="Password">
                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Asign Branch <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <select class="js-example-basic-single form-select" id="exampleInputPassword2ss"
                                    name="branch_id" data-width="100%">
                                    <option selected disabled>Select Branch </option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}">{{ $branches->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2s" class="col-sm-3 col-form-label">Asign Role <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <select class="js-example-basic-single form-select " id="exampleInputPassword2s"
                                    name="role_id" data-width="100%">
                                    <option selected disabled>----> Select Role <-----< /option>
                                    @foreach ($role as $roles)
                                                @if ($roles->id === 1 || $roles->id === 4)
                                    <option value="{{ $roles->id }}" disabled>{{ $roles->name }}</option>
                                @else
                                    <option value="{{ $roles->id }}">{{ $roles->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="sale_commission" style="display:none;">
                        <hr>
                            <!-- Commission Type -->
                            <div class="row mb-3">
                                <label for="commission_type" class="col-sm-3 col-form-label">Commission Type</label>
                                <div class="col-sm-9">
                                    <select class="form-select rounded-pill" id="commission_type" name="commission_type">
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Commission State -->
                            <div class="row mb-3">
                                <label for="commission_state" class="col-sm-3 col-form-label">Commission State</label>
                                <div class="col-sm-9">
                                    <select class="form-select rounded-pill" name="commission_state" id="commission_state">
                                        <option value="">Select Type Of Commission State</option>
                                        <option value="against_sale_amount">Sale Wise Commission</option>
                                        <option value="against_profit_amount">Profit Wise Commission</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Commission Rate -->
                            <div class="row mb-3">
                                <label for="commission_rate" class="col-sm-3 col-form-label">Commission Rate</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control rounded-pill" id="commission_rate" name="commission_rate">
                                </div>
                            </div>
                        </div>








                        <button type="submit" class="btn btn-primary me-2">Submit</button>

                        @if($settings->sale_commission == 1 && ($userIdentity=="admin"||$userIdentity=="superadmin" ||$userIdentity=="tecadmin"))
                        <a class="btn btn-primary me-2 assign">Assign Commission to User</a>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

$(document).on('click', '.assign', function () {
    $(".sale_commission").fadeToggle();
});





        $(document).ready(function() {

            $('#myValidForms').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    password: {
                        required: true,
                    },
                    branch_id: {
                        required: true,
                    },
                    role_id: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Name',
                    },
                    email: {
                        required: 'Enter Email Address',
                    },
                    password: {
                        required: 'Enter Strong Password',
                    },
                    branch_id: {
                        required: 'Select Branch',
                    },
                    role_id: {
                        required: 'Select Role Name',
                    },

                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-valid-groupss').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                },
            });

       /////////////
            $('#unselectButton').on('click', function () {
            $('#employeeSelect').val('').trigger('change'); // Clear dropdown
            clearForm(); // Clear all related fields
        });

        // Clear form fields function
        function clearForm() {
            $('input[name="name"]').val('');
            $('input[name="email"]').val('');
            $('input[name="phone"]').val('');
            $('input[name="address"]').val('');
            $('input[name="password"]').val('');
        }
        $('#employeeSelect').on('change', function () {
            const employeeId = $(this).val();

            if (employeeId) {
                $.ajax({
                    url: `/get-employee-data/${employeeId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            // Populate form fields with employee data
                            $('input[name="name"]').val(data.employee.name || '');
                            $('input[name="email"]').val(data.employee.email || '');
                            $('input[name="phone"]').val(data.employee.phone || '');
                            $('input[name="address"]').val(data.employee.address || '');
                        } else {
                            alert('Employee data not found.');
                            clearForm();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching employee data:', error);
                        clearForm();
                    }
                });
            } else {
                clearForm();
            }
        });


        });
    </script>
@endsection
