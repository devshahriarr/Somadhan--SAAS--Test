@extends('master')
@section('title','| Add Role-Permission')
@section('admin')
<style>
    .form-check-label{
        text-transform: capitalize;
    }
</style>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('add.role') }}" class="btn btn-info">Add Role</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Add Role In Permission</h6>
                    <form id="myValidForm" action="{{ route('role.permission.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Role Name<span class="text-danger">*</span></label>
                                    </label>
                                    <select class="js-example-basic-single form-select" name="role_id"
                                    data-width="100%"  >
                                        <option selected disabled>Select Role Name</option>
                                        @foreach ($role as $roles)
                                        @if ($roles->id === 1 || $roles->id === 4)
                                        <option value="{{ $roles->id }}" disabled>{{ $roles->name }}</option>
                                        @else
                                            <option value="{{ $roles->id }}">{{ $roles->name }}</option>
                                        @endif
                                        @endforeach
                                     </select>
                                </div>
                            </div><br>
                            <div class="form-check form-check-inline" style="margin-bottom: 20px;margin-left:10px">
                                <label class="form-check-label" for="checkInlineCheckedAll">
                                   Select All Permission</label>
                                <input type="checkbox" class="form-check-input" id="checkInlineCheckedAll" >
                            </div>

                            <hr>
                            @foreach ($permission_group->unique('group_name') as $group )
                            <div class="row">
                                <div class="col-md-3 form-valid-groups">
                                        {{-- <h5 class="form-label">Group Name</h5><br> --}}
                                    <div class="form-check form-check-inline">


                                        <label class="form-check-label" for="checkInlineChecked{{$group->group_name}}">
                                            {{$group->group_name}} </label>
                                        <input type="checkbox" class="form-check-input" id="checkInlineChecked{{$group->group_name}}" >
                                    </div>
                            </div>
                            <div class="col-md-9 form-valid-groups">
                                @php
                                    $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                                @endphp
                                    {{-- <h5 class="form-label">Permission Name </h5><br> --}}
                                    @foreach ($permissions as $permission)
                                <div class="form-check form-check-inline form-valid-groups">
                                    <label class="form-check-label" for="checkInlineChecked{{$permission->id}}">
                                        {{$permission->name}}
                                    </label>
                                    <input type="checkbox" name="permission[]" class="form-check-input" id="checkInlineChecked{{$permission->id}}" value="{{$permission->id}}">
                                </div></br>
                                @endforeach </br>
                            </div>
                        </div><!-- Row -->
                        @endforeach
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#checkInlineCheckedAll').click(function(){
		if ($(this).is(':checked')) {
			$('input[type = checkbox]').prop('checked',true);
		}else{
			$('input[type = checkbox]').prop('checked',false);
		}
	    });

        });
        $(document).ready(function() {
        $('#myValidForm').validate({
    rules: {
        role_id: {
            required: true,
        },
        permission: {
            required: true,
        },

    },
    messages: {
        role_id: {
            required: 'Please Select Role Name',
        },
        permission: {
            required: 'Please Checked Persmission Name',
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


<!-- //////////////Validation baki////////////// -->
