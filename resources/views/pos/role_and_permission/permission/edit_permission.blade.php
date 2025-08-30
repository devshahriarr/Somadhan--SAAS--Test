@extends('master')
@section('title','| Edit Permission')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('all.permission') }}" class="btn btn-info">View Permission List</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Permission</h6>
                    <form id="myValidForm" action="{{ route('permission.update') }}" method="post">
                        <input type="hidden" name="permission_id" value="{{$permissions->id}}">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Permission Name<span class="text-danger">*</span></label>
                                    </label>
                                    <input type="text" value="{{$permissions->name}}"  name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Permission Name"  autocomplete="off">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                            <div class="mb-3 form-valid-groups">
                                <label for="ageSelect" class="form-label">Group Name <span class="text-danger">*</span></label>
                                <select class="js-example-basic-single form-select @error('group_name') is-invalid @enderror" name="group_name"
                                    data-width="100%"  >
                                        <option selected disabled>Select Group Name</option>
                                        <option value="dashboard" {{ $permissions->group_name == 'dashboard' ? 'selected': ''}}>Dashboard</option>
                                        <option value="sale" {{ $permissions->group_name == 'sale' ? 'selected': ''}}>Sale</option>
                                        <option value="products" {{ $permissions->group_name == 'products' ? 'selected': ''}}>Products</option>
                                        <option value="category" {{ $permissions->group_name == 'category' ? 'selected': ''}}>Category</option>
                                        <option value="sub-category" {{ $permissions->group_name == 'sub-category' ? 'selected': ''}}>Sub Category</option>
                                        <option value="brand" {{ $permissions->group_name == 'brand' ? 'selected': ''}}>Brand</option>
                                        <option value="unit" {{ $permissions->group_name == 'unit' ? 'selected': ''}}>Unit</option>
                                        <option value="product-size" {{ $permissions->group_name == 'product-size' ? 'selected': ''}}>Product Size</option>
                                        <option value="taxes" {{ $permissions->group_name == 'taxes' ? 'selected': ''}}>Taxes</option>
                                        <option value="supplier" {{ $permissions->group_name == 'supplier' ? 'selected': ''}}>Supplier</option>
                                        <option value="purchase" {{ $permissions->group_name == 'purchase' ? 'selected': ''}}>Purchase</option>
                                        <option value="promotion" {{ $permissions->group_name == 'promotion' ? 'selected': ''}}>Promotion</option>
                                        <option value="promotion-details" {{ $permissions->group_name == 'promotion-details' ? 'selected': ''}}>Promotion Details</option>
                                        <option value="damage" {{ $permissions->group_name == 'damage' ? 'selected': ''}}>Damage</option>
                                        <option value="bank" {{ $permissions->group_name == 'bank' ? 'selected': ''}}>Bank</option>
                                        <option value="expense" {{ $permissions->group_name == 'expense' ? 'selected': ''}}>Expense</option>
                                        <option value="transaction" {{ $permissions->group_name == 'transaction' ? 'selected': ''}}>Transaction</option>
                                        <option value="customer" {{ $permissions->group_name == 'customer' ? 'selected': ''}}>Customer</option>
                                        <option value="employee"{{ $permissions->group_name == 'employee' ? 'selected': ''}}>Employee</option>
                                        <option value="employee-salary" {{ $permissions->group_name == 'employee-salary' ? 'selected': ''}}>Employee Salary</option>
                                        <option value="advanced-employee-salary" {{ $permissions->group_name == 'advanced-employee-salary' ? 'selected': ''}}>Advanced Employee Salary</option>
                                        <option value="crm" {{ $permissions->group_name == 'crm' ? 'selected': ''}}>CRM</option>
                                        <option value="report" {{ $permissions->group_name == 'report' ? 'selected': ''}}>Report</option>
                                        <option value="sales-report" {{ $permissions->group_name == 'sales-report' ? 'selected': ''}}>Sales Report</option>
                                        <option value="purchase-report"  {{ $permissions->group_name == 'purchase-report' ? 'selected': ''}}>Purchase Report</option>
                                        <option value="settings" {{ $permissions->group_name == 'settings' ? 'selected': ''}}>Settings</option>
                                        <option value="branch" {{ $permissions->group_name == 'branch' ? 'selected': ''}}>Branch</option>
                                        <option value="limit" {{ $permissions->group_name == 'limit' ? 'selected': ''}}>Limitation</option>
                                        <option value="return" {{ $permissions->group_name == 'return' ? 'selected': ''}}>Return</option>
                                        <option value="inventory" {{ $permissions->group_name == 'inventory' ? 'selected': ''}}>Inventory</option>
                                        <option value="pos-setting" {{ $permissions->group_name == 'pos-setting' ? 'selected': ''}}>Pos Setting</option>
                                        <option value="other" {{ $permissions->group_name == 'other' ? 'selected': ''}}>Others</option>
                                    </select>
                                @error('group_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                        </div>

                        </div><!-- Row -->
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Update permission">
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
