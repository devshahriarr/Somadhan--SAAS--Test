@extends('master')
@section('title', '| Branch List')
@section('admin')

    <div class="row">
        @if (Auth::user()->can('branch.menu'))
            <div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
                <div class="">
                    @if (Auth::user()->can('branch.add'))
                        <h4 class="text-right"><a href="{{ route('branch') }}" class="btn btn-info">Add New Branch</a></h4>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-info">View Branch List</h6>
                    <div id="" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Branch Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>logo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                                @if ($branches->count() > 0)
                                    @foreach ($branches as $key => $branch)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $branch->name ?? '' }}</td>
                                            <td>{{ $branch->email ?? '' }}</td>
                                            <td>{{ $branch->phone ?? '' }}</td>
                                            <td>{{ $branch->address ?? '' }}</td>
                                            {{-- @dd($branch->logo) --}}
                                            <td>
                                                <img src="{{ is_null($branch->logo) ? asset('dummy/image.jpg') : (file_exists(public_path('uploads/branch/' . $branch->logo)) ? asset('uploads/branch/' . $branch->logo) : asset('dummy/image.jpg')) }}"
                                                    alt="logo" height="60px" width="60px">
                                            </td>
                                            <td>
                                                @if (Auth::user()->can('branch.add'))
                                                    <a href="{{ route('branch.edit', $branch->id) }}"
                                                        class="btn btn-sm btn-primary btn-icon">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                @endif
                                                @if (Auth::user()->can('branch.edit'))
                                                    <a href="{{ route('branch.delete', $branch->id) }}" id="delete"
                                                        class="btn btn-sm btn-danger btn-icon">
                                                        <i data-feather="trash-2"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">
                                            <div class="text-center text-warning mb-2">Data Not Found</div>
                                            <div class="text-center">
                                                <a href="{{ route('branch') }}" class="btn btn-primary">Add Branch<i
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
