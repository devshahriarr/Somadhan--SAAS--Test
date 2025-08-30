@extends('master')
@section('title','| Add Branch')
@section('admin')

<div class="row">
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('branch.view')}}" class="btn btn-info">View All Branch</a></h4>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

    <h6 class="card-title text-info">Add Bracnch</h6>

    <form class="forms-sample" action="{{route('branch.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control  @error('name') is-invalid  @enderror" id="exampleInputBranchname2" value="{{old('name')}}" placeholder="Enter Branch Name">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
                <input type="email" name="email" class="form-control  @error('email') is-invalid  @enderror" id="exampleInputBranchname2" value="{{old('email')}}" placeholder="Enter Email">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Mobile</label>
            <div class="col-sm-9">
                <input type="text" class="form-control @error('phone') is-invalid  @enderror" name="phone" id="exampleInputMobile" value="{{old('phone')}}"  placeholder="Mobile number">
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Address</label>
            <div class="col-sm-9">
            <textarea class="form-control  @error('address') is-invalid  @enderror" name="address" id="exampleFormControlTextarea1" placeholder="Enter Branch Address" rows="5">{{old('address')}}</textarea>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        </div>

        <div class="row mb-3">
            <label for="exampleInputPassword2" class="col-sm-3 col-form-label">Branch Logo</label>
            <div class="col-sm-9">
            <div class="mb-3">
                            <div class="card">
                                <div class="card-body">

                                    <input type="file"  class="categoryImage  @error('logo') is-invalid  @enderror" value="{{old('logo')}}"  name="logo" id="myDropify" />
                                    @error('logo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                 </div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputPassword2" class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            </div>
        </div>


    </form>

</div>
</div>
</div>
</div>

@endsection
