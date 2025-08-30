@extends('master')
@section('title','| Edit Role-Permission')
@section('admin')
<style>
    .form-check-label{
        text-transform: capitalize;
    }
</style>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('all.role.permission') }}" class="btn btn-info">All Role In Permission List</a></h4>
            </div>
        </div>
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">Edit Role In Permission</h6>
                    <form id="myValidForm" action="{{ route('admin.role.update', $role->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <!-- Col -->
                            <div class="col-sm-12">
                                <div class="mb-3 form-valid-groups">
                                    <label class="form-label">Role Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" readonly value="{{$role->name}}">
                                </div>
                            </div><br>
                            <div class="form-check form-check-inline" style="margin-bottom: 20px;margin-left:10px">

                                <label class="form-check-label" for="checkInlineCheckedAll">
                                   Select All Permission</label>
                                <input type="checkbox" class="form-check-input" id="checkInlineCheckedAll" >
                            </div>

                            <hr>
                            @foreach ($permission_group->unique('group_name') as $group )
                            @php
                            $permissions = App\Models\User::getPermissionByGroupName($group->group_name);
                            @endphp
                            <div class="row">
                                <div class="col-md-3">
                                        {{-- <h5 class="form-label">Group Name</h5><br> --}}
                                    <div class="form-check form-check-inline">

                                        <label class="form-check-label" for="checkInlineChecked{{$group->group_name}}">
                                            {{$group->group_name}} </label>
                                        <input type="checkbox" class="form-check-input" id="checkInlineChecked{{$group->group_name}}" {{App\Models\User::roleHasPermissions($role,$permissions) ? 'checked' : ''}}>
                                    </div>
                            </div>
                            <div class="col-md-9">

                                  
                                    @foreach ($permissions as $permission)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label" for="checkInlineChecked{{$permission->id}}">
                                        {{$permission->name}}
                                    </label>
                                    <input type="checkbox" name="permission[]" {{$role->hasPermissionTo($permission->name)? 'checked':''}} class="form-check-input" id="checkInlineChecked{{$permission->id}}" value="{{$permission->id}}">
                                </div></br>
                                @endforeach </br>
                            </div>
                        </div><!-- Row -->
                        @endforeach
                        <div>
                            <input type="submit" id="submit_btn" class="btn btn-primary submit" value="Update">
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



    </script>
@endsection
