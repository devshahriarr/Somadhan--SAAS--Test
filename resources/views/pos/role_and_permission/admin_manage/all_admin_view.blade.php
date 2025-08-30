@extends('master')
@section('title', '| All Admin List')
@section('admin')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('admin.add') }}" class="btn btn-info">Add Admin</a></h4>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Admin List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Admin Name</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Branch</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($user->count() > 0)
                                    @foreach ($user as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            @if($data->employee_id === null)
                                            <td>{{ $data->name ?? 'N/A' }}</td>
                                            @else
                                            <td><a href="{{url('/employe/profile/'.$data->employee_id)}}">{{ $data->name ?? '-' }}</a> </td>
                                            @endif
                                            <td>
                                            @foreach ($data->roles as $role)
                                                <span class="badge rounded-pill bg-danger">{{ $role->name }}</span>
                                            @endforeach
                                            </td>
                                            <td>{{ $data->phone ?? '-' }}</td>
                                            <td>{{ $data->email ?? '-' }}</td>
                                            <td>{{ $data->address ?? '-' }}</td>
                                            <td>{{ $data->branch_id ?? '-' }}</td>
                                            <td>
                                                @if (Auth::user()->can('admin-manage.edit'))
                                                @foreach ($data->roles as $role)
                                                @if ($role->id !== 1 && $role->id !== 4)
                                                    <a href="{{ route('admin.manage.edit', $data->id) }}"
                                                        class="btn btn-sm btn-primary btn-icon" title="Edit">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    @endif
                                                @endforeach
                                                @endif
                                                @if (Auth::user()->can('admin-manage.delete'))
                                                @foreach ($data->roles as $role)
                                                @if ($role->id !== 1 && $role->id !== 4)

                                                    <a href="{{ route('admin.manage.delete', $data->id) }}" id="delete" class="btn btn-sm btn-danger btn-icon" title="Delete">
                                                        <i data-feather="trash-2"></i>
                                                    </a>
                                                @endif
                                                @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{ route('add.role') }}" class="btn btn-primary">Add role<i
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
