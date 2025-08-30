@extends('master')
@section('title', '| Edit Admin')
@section('admin')

@php
    $settings = App\Models\PosSetting::all()->first();
    $userIdentityAffliate = App\Models\Affiliator::where('user_id',$user->id)->first();
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

                    <h6 class="card-title">Edit Admin Form</h6>

                    <form class="forms-sample" id="myValidForms" method="post"
                        action="{{ route('update.admin.manage', $user->id) }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="exampleInput1Username2" class="col-sm-3 col-form-label">Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                                    id="exampleInput1Username2" placeholder="Name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control"
                                    id="exampleInputEmail2" autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile (Optional)</label>
                            <div class="col-sm-9">
                                <input type="number" name="phone" value="{{ $user->phone }}" class="form-control"
                                    id="exampleInputMobile" placeholder="Mobile number">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile11" class="col-sm-3 col-form-label">Address(Optional)</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $user->address }}" name="address"
                                    id="exampleInputMobile11" placeholder="Enter Address">
                            </div>
                        </div>
                    <div class="row mb-3">
                        <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Asign Branch <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9 form-valid-groupss">
                            <select class="js-example-basic-single form-select" id="exampleInputPassword2ss"
                                name="branch_id" data-width="100%">
                                <option selected disabled>Select Branch </option>
                                @foreach ($branch as $branches)
                                    <option value="{{ $branches->id }}"
                                        {{ $branches->id == $user->branch_id ? 'selected' : '' }}>{{ $branches->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="exampleInputPassword2s" class="col-sm-3 col-form-label">Asign Role <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-9 form-valid-groupss">
                            <select class="js-example-basic-single form-select" id="exampleInputPassword2s" name="role_id"
                                data-width="100%">
                                <option selected disabled>----> Select Role <-----</option>
                                    @foreach ($role as $roles)
                                    @if ($roles->id === 1 || $roles->id === 4)
                                    <option value="{{ $roles->id }}" disabled>{{ $roles->name }}</option>
                                    @else
                                    <option value="{{ $roles->id }}"
                                        {{ $user->hasRole($roles->name) ? 'selected' : '' }}>{{ $roles->name }}
                                    </option>
                                    @endif
                                  @endforeach
                            </select>
                        </div>
                    </div>





             

                    @if($settings && $settings->sale_commission == 1)

                    <div class="sale_commission">
                        <hr>

                        <!-- Commission Type -->
                        <div class="row mb-3">
                            <label for="commission_type" class="col-sm-3 col-form-label">Commission Type</label>
                            <div class="col-sm-9">
                                <select class="form-select rounded-pill" id="commission_type" name="commission_type">
                                    <option {{ optional($userIdentityAffliate)->commission_type == "fixed" ? 'selected' : '' }} value="fixed">Fixed</option>
                                    <option {{ optional($userIdentityAffliate)->commission_type == "percentage" ? 'selected' : '' }} value="percentage">Percentage</option>
                                </select>
                            </div>
                        </div>

                        <!-- Commission State -->
                        <div class="row mb-3">
                            <label for="commission_state" class="col-sm-3 col-form-label">Commission State</label>
                            <div class="col-sm-9">
                                <select class="form-select rounded-pill" name="commission_state" id="commission_state">
                                    <option value="">Select Type Of Commission State</option>
                                    <option {{ optional($userIdentityAffliate)->commission_state == "against_sale_amount" ? 'selected' : '' }} value="against_sale_amount">Sale Wise Commission</option>
                                    <option {{ optional($userIdentityAffliate)->commission_state == "against_profit_amount" ? 'selected' : '' }} value="against_profit_amount">Profit Wise Commission</option>
                                </select>
                            </div>
                        </div>

                        <!-- Commission Rate -->
                        <div class="row mb-3">
                            <label for="commission_rate" class="col-sm-3 col-form-label">Commission Rate</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control rounded-pill" id="commission_rate" name="commission_rate" value="{{ optional($userIdentityAffliate)->commission_rate ?? '' }}">
                            </div>
                        </div>
                    </div>
                @endif







                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#myValidForms').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
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
        });

@endsection
