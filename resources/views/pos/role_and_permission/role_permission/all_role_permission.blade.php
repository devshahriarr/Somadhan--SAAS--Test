@extends('master')
@section('title','| Role Permission List')
@section('admin')

    <div class="row">

        <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
            <div class="">
                <h4 class="text-right"><a href="{{ route('add.role') }}" class="btn btn-info">Add Role</a></h4>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Role Permission List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Role Name</th>
                                    <th>Permission Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($role->count() > 0)
                                    @foreach ($role as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->name ?? '' }}</td>
                                        <td class="permissions-container">
                                            {{-- @foreach(array_chunk($data->permissions->toArray(), 12) as $chunk) --}}
                                            <div class="chunk">
                                            @foreach($data->permissions as $permission)
                                                <span class="badge rounded-pill bg-danger"> {{ $permission['name'] ??  '' }}</span>
                                            @endforeach
                                        </div>
                                                {{-- @endforeach --}}
                                        </td>
                                        <td>
                                @if(Auth::user()->can('role-and-permission-check-role-permission.edit'))
                                  @if ( $data->id == 4 )
                                    {{-- //This is Edit  --}}
                                    @if(Auth::user()->id == 4 )
                                    <a href="{{route('admin.role.edit',$data->id)}}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif

                                    @else
                                    <a href="{{route('admin.role.edit',$data->id)}}" class="btn btn-sm btn-primary btn-icon" title="Edit">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @endif
                                    @if(Auth::user()->can('role-and-permission-check-role-permission.delete'))

                                    @if ($data->id == 1 || $data->id == 4)
                                    @else
                                    <a href="{{route('admin.role.delete',$data->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon" title="Delete">
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
    <style>
        .permissions-container {
            display: flex;
            flex-wrap: wrap;
        }

        .chunk {
            width: 100%; /* or specify another width if needed */
            display: flex;
            flex-wrap: wrap;
        }

        .chunk .badge {
            margin: 3px; /* add margin between badges for better spacing */
        }
    </style>
@endsection
