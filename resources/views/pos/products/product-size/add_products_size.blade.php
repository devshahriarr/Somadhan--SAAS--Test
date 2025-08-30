@extends('master')
@section('title','| Add Product Size')
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

    <h6 class="card-title text-info">Add Product Size</h6>

    <form class="forms-sample" id="myValidForm" action="{{route('product.size.store')}}" method="POST">
        @csrf

        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Select Category</label>
            <div class="col-sm-9 form-valid-groups">
            <select class="form-control" name="category_id">
                <option slectetd value="">Select Category</option>
                @foreach($allCategory as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>

            </div>
        </div>
        <div class="row mb-3">
            <label for="exampleInputBranchname2" class="col-sm-3 col-form-label">Product Size</label>
            <div class="col-sm-9 form-valid-groups">
                <input type="text" name="size" class="form-control" id="exampleInputBranchname2" placeholder="Please Enter Size">
            </div>
        </div>

        <div class="row mb-3">
            <label for="exampleInputPassword2" class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
            <button type="submit" id="submitForm" class="btn btn-primary me-2">Submit</button>
            </div>
        </div>
    </form>

</div>
</div>
</div>
</div>
<!-- /// All Size -->
<!-- <div class="row"> -->

<!-- <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('product.size.add')}}" class="btn btn-info">Add New Product Size</a></h4>
    </div>
</div> -->
<!-- <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title text-info">View P.Size List</h6>

                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Category Name</th>
                                    <th>Product Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            @if ($productSize->count() > 0)
                            @foreach ($productSize as $key => $pSize)
                                <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $pSize['category']['name']}}</td>
                                <td>{{ $pSize->size ?? ''}}</td>


                                <td>
                                    <a href="{{route('product.size.edit',$pSize->id)}}" class="btn btn-sm btn-primary btn-icon">
                                        <i data-feather="edit"></i>
                                    </a>
                                    <a href="{{route('product.size.delete',$pSize->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <div class="text-center text-warning mb-2">Data Not Found</div>
                                <div class="text-center">
                                    <a href="{{route('product.size.add')}}" class="btn btn-primary">Add Product Size<i
                                            data-feather="plus"></i></a>
                                </div>
                            </td>
                        </tr>
                       @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div> -->

<!-- /// -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#submitForm').on('click', function(e) {
            e.preventDefault();

            if ($('#myValidForm').valid()) { // Check if form is valid
                var formData = $('#myValidForm').serialize(); // Serialize form data

                $.ajax({
                    url: $('#myValidForm').attr('action'), // Form action URL
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                         window.location.href = '{{ route("product.size.view") }}';
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error occurred while submitting the form.');
                    }
                });
            }
        });

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
