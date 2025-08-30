@extends('master')
@section('title', '| Password Change ')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Change Password</h6>
                    @if(count($errors))
                    @foreach ($errors->all() as $error)
                    <p class="alert alert-danger alert-dismissible fade show"> {{ $error}} </p>
                    @endforeach
                      @endif
                    <form class="forms-sample" method="post" action="{{route('user.update.password')}}">
                        @csrf
                        <div class="row mb-3 ">
                            <label for="example-text-input" class="col-sm-3 col-form-label">Old Password </label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="password" name="oldpassword" class="form-control" id="oldpassword"
                                    placeholder="Old Password">
                            </div>
                        </div>
                        <div class="row mb-3 form-valid-groups">
                            <label for="example-text-input" class="col-sm-3 col-form-label">New Password</label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="password" name="newpassword" class="form-control" id="newpassword"
                                    placeholder="New Passwod">
                            </div>
                        </div>
                        <div class="row mb-3 form-valid-groups">
                            <label for="example-text-input" class="col-sm-3 col-form-label">Confirm  Password</label>
                            <div class="col-sm-9 form-valid-groupss">
                                <input type="password" name="confirm_password"  class="form-control" id="confirm_password"
                                     placeholder="Confirm Password">
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary me-2">Change Password</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
