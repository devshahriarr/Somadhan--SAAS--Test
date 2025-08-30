@extends('master')
@section('title', '| Prodile  ')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">User Profile</h6>
                    <form class="forms-sample" id="myValidForms" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3 ">
                            <label for="exampleInput1Username2" class="col-sm-3 col-form-label">Name </label>
                            <div class="col-sm-9 form-valid-groupss">
                                <label  id="exampleInput1Username2"
                                    > {{$user->name ?? '-'}}</label>
                            </div>
                        </div>
                        <div class="row mb-3 form-valid-groups">
                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email </label>
                            <div class="col-sm-9 form-valid-groupss">

                                    <label  id="exampleInput1Username2"
                                    > {{$user->email ?? '-'}}</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile Number</label>
                            <div class="col-sm-9">
                                    <label  id="exampleInput1Username2"
                                    > {{$user->phone ?? '-'}}</label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputMobile11" class="col-sm-3 col-form-label">Address </label>
                            <div class="col-sm-9 ">
                                <label  id="exampleInput1Username2"
                                    > {{$user->address ?? '-'}}</label>
                            </div>
                        </div>

                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Branch Name</label>
                            <div class="col-sm-9 form-valid-groupss">
                                <label  id="exampleInput1Username2"
                                > {{$user['branch']['name']}}</label>
                            </div>
                        </div>
                        <div class="row mb-3 ">
                            <label for="exampleInputPassword2ss" class="col-sm-3 col-form-label">Profile Image</label>
                            <div class="col-sm-9 form-valid-groupss">
                                <img  src="{{ $user->photo ? asset('uploads/profile/' . $user->photo) : asset('assets/images/default-user.svg') }}" alt="" height="120px" width="120px">
                            </div>
                        </div>
                        <a href="{{route('user.profile.edit')}}" class="btn btn-primary me-2">Edit</a>
                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection
