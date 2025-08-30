@extends('master')
@section('title', '| Prodile Edit ')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">User Edit Form</h6>

                    <form class="forms-sample" id="myValidForms" method="post" action="{{route('user.profile.update')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3 ">
                            <label for="exampleInput1Username2" class="col-sm-3 col-form-label">Name <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="text" name="name" value="{{$user->name}}" class="form-control" id="exampleInput1Username2"
                                    placeholder="Name">
                            </div>
                        </div>
                        <div class="row mb-3 form-valid-groups">
                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="email" name="email" value="{{$user->email ?? ''}}" class="form-control" id="exampleInputEmail2" readonly
                                    autocomplete="off" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile (Optional)</label>
                            <div class="col-sm-9">
                                <input type="number" value="{{$user->phone ?? ''}}" name="phone" class="form-control" id="exampleInputMobile"
                                    placeholder="Mobile number">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile11" class="col-sm-3 col-form-label">Address (Optional)</label>
                            <div class="col-sm-9 ">
                                <textarea name="address" id="" class="form-control" placeholder="Enter Address" cols="30" rows="5">{{$user->address ?? ''}} </textarea>

                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Branch Name<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input class="form-control" value="{{$user['branch']['name']}}" readonly> </input>
                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Profile Image<span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="file" class="categoryImage" data-default-file="{{ $user->photo ? asset('uploads/profile/' . $user->photo) : asset('assets/images/default-user.svg') }}"  name="image" id="myDropify" />
                            </div>
                        </div>
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
        });
    </script>
@endsection
