
<div class="row">
    <div class="col-md-12 ">
        <div id="" class="table-responsive">
            <h5>Expense List</h5><br>
            <table id="example" class="table w-100 display nowrap">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Account Name</th>
                        <th>Expense Date</th>
                        <th>Category</th>
                        <th>Purpose</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody class="showData">
                <?php
                $expenseTotalAmount = 0;
                ?>
                @if ($expense->count() > 0)
                @foreach ($expense as $key => $expenseData)
                    <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $expenseData['bank']['name'] ?? ''}}</td>
                    <td>{{$expenseData->expense_date}}</td>
                    <td>{{ $expenseData['expenseCat']['name'] ?? ''}}</td>
                    <td>{{$expenseData->purpose}}</td>

                    <td>{{ $expenseData->amount ?? '0'}} TK</td>
                    <?php
                    $expenseTotalAmount += isset(  $expenseData->amount ) ? $expenseData->amount : 0;
                    ?>
                    <td> @php
                        $text =$expenseData->note ?? '-';
                        $chunks = str_split($text, 40);
                        @endphp
                     @foreach ($chunks as $chunk)
                          {{ $chunk }}<br>
                      @endforeach
                      </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12">
                    <div class="text-center text-warning mb-2">Data Not Found</div>00
                </td>
            </tr>
            @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><strong>Total :{{ $expenseTotalAmount ?? '0' }}  Tk</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
