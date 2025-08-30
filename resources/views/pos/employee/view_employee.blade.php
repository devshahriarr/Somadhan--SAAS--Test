@extends('master')
@section('title','| Employee List')
@section('admin')

<div class="row">
    @if(Auth::user()->can('employee.add'))
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('employee.add')}}" class="btn btn-info">Add New Employee</a></h4>
    </div>
</div>
@endif
<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title text-info">View Employee List</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>NID Numbaer</th>
                                    <th>Image</th>
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            @if ($employees->count() > 0)
                            @foreach ($employees as $key => $employe)
                                <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $employe->full_name ?? ''}}</td>
                                <td>{{ $employe->email ?? ''}}</td>
                                <td>{{ $employe->phone ?? ''}}</td>
                                <td>{{ $employe->address ?? ''}}</td>
                                <td>{{ $employe->nid ?? ''}}</td>
                                <td>
                                @if($employe->pic)
                                    <img src="{{ asset('uploads/employee/'.$employe->pic) }}" alt="">
                                    @else
                                    <img src="{{ asset('dummy/image.jpg') }}" alt="Dummy Image">
                                    @endif

                                <td>{{ $employe->designation ?? ''}}</td>
                                <td>{{ $employe->salary ?? ''}}</td>

                                <td>
                                    @if(Auth::user()->can('employee.edit'))
                                    <a href="{{route('employee.edit',$employe->id)}}" class="btn btn-sm btn-primary btn-icon">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->can('employee.delete'))
                                    <a href="{{route('employee.delete',$employe->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon">
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
                                    <a href="{{route('employee.add')}}" class="btn btn-primary">Add Employee<i
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


