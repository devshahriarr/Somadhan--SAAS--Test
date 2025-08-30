@extends('master')
@section('title', '| Edit Employee')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">

                <h4 class="text-right"><a href="{{ route('employee.view') }}" class="btn btn-info">View All Employee</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Employee</h6>
                    <form id="myValidForm" action="{{ route('employee.update', $employees->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" value="{{ $employees->full_name }}"
                                        class="form-control field_required" placeholder="Enter Employee name">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Phone<span class="text-danger">*</span></label>
                                    <input type="text" name="phone" value="{{ $employees->phone }}"
                                        class="form-control" placeholder="Enter Employee Number">
                                </div>
                            </div>
                            <div class="col-sm-6 form-valid-group">
                                <div class="mb-3">
                                    <label class="form-label">Email address<span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ $employees->email }}"
                                        class="form-control" placeholder="Enter Employee email">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Employee Address<span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" placeholder="Write Employee Address" rows="4" cols="50">{{ $employees->address }}</textarea>
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-4 form-valid-group">
                                <div class="mb-3">
                                    <label class="form-label">NID Number</label>
                                    <input type="number" class="form-control" name="nid" value="{{ $employees->nid }}"
                                        placeholder="Enter NID Number">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4 form-valid-group">
                                <div class="mb-3">
                                    <label class="form-label">Designation</label>
                                    <input type="text" name="designation" class="form-control"
                                        value="{{ $employees->designation }}" placeholder="Enter Employee Designation">
                                </div>
                            </div><!-- Col -->
                            <div class="col-sm-4 form-valid-group">
                                <div class="mb-3">
                                    <label class="form-label">Employee Salary</label>
                                    <input type="number" class="form-control" name="salary"
                                        value="{{ $employees->salary }}" placeholder="Enter Employee Salary">
                                </div>
                            </div><!-- Col -->
                        </div><!-- Row -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Employee Image</h6>
                                            <p class="mb-3 text-warning">Note: <span class="fst-italic">Image not
                                                    required. If you
                                                    add
                                                    a image
                                                    please add a 35mm (width) x 45mm (height) size image.</span></p>
                                            <input type="file"
                                                data-default-file="{{ $employees->pic ? asset('uploads/employee/' . $employees->pic) : '' }}"
                                                class="employeeImage" name="image" id="myDropify" />
                                        </div>
                                    </div>
                                </div>
                            </div><!-- Col -->
                            <!-- Col -->
                            <!-- Col -->
                        </div><!-- Row -->
                        <div>
                            <input type="submit" class="btn btn-primary submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myValidForm').validate({
                rules: {
                    full_name: {
                        required: true,
                    },
                    phone: {
                        required: true,
                    },
                    email: {
                        required: true,
                    },
                    address: {
                    required : true,
                    },
                     salary: {
                    required : true,
                 },
                },
                messages: {
                    full_name: {
                        required: 'Please Enter Employee Name',
                    },
                    phone: {
                        required: 'Please Enter Customer Phone Number',
                    },
                    email: {
                        required: 'Please Enter Employee Email',
                    },
                    address: {
                    required : 'Enter Address',
                        },
                    salary: {
                    required : 'Enter Salary Amount',
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
