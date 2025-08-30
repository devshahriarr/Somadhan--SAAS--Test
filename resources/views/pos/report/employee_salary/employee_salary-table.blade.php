
<div class="row">
    <div class="col-md-12 ">
        <div id="" class="table-responsive">
            <table id="example" class="table w-100 ">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Employee Name</th>
                        <th>Branch Name</th>
                        <th>Date</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                        <th>Submit Date</th>
                        <th>Update Date</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody class="showData">
                @if ($employeeSalary->count() > 0)
                @foreach ($employeeSalary as $key => $employeeData)
                    <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $employeeData['emplyee']['full_name'] ?? ''}}</td>
                    <td>{{ $employeeData['branch']['name'] ?? ''}}</td>
                    <td>{{ $employeeData->date ?? '-'}} </td>
                    <td>{{ $employeeData->debit ?? '0'}} TK</td>
                    <td>{{ $employeeData->creadit ?? '0'}}TK</td>
                    <td>{{ $employeeData->balance ?? '0'}}TK</td>
                    <td>{{$employeeData->created_at->format('d F Y') ?? ''}}</td>
                    <td>{{ $employeeData->updated_at ? $employeeData->updated_at->format('d F Y') : '-' }}</td>

                    <td>{{ $employeeData->note ?? '-'}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12">
                    <div class="text-center text-warning mb-2">Data Not Found</div>

                </td>
            </tr>
            @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
