@extends('master')
@section('title','| Employee Salary List')
@section('admin')
<div class="row">
    @if(Auth::user()->can('employee-salary.add'))
<div class="col-md-12 grid-margin stretch-card d-flex justify-content-end">
    <div class="">
        <h4 class="text-right"><a href="{{route('employee.salary.add')}}" class="btn btn-info">Add New Salary </a></h4>
    </div>
</div>
@endif

<table class="table table-bordered table-striped summary-print text-center  mb-4">
    <thead class="text-dark">
        <tr>
            <th>Total Employee </th>
            <th>Total Salary </th>
            <th>Total Paid Salary </th>
            <th>Total Due Salary</th>

        </tr>
    </thead>
    <tbody>

        <tr class="fw-bold">
            <td>{{$employee->count() ?? 0}}</td>
            <td>{{$employeSalary->sum(('creadit')) ?? 0}}</td>
            <td>{{$employeSalary->sum(('debit')) ?? 0}}</td>
            <td>{{$employeSalary->sum(('balance')) ?? 0}}</td>
        </tr>
    </tbody>
</table>
<div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h6 class="card-title text-info">View Salary History</h6>

                    <div id="" class="table-responsive">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Employee Name</th>
                                    <th>Branch</th>
                                    <th>Date</th>
                                    <th>Submited Salary</th>
                                    <th>Due Salary</th>
                                    <th>Submit Date</th>
                                    <th>Update Date</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="showData">
                            @if ($employeSalary->count() > 0)
                            @foreach ($employeSalary as $key => $employeSalarys)
                                <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{url('/employe/profile/'.$employeSalarys['emplyee']['id'])}}">{{ $employeSalarys['emplyee']['full_name'] ?? ''}}</a></td>
                                <td>{{ $employeSalarys['branch']['name'] ?? ''}}</td>
                                <td>{{ \Carbon\Carbon::parse($employeSalarys->date)->format('d F Y')   ?? ''}}</td>
                                <td>{{ $employeSalarys->debit ?? ''}}</td>
                                <td>{{ $employeSalarys->creadit - $employeSalarys->debit ?? ''}}</td>
                                <td>{{$employeSalarys->created_at->format('d F Y') ?? ''}}</td>
                                <td>{{ $employeSalarys->updated_at ? $employeSalarys->updated_at->format('d F Y') : '-' }}</td>
                                <td>
                                    @php
                                    $note = $employeSalarys->note;
                                    $noteChunks = str_split($note, 40);
                                    echo implode("<br>", $noteChunks);
                                    @endphp</td>
                                <td>
                                    @if(Auth::user()->can('employee-salary.edit'))
                                    <a href="{{route('employee.salary.edit',$employeSalarys->id)}}" class="btn btn-sm btn-primary btn-icon" title="Edit Data">
                                        <i data-feather="edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->can('employee-salary.delete'))
                                    <a href="{{route('employee.salary.delete',$employeSalarys->id)}}" id="delete" class="btn btn-sm btn-danger btn-icon">
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
                                    <a href="{{route('employee.salary.add')}}" class="btn btn-primary">Add New Sallary<i
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
