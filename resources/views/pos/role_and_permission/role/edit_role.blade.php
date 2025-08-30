@extends('master')
@section('title','| Edit Role')
@section('admin')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('all.role') }}" class="btn btn-info">View Role List</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Role</h6>
                    <form id="myValidForm" action="{{ route('role.update') }}" method="post">
                        <input type="hidden" name="role_id" value="{{$roles->id}}">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Role Name<span class="text-danger">*</span></label>
                                    </label>
                                    <input type="text"  name="name" value="{{$roles->name}}" class="form-control" placeholder="Enter role Name"  autocomplete="off"">
                                </div>
                            </div>
                        </div><!-- Row -->
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Update Role">
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

                },
                messages: {
                    name: {
                        required: 'Please Enter Role Name',
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
