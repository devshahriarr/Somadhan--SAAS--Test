@extends('master')
@section('title','| Permission List')
@section('admin')

    <div class="row">
        @if(Auth::user()->can('role-and-permission.all-permission.add'))
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('add.permission') }}" class="btn btn-info">Add Permission</a></h4>
            </div>
        </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Permission List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Group Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($permission->count() > 0)
                                    @foreach ($permission as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name ?? '' }}</td>
                                            <td>{{ $data->group_name ?? '' }}</td>
                                            <td>
                                    @if(Auth::user()->can('role-and-permission.all-permission.edit'))
                                    <a href="{{route('permission.edit',$data->id)}}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->can('role-and-permission.all-permission.delete'))
                                    <a href="{{route('permission.delete',$data->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon" title="Delete">
                                        <i data-feather="trash-2"></i>
                                    </a>
                                    @endif
                                </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{route('add.permission')}}" class="btn btn-primary">Add Permission<i
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
