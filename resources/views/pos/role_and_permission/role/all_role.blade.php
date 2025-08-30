@extends('master')
@section('title','| Role List')
@section('admin')

    <div class="row">
    @if(Auth::user()->can('role-and-permission.all-role.add'))
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('add.role') }}" class="btn btn-info">Add Role</a></h4>
            </div>
        </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Role List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($role->count() > 0)
                                    @foreach ($role as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name ?? '' }}</td>
                                            <td>
                                    @if(Auth::user()->can('role-and-permission.all-role.edit'))
                                    @if($data->id !== 1 && $data->id !== 4)
                                    <a href="{{route('role.edit',$data->id)}}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @endif
                                    @if(Auth::user()->can('role-and-permission.all-role.delete'))
                                    @if($data->id !== 1 && $data->id !== 4)
                                    <a href="{{route('role.delete',$data->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                    @endif
                                    @endif
                                </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{route('add.role')}}" class="btn btn-primary">Add role<i
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
    </div>
@endsection
