@extends('master')
@section('title', '| Add Permission')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('all.permission') }}" class="btn btn-info">View Permission List</a>
                </h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Add Permission</h6>
                    <form id="myValidForm" action="{{ route('store.permission') }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Permission Name<span class="text-danger">*</span></label>
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }} "placeholder="Enter Permission Name" autocomplete="off">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label for="ageSelect" class="form-label">Group Name <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="js-example-basic-single form-select @error('group_name') is-invalid @enderror"
                                        name="group_name" data-width="100%">
                                        <option selected disabled>Select Group Name</option>
                                        <option value="dashboard">Dashboard</option>
                                        <option value="pos">Pos</option>
                                        <option value="pos-manage">Pos Manage</option>
                                        <option value="products">Products</option>
                                        <option value="category">Category</option>
                                        <option value="sub-category">Sub Category</option>
                                        <option value="brand">Brand</option>
                                        <option value="unit">Unit</option>
                                        <option value="product-size">Product Size</option>
                                        <option value="taxes">Taxes</option>
                                        <option value="supplier">Supplier</option>
                                        <option value="purchase">Purchase</option>
                                        <option value="promotion">Promotion</option>
                                        <option value="promotion-details">Promotion Details</option>
                                        <option value="damage">Damage</option>
                                        <option value="bank">Bank</option>
                                        <option value="expense">Expense</option>
                                        <option value="transaction">Transaction</option>
                                        <option value="customer">Customer</option>
                                        <option value="employee">Employee</option>
                                        <option value="employee-salary">Employee Salary</option>
                                        <option value="advanced-employee-salary">Advanced Employee Salary</option>
                                        <option value="crm">CRM</option>
                                        <option value="report">Report</option>
                                        <option value="sales-report">Sales Report</option>
                                        <option value="purchase-report">Purchase Report</option>
                                        <option value="role-and-permissions">Role & Permissions</option>
                                        <option value="admin-manage">Admin Manage</option>
                                        <option value="settings">Settings</option>
                                        <option value="branch">Branch</option>
                                        <option value="return">Return</option>
                                        <option value="limit">Limitation</option>
                                        <option value="inventory">Inventory</option>
                                        <option value="warehouse">Warehouse</option>
                                        <option value="pos-setting">Pos Setting</option>
                                        <option value="other">Others</option>
                                    </select>
                                    @error('group_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div><!-- Row -->
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Add permission">
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
                    name: {
                        required: true,
                    },
                    group_name: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Permission Name',
                    },
                    group_name: {
                        required: 'Please Select Group Name',
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
