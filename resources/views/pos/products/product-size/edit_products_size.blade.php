@extends('master')
@section('title','| Edit Product Size')
@section('admin')

<div class="row">
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('product.size.view')}}" class="btn btn-info">View All P. Size</a></h4>
    </div>
</div>
<div class="col-md-12 grid-margin stretch-card">

<div class="card">
<div class="card-body">

    <h6 class="card-title text-info">Edit Product Size</h6>
    <form class="forms-sample" id="myValidForm" action="{{route('product.size.update',$productSize->id)}}" method="POST">
        @csrf

        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Select Category</label>
            <div class="col-sm-9 form-valid-groups">
            <select class="form-control" name="category_id">
                <option slectetd value="">Select Category</option>
                @foreach($allCategory as $category)
                <option value="{{$category->id}}" {{$category->id == $productSize->category_id ? 'selected' : ''}} >{{$category->name}}</option>
                @endforeach
              </select>

            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Product Size</label>
            <div class="col-sm-9 form-valid-groups">
                <input type="text" name="size" value="{{$productSize->size}}" class="form-control" id="exampleInputBranchname2" placeholder="Please Enter Size">

            </div>
        </div>

        <div class="row mb-3">
            <label for="exampleInputPassword2" class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
            <button type="submit" class="btn btn-primary me-2">Update</button>
            </div>
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
                category_id: {
                    required : true,
                },
                size: {
                    required : true,
                },
            },
            messages :{
                category_id: {
                    required : 'Please Select The Category',
                },
                size: {
                    required : 'Please Enter Size ',
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

</script>
@endsection
