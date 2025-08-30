<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title text-info">Expense </h6>
            <div id="tableContainer" class="table-responsive">
                <table id="example" class="table">
                    <thead class="action">
                        <tr>
                            <th>SN</th>
                            <th>purpose</th>
                            <th>Amount</th>
                            <th>Spender</th>
                            <th>receipt Image</th>
                            <th>Bank Account</th>
                            <th>Expense Category</th>
                            <th>Expense Date</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="showData">
                        @if ($expense->count() > 0)
                            @foreach ($expense as $key => $expenses)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $expenses->purpose ?? '' }}</td>
                                    <td>{{ $expenses->amount ?? '' }}</td>
                                    <td>{{ $expenses->spender ?? '' }}</td>
                                    <td>
                                     <img src="{{ $expenses->image ? asset('uploads/expense/' . $expenses->image) : asset('dummy/image.jpg') }}"
                                            alt="Receipt image">
                                    </td>
                                    <td>{{ $expenses['bank']['name'] ?? '-' }}</td>
                                    <td>{{ $expenses['expenseCat']['name'] ?? '-' }}</td>
                                    <td>{{ $expenses->expense_date ?? '' }}</td>
                                    <td>{{ $expenses->note ?? '-' }}</td>

                                    <td>

                                        @if(Auth::user()->can('expense.edit'))
                                        <a href="{{ route('expense.edit', $expenses->id) }}"
                                            class="btn btn-sm btn-primary " title="Edit">
                                            Edit
                                        </a>
                                        @endif
                                        @if(Auth::user()->can('expense.delete'))
                                        <a href="{{ route('expense.delete', $expenses->id) }}" id="delete"
                                            class="btn btn-sm btn-danger " title="Delete">
                                            Delete
                                        </a>
                                        @endif
                                    </td>
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
</div>

